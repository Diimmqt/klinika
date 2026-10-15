<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVisitRequest;
use App\Models\Patient;
use App\Models\Poli;
use App\Models\Queue;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VisitController extends Controller
{
    /**
     * Display a listing of clinic visits with filter and search.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));
        $poliId = $request->input('poli_id');
        $status = $request->input('status');
        $date = $request->input('date', date('Y-m-d'));

        $query = Visit::with(['patient', 'poli', 'queue', 'creator']);

        if ($date) {
            $query->whereDate('visit_date', $date);
        }

        if ($poliId) {
            $query->where('poli_id', $poliId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('visit_number', 'like', "%{$search}%")
                    ->orWhereHas('patient', function ($p) use ($search) {
                        $p->where('name', 'like', "%{$search}%")
                            ->orWhere('medical_record_number', 'like', "%{$search}%")
                            ->orWhere('nik', 'like', "%{$search}%");
                    })
                    ->orWhereHas('queue', function ($qu) use ($search) {
                        $qu->where('queue_code', 'like', "%{$search}%");
                    });
            });
        }

        $visits = $query->latest('id')->paginate(10)->withQueryString();

        $todayDate = date('Y-m-d');
        $stats = [
            'total_today' => Visit::whereDate('visit_date', $todayDate)->count(),
            'registered_today' => Visit::whereDate('visit_date', $todayDate)->where('status', 'registered')->count(),
            'in_progress_today' => Visit::whereDate('visit_date', $todayDate)->where('status', 'in_progress')->count(),
            'completed_today' => Visit::whereDate('visit_date', $todayDate)->where('status', 'completed')->count(),
        ];

        $polis = Poli::where('is_active', true)->orderBy('name')->get();

        return view('visits.index', compact('visits', 'stats', 'polis', 'search', 'poliId', 'status', 'date'));
    }

    /**
     * Show the form for creating a new visit.
     */
    public function create(Request $request): View
    {
        $selectedPatient = null;
        if ($request->filled('patient_id')) {
            $selectedPatient = Patient::find($request->patient_id);
        }

        $patients = Patient::orderBy('name')->limit(50)->get(['id', 'name', 'medical_record_number', 'nik', 'phone']);
        $polis = Poli::where('is_active', true)->orderBy('name')->get();

        return view('visits.create', compact('selectedPatient', 'patients', 'polis'));
    }

    /**
     * Store a newly created visit and atomically generate queue number in storage.
     */
    public function store(StoreVisitRequest $request): RedirectResponse
    {
        try {
            $visit = DB::transaction(function () use ($request) {
                $visitNumber = Visit::generateVisitNumber();

                $createdBy = auth()->id();
                if (! $createdBy) {
                    $defaultUser = User::firstOrCreate(
                        ['email' => 'admin@klinika.id'],
                        [
                            'name' => 'Petugas Pendaftaran',
                            'password' => bcrypt('password'),
                        ]
                    );
                    $createdBy = $defaultUser->id;
                }

                $visit = Visit::create([
                    'visit_number' => $visitNumber,
                    'patient_id' => $request->patient_id,
                    'poli_id' => $request->poli_id,
                    'visit_date' => $request->visit_date,
                    'complaint' => $request->complaint,
                    'status' => 'registered',
                    'created_by' => $createdBy,
                ]);

                // Generate atomic Queue counter and record for this visit
                Queue::createForVisit($visit);

                return $visit;
            });

            $visit->load(['patient', 'poli', 'queue']);

            return redirect()
                ->route('visits.show', $visit)
                ->with('success', "Kunjungan berhasil dibuat untuk pasien '{$visit->patient->name}' dengan No. Antrean {$visit->queue->queue_code}.");
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat kunjungan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified visit and its queue ticket.
     */
    public function show(Visit $visit): View
    {
        $visit->load(['patient', 'poli', 'queue.statusLogs.user', 'creator']);

        return view('visits.show', compact('visit'));
    }

    /**
     * Cancel the specified visit.
     */
    public function destroy(Visit $visit): RedirectResponse
    {
        try {
            DB::transaction(function () use ($visit) {
                $visit->update(['status' => 'cancelled']);

                if ($visit->queue && $visit->queue->status !== 'selesai') {
                    $visit->queue->update(['status' => 'selesai']);
                }
            });

            return redirect()
                ->route('visits.index')
                ->with('success', "Kunjungan {$visit->visit_number} berhasil dibatalkan.");
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal membatalkan kunjungan: ' . $e->getMessage());
        }
    }
}
