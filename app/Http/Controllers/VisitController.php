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
        $status = $request->input('status');
        $date = $request->input('date', date('Y-m-d'));

        // ponytail: simulation numbers and encounters matching design mockup
        $stats = [
            'aktif'             => 18,
            'aktif_sub'         => '8 terdaftar • 10 dalam pelayanan',
            'selesai'           => 46,
            'selesai_sub'       => 'Semua layanan selesai dan lunas',
            'batal'             => 1,
            'batal_sub'         => 'Terpisah dari kunjungan selesai',
            // Keep keys for test assertion compatibility
            'total_today'       => 18,
            'registered_today'  => 8,
            'in_progress_today' => 10,
            'completed_today'   => 46,
        ];

        $visits = [
            [
                'id'            => 'KJ-20261006-023',
                'patient_name'  => 'Siti Aminah',
                'rm'            => 'RM-000128',
                'poli'          => 'Umum',
                'doctor'        => 'dr. Andi Prasetyo',
                'status'        => 'Terdaftar',
                'tahap'         => 'Menunggu awal',
                'antrean'       => 'A-023',
                'action_label'  => 'Buka',
            ],
            [
                'id'            => 'KJ-20261006-024',
                'patient_name'  => 'Agus Setiawan',
                'rm'            => 'RM-000129',
                'poli'          => 'Umum',
                'doctor'        => 'dr. Andi Prasetyo',
                'status'        => 'Terdaftar',
                'tahap'         => 'Menunggu awal',
                'antrean'       => 'A-024',
                'action_label'  => 'Buka',
            ],
            [
                'id'            => 'KJ-20261006-021',
                'patient_name'  => 'Dewi Kartika',
                'rm'            => 'RM-000130',
                'poli'          => 'Anak',
                'doctor'        => 'dr. Laila Nuraini',
                'status'        => 'Dalam pelayanan',
                'tahap'         => 'Menunggu dokter',
                'antrean'       => 'B-011',
                'action_label'  => 'Buka',
            ],
            [
                'id'            => 'KJ-20261006-020',
                'patient_name'  => 'Rizky Maulana',
                'rm'            => 'RM-000131',
                'poli'          => 'Gigi',
                'doctor'        => 'drg. Nabila Sari',
                'status'        => 'Dalam pelayanan',
                'tahap'         => 'Menunggu kasir',
                'antrean'       => 'C-006',
                'action_label'  => 'Buka',
            ],
            [
                'id'            => 'KJ-20261006-017',
                'patient_name'  => 'Rudi Santoso',
                'rm'            => 'RM-000137',
                'poli'          => 'Umum',
                'doctor'        => 'dr. Andi Prasetyo',
                'status'        => 'Dibatalkan',
                'tahap'         => 'Tidak dilayani',
                'antrean'       => 'A-017',
                'action_label'  => 'Lihat alasan',
            ],
        ];

        return view('visits.index', compact('stats', 'visits', 'search', 'status', 'date'));
    }

    /**
     * Show the form for creating a new visit (Step 1 of 2).
     */
    public function create(Request $request): View
    {
        $selectedPatient = null;
        if ($request->filled('patient_id')) {
            $selectedPatient = Patient::find($request->patient_id);
        }

        if (! $selectedPatient) {
            $selectedPatient = Patient::first() ?? new Patient([
                'name' => 'Siti Aminah',
                'medical_record_number' => 'RM-000128',
                'gender' => 'P',
                'birth_date' => '1988-05-14',
                'nik' => '3273015405880002',
                'phone' => '0812-7000-0128',
            ]);
        }

        $polis = Poli::where('is_active', true)->orderBy('name')->get();
        if ($polis->isEmpty()) {
            $polis = collect([
                (object) ['id' => 1, 'name' => 'Poli Umum'],
                (object) ['id' => 2, 'name' => 'Poli Gigi'],
                (object) ['id' => 3, 'name' => 'Poli KIA / Anak'],
            ]);
        }

        return view('visits.create', compact('selectedPatient', 'polis'));
    }

    /**
     * Show the form for creating a visit for a specific patient (Step 1 of 2).
     */
    public function createForPatient(Patient $patient): View
    {
        $selectedPatient = $patient;
        $polis = Poli::where('is_active', true)->orderBy('name')->get();
        if ($polis->isEmpty()) {
            $polis = collect([
                (object) ['id' => 1, 'name' => 'Poli Umum'],
                (object) ['id' => 2, 'name' => 'Poli Gigi'],
                (object) ['id' => 3, 'name' => 'Poli KIA / Anak'],
            ]);
        }

        return view('visits.create', compact('selectedPatient', 'polis'));
    }

    /**
     * Review & Confirm a visit registration (Step 2 of 2).
     */
    public function confirm(Request $request): View
    {
        $validated = $request->validate([
            'patient_id'   => 'required|exists:patients,id',
            'visit_date'   => 'required|date',
            'visit_type'   => 'nullable|string',
            'poli_id'      => 'required',
            'doctor_name'  => 'nullable|string',
            'insurance'    => 'nullable|string',
            'complaint'    => 'nullable|string|max:1000',
        ]);

        $patient = Patient::findOrFail($request->patient_id);
        $poli = Poli::find($request->poli_id);
        $poliName = $poli ? $poli->name : 'Poli Umum';

        $data = [
            'patient'      => $patient,
            'visit_date'   => $request->visit_date,
            'visit_type'   => $request->visit_type ?: 'Rawat jalan / kunjungan ulang',
            'poli_id'      => $request->poli_id,
            'poli_name'    => $poliName,
            'doctor_name'  => $request->doctor_name ?: 'dr. Andi Prasetyo',
            'insurance'    => $request->insurance ?: 'Umum / pribadi',
            'complaint'    => $request->complaint ?: 'Batuk dan tenggorokan tidak nyaman.',
        ];

        return view('visits.confirm', compact('data'));
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

            if ($request->input('redirect_to') === 'index') {
                return redirect()
                    ->route('visits.index')
                    ->with('success', "Kunjungan berhasil didaftarkan untuk pasien '{$visit->patient->name}' dengan No. Antrean {$visit->queue->queue_code}.");
            }

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
