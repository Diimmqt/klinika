<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use App\Models\Queue;
use App\Models\Visit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class NurseController extends Controller
{
    /**
     * Dashboard Perawat - Pemeriksaan awal hari ini.
     * Mengikuti desain Figma Frame: "Dashboard • Perawat".
     */
    public function index(Request $request): View
    {
        $date = $request->input('date', date('Y-m-d'));
        $poliId = $request->input('poli_id');

        $baseQuery = Queue::with(['visit.patient', 'poli'])
            ->whereDate('queue_date', $date);

        if ($poliId) {
            $baseQuery->where('poli_id', $poliId);
        }

        // 4 KPI Counters sesuai Figma
        $menungguCount = (clone $baseQuery)->where('status', 'menunggu')->count();
        $sedangDiperiksaCount = (clone $baseQuery)->whereIn('status', ['dipanggil', 'sedang_dilayani'])->count();
        $selesaiCount = (clone $baseQuery)->where('status', 'selesai')->count();

        // Cari antrean yang sedang aktif diperiksa
        $activePatient = (clone $baseQuery)
            ->whereIn('status', ['dipanggil', 'sedang_dilayani'])
            ->orderBy('updated_at', 'desc')
            ->first();

        // Cari data draf yang belum dikirim ke dokter
        $draftQueues = (clone $baseQuery)
            ->where('status', 'sedang_dilayani')
            ->orderBy('updated_at', 'desc')
            ->get();
        $drafCount = $draftQueues->count();

        // Daftar antrean tunggu untuk tabel "Berikutnya di antrean awal"
        $waitingQueues = (clone $baseQuery)
            ->where('status', 'menunggu')
            ->orderBy('queue_number', 'asc')
            ->paginate(10)
            ->withQueryString();

        $polis = Poli::where('is_active', true)->orderBy('name')->get();

        return view('nurse.index', compact(
            'waitingQueues',
            'menungguCount',
            'sedangDiperiksaCount',
            'selesaiCount',
            'drafCount',
            'activePatient',
            'draftQueues',
            'polis',
            'date',
            'poliId'
        ));
    }

    /**
     * Antrean Pasien Perawat dengan monitoring live dan empty state.
     * Mengikuti desain Figma Frame: "Antrean • Pemeriksaan awal dan dokter" & "Antrean • Keadaan kosong".
     */
    public function queue(Request $request): View
    {
        $date = $request->input('date', date('Y-m-d'));
        $poliId = $request->input('poli_id');
        $status = $request->input('status');
        $search = trim((string) $request->input('search'));

        $query = Queue::with(['visit.patient', 'poli'])
            ->whereDate('queue_date', $date);

        if ($poliId) {
            $query->where('poli_id', $poliId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('queue_code', 'like', "%{$search}%")
                    ->orWhereHas('visit.patient', function ($p) use ($search) {
                        $p->where('name', 'like', "%{$search}%")
                            ->orWhere('medical_record_number', 'like', "%{$search}%")
                            ->orWhere('nik', 'like', "%{$search}%");
                    })
                    ->orWhereHas('visit', function ($v) use ($search) {
                        $v->where('visit_number', 'like', "%{$search}%");
                    });
            });
        }

        $queues = $query->orderBy('queue_number', 'asc')->paginate(12)->withQueryString();

        // Panggilan aktif saat ini
        $activeCall = Queue::with(['visit.patient', 'poli'])
            ->whereDate('queue_date', $date)
            ->whereIn('status', ['dipanggil', 'sedang_dilayani'])
            ->when($poliId, fn ($q) => $q->where('poli_id', $poliId))
            ->orderBy('updated_at', 'desc')
            ->first();

        $stats = [
            'menunggu' => Queue::whereDate('queue_date', $date)->where('status', 'menunggu')->when($poliId, fn ($q) => $q->where('poli_id', $poliId))->count(),
            'dipanggil' => Queue::whereDate('queue_date', $date)->where('status', 'dipanggil')->when($poliId, fn ($q) => $q->where('poli_id', $poliId))->count(),
        ];

        $polis = Poli::where('is_active', true)->orderBy('name')->get();

        return view('nurse.queue', compact('queues', 'activeCall', 'stats', 'polis', 'date', 'poliId', 'status', 'search'));
    }

    /**
     * Tampilan Formulir Pemeriksaan Awal & Tanda Vital.
     * Mengikuti desain Figma Frame: "Perawat • Pemeriksaan awal".
     */
    public function examination(Queue $queue): View
    {
        $queue->load(['visit.patient', 'visit.poli', 'visit.creator']);

        $assessment = $queue->visit->assessment_data ?? [];
        $vitals = $assessment['vital_signs'] ?? [];

        return view('nurse.examination', compact('queue', 'assessment', 'vitals'));
    }

    /**
     * Simpan hasil pemeriksaan awal & tanda vital (Draf atau Kirim ke Dokter).
     */
    public function storeExamination(Request $request, Queue $queue): RedirectResponse
    {
        $action = $request->input('action', 'submit'); // 'draft' | 'submit'

        if ($action === 'submit') {
            $request->validate([
                'keluhan_utama' => 'required|string|max:2000',
                'sistolik' => 'required|numeric|min:40|max:300',
                'diastolik' => 'required|numeric|min:20|max:200',
                'nadi' => 'required|numeric|min:30|max:250',
                'suhu' => 'required|numeric|min:30|max:45',
                'spo2' => 'nullable|numeric|min:50|max:100',
                'berat_badan' => 'required|numeric|min:1|max:300',
                'kesadaran' => 'required|string|in:Compos mentis,Apatis,Somnolen,Sopor,Coma',
                'skala_nyeri' => 'nullable|integer|min:0|max:10',
                'observasi' => 'nullable|string|max:2000',
            ], [
                'keluhan_utama.required' => 'Keluhan utama wajib diisi.',
                'sistolik.required' => 'Nilai tekanan darah sistolik wajib diisi.',
                'diastolik.required' => 'Nilai tekanan darah diastolik wajib diisi.',
                'nadi.required' => 'Frekuensi denyut nadi wajib diisi.',
                'suhu.required' => 'Suhu tubuh wajib diisi.',
                'berat_badan.required' => 'Berat badan pasien wajib diisi.',
                'kesadaran.required' => 'Tingkat kesadaran pasien wajib dipilih.',
            ]);
        } else {
            // Untuk Simpan Draf, validasi lebih longgar
            $request->validate([
                'keluhan_utama' => 'nullable|string|max:2000',
                'sistolik' => 'nullable|numeric',
                'diastolik' => 'nullable|numeric',
                'nadi' => 'nullable|numeric',
                'suhu' => 'nullable|numeric',
                'spo2' => 'nullable|numeric',
                'berat_badan' => 'nullable|numeric',
                'kesadaran' => 'nullable|string',
                'skala_nyeri' => 'nullable|integer',
                'observasi' => 'nullable|string',
            ]);
        }

        try {
            DB::transaction(function () use ($request, $queue, $action) {
                $assessmentPayload = [
                    'keluhan_utama' => $request->input('keluhan_utama'),
                    'vital_signs' => [
                        'sistolik' => $request->input('sistolik'),
                        'diastolik' => $request->input('diastolik'),
                        'nadi' => $request->input('nadi'),
                        'suhu' => $request->input('suhu'),
                        'spo2' => $request->input('spo2'),
                        'berat_badan' => $request->input('berat_badan'),
                        'kesadaran' => $request->input('kesadaran', 'Compos mentis'),
                        'skala_nyeri' => $request->input('skala_nyeri'),
                    ],
                    'catatan_perawat' => $request->input('observasi'),
                    'recorded_by' => auth()->user()?->name ?? 'Ns. Maya Putri',
                    'recorded_at' => now()->format('Y-m-d H:i:s'),
                    'is_draft' => ($action === 'draft'),
                ];

                // Simpan payload assessment terstruktur ke complaint visit tanpa migrasi tambahan
                $queue->visit->update([
                    'complaint' => json_encode($assessmentPayload, JSON_UNESCAPED_UNICODE),
                ]);

                if ($action === 'submit') {
                    // Update antrean ke selesai perawat / dikirim ke dokter
                    if ($queue->status === 'menunggu' || $queue->status === 'dipanggil') {
                        $queue->updateStatus('sedang_dilayani', auth()->id());
                    }
                    $queue->updateStatus('selesai', auth()->id());
                } else {
                    // Simpan Draf: tandai sedang dilayani bila masih menunggu
                    if ($queue->status === 'menunggu') {
                        $queue->updateStatus('dipanggil', auth()->id());
                        $queue->updateStatus('sedang_dilayani', auth()->id());
                    }
                }
            });

            $patientName = $queue->visit->patient->name;
            if ($action === 'submit') {
                return redirect()
                    ->route('perawat.index')
                    ->with('success', "Pemeriksaan awal pasien {$patientName} ({$queue->queue_code}) berhasil disimpan dan dikirim ke antrean dokter.");
            }

            return redirect()
                ->route('perawat.examination', $queue)
                ->with('success', "Draf pemeriksaan awal pasien {$patientName} ({$queue->queue_code}) berhasil disimpan sementara.");
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Simpan gagal • koneksi ke server terputus: ' . $e->getMessage());
        }
    }
}
