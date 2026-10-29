<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use App\Models\Queue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QueueController extends Controller
{
    /**
     * Display the clinic queue monitor and management list.
     */
    public function index(Request $request): View
    {
        $date = $request->input('date', date('Y-m-d'));
        $poliId = $request->input('poli_id');
        $status = $request->input('status');

        $query = Queue::with(['visit.patient', 'poli', 'statusLogs'])
            ->whereDate('queue_date', $date);

        if ($poliId) {
            $query->where('poli_id', $poliId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $queues = $query->orderBy('queue_number', 'asc')->paginate(15)->withQueryString();

        $allQueuesToday = Queue::whereDate('queue_date', $date);
        if ($poliId) {
            $allQueuesToday->where('poli_id', $poliId);
        }

        $stats = [
            'total' => (clone $allQueuesToday)->count(),
            'menunggu' => (clone $allQueuesToday)->where('status', 'menunggu')->count(),
            'dipanggil' => (clone $allQueuesToday)->where('status', 'dipanggil')->count(),
            'sedang_dilayani' => (clone $allQueuesToday)->where('status', 'sedang_dilayani')->count(),
            'selesai' => (clone $allQueuesToday)->where('status', 'selesai')->count(),
        ];

        // Active queues per poli for the live call board
        $polis = Poli::where('is_active', true)->orderBy('name')->get();

        $activePerPoli = [];
        foreach ($polis as $poli) {
            $currentCalling = Queue::where('poli_id', $poli->id)
                ->whereDate('queue_date', $date)
                ->whereIn('status', ['dipanggil', 'sedang_dilayani'])
                ->orderBy('updated_at', 'desc')
                ->first();

            $nextWaiting = Queue::where('poli_id', $poli->id)
                ->whereDate('queue_date', $date)
                ->where('status', 'menunggu')
                ->orderBy('queue_number', 'asc')
                ->first();

            $activePerPoli[$poli->id] = [
                'current' => $currentCalling,
                'next' => $nextWaiting,
            ];
        }

        return view('queues.index', compact('queues', 'stats', 'polis', 'date', 'poliId', 'status', 'activePerPoli'));
    }

    /**
     * Update the queue status (menunggu -> dipanggil -> sedang_dilayani -> selesai).
     */
    public function updateStatus(Request $request, Queue $queue): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:menunggu,dipanggil,sedang_dilayani,selesai',
        ]);

        try {
            $queue->updateStatus($request->status, auth()->id());

            return back()->with('success', "Status antrean {$queue->queue_code} ({$queue->visit->patient->name}) berhasil diubah menjadi: {$queue->status_label}.");
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal mengubah status antrean: ' . $e->getMessage());
        }
    }
}
