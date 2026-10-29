<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Poli;
use App\Models\Queue;
use App\Models\Visit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DoctorController extends Controller
{
    /**
     * Dashboard Dokter - Ringkasan antrean dan aktivitas pemeriksaan medis hari ini.
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

        // KPI Counters
        $menungguCount = (clone $baseQuery)->where('status', 'menunggu')->count();
        $sedangDiperiksaCount = (clone $baseQuery)->whereIn('status', ['dipanggil', 'sedang_dilayani'])->count();
        $selesaiCount = (clone $baseQuery)->where('status', 'selesai')->count();
        $totalCount = (clone $baseQuery)->count();

        // Antrean aktif dokter
        $activePatient = (clone $baseQuery)
            ->whereIn('status', ['dipanggil', 'sedang_dilayani'])
            ->orderBy('updated_at', 'desc')
            ->first();

        // Antrean yang siap / sedang menunggu pemeriksaan
        $waitingQueues = (clone $baseQuery)
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->orderBy('queue_number', 'asc')
            ->paginate(10)
            ->withQueryString();

        $polis = Poli::where('is_active', true)->orderBy('name')->get();

        return view('doctor.index', compact(
            'waitingQueues',
            'menungguCount',
            'sedangDiperiksaCount',
            'selesaiCount',
            'totalCount',
            'activePatient',
            'polis',
            'date',
            'poliId'
        ));
    }

    /**
     * Antrean Pasien Dokter dengan pencarian, filter, dan monitoring panggilan.
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

        $activeCall = Queue::with(['visit.patient', 'poli'])
            ->whereDate('queue_date', $date)
            ->whereIn('status', ['dipanggil', 'sedang_dilayani'])
            ->when($poliId, fn ($q) => $q->where('poli_id', $poliId))
            ->orderBy('updated_at', 'desc')
            ->first();

        $stats = [
            'total' => Queue::whereDate('queue_date', $date)->when($poliId, fn ($q) => $q->where('poli_id', $poliId))->count(),
            'menunggu' => Queue::whereDate('queue_date', $date)->where('status', 'menunggu')->when($poliId, fn ($q) => $q->where('poli_id', $poliId))->count(),
            'sedang_dilayani' => Queue::whereDate('queue_date', $date)->whereIn('status', ['dipanggil', 'sedang_dilayani'])->when($poliId, fn ($q) => $q->where('poli_id', $poliId))->count(),
            'selesai' => Queue::whereDate('queue_date', $date)->where('status', 'selesai')->when($poliId, fn ($q) => $q->where('poli_id', $poliId))->count(),
        ];

        $polis = Poli::where('is_active', true)->orderBy('name')->get();

        return view('doctor.queue', compact('queues', 'activeCall', 'stats', 'polis', 'date', 'poliId', 'status', 'search'));
    }

    /**
     * Tampilan Formulir Pemeriksaan Medis Dokter & Akses RME Pasien.
     */
    public function examination(Queue $queue): View
    {
        $queue->load([
            'visit.patient.visits' => function ($q) use ($queue) {
                $q->where('id', '!=', $queue->visit_id)->with(['poli', 'queue'])->latest();
            },
            'visit.poli',
            'visit.creator',
            'visit.practitioner',
        ]);

        $patient = $queue->visit->patient;
        $pastVisits = $patient->visits;

        // Data pemeriksaan awal perawat
        $assessment = $queue->visit->assessment_data ?? [];
        $vitals = $assessment['vital_signs'] ?? [];

        // Data pemeriksaan dokter tersimpan / draf
        $doctorExam = $queue->visit->doctor_examination ?? [];
        $soap = $doctorExam['soap'] ?? [
            'subjective' => $assessment['keluhan_utama'] ?? ($queue->visit->complaint && !is_array(json_decode($queue->visit->complaint, true)) ? $queue->visit->complaint : ''),
            'objective' => '',
            'assessment' => '',
            'plan' => '',
        ];
        $diagnoses = $doctorExam['diagnosis'] ?? [];
        $actions = $doctorExam['actions'] ?? [];
        $prescriptions = $doctorExam['prescriptions'] ?? [];

        // Rekomendasi master diagnosis ICD-10 umum untuk autocomplete / pilihan cepat
        $commonIcd10 = [
            ['code' => 'I10', 'name' => 'Essential (primary) hypertension'],
            ['code' => 'J00', 'name' => 'Acute nasopharyngitis (common cold)'],
            ['code' => 'J02.9', 'name' => 'Acute pharyngitis, unspecified'],
            ['code' => 'J06.9', 'name' => 'Acute upper respiratory infection, unspecified'],
            ['code' => 'E11.9', 'name' => 'Type 2 diabetes mellitus without complications'],
            ['code' => 'K29.7', 'name' => 'Gastritis, unspecified'],
            ['code' => 'K30', 'name' => 'Functional dyspepsia'],
            ['code' => 'R50.9', 'name' => 'Fever, unspecified'],
            ['code' => 'R51', 'name' => 'Headache'],
            ['code' => 'M79.1', 'name' => 'Myalgia'],
            ['code' => 'A09', 'name' => 'Infectious gastroenteritis and colitis, unspecified'],
            ['code' => 'L23.9', 'name' => 'Allergic contact dermatitis, unspecified cause'],
            ['code' => 'K02.9', 'name' => 'Dental caries, unspecified'],
            ['code' => 'H10.9', 'name' => 'Conjunctivitis, unspecified'],
        ];

        return view('doctor.examination', compact(
            'queue',
            'patient',
            'pastVisits',
            'assessment',
            'vitals',
            'doctorExam',
            'soap',
            'diagnoses',
            'actions',
            'prescriptions',
            'commonIcd10'
        ));
    }

    /**
     * Simpan Hasil Pemeriksaan Medis Dokter (Draf / Selesai).
     */
    public function storeExamination(Request $request, Queue $queue): RedirectResponse
    {
        $action = $request->input('action', 'submit'); // 'draft' | 'submit'

        if ($action === 'submit') {
            $request->validate([
                'subjective' => 'required|string|max:3000',
                'objective' => 'required|string|max:3000',
                'assessment' => 'required|string|max:3000',
                'plan' => 'required|string|max:3000',
                'diagnosis_code' => 'required|array|min:1',
                'diagnosis_code.*' => 'required|string|max:20',
                'diagnosis_name' => 'required|array|min:1',
                'diagnosis_name.*' => 'required|string|max:255',
                'diagnosis_type' => 'nullable|array',
                'diagnosis_notes' => 'nullable|array',
                'actions' => 'nullable|array',
                'actions.*.name' => 'nullable|string|max:255',
                'actions.*.notes' => 'nullable|string|max:500',
                'prescriptions' => 'nullable|array',
                'prescriptions.*.medicine_name' => 'nullable|string|max:255',
                'prescriptions.*.dose' => 'nullable|string|max:100',
                'prescriptions.*.frequency' => 'nullable|string|max:100',
                'prescriptions.*.instruction' => 'nullable|string|max:255',
                'prescriptions.*.quantity' => 'nullable|numeric|min:1',
            ], [
                'subjective.required' => 'Subjective (S) keluhan pasien wajib diisi.',
                'objective.required' => 'Objective (O) hasil pemeriksaan fisik wajib diisi.',
                'assessment.required' => 'Assessment (A) penilaian klinis wajib diisi.',
                'plan.required' => 'Plan (P) rencana terapi / penanganan wajib diisi.',
                'diagnosis_code.required' => 'Setidaknya satu diagnosis ICD-10 harus dicantumkan.',
                'diagnosis_code.*.required' => 'Kode diagnosis ICD-10 tidak boleh kosong.',
                'diagnosis_name.*.required' => 'Nama diagnosis tidak boleh kosong.',
            ]);
        } else {
            // Validasi draf lebih fleksibel
            $request->validate([
                'subjective' => 'nullable|string|max:3000',
                'objective' => 'nullable|string|max:3000',
                'assessment' => 'nullable|string|max:3000',
                'plan' => 'nullable|string|max:3000',
                'diagnosis_code' => 'nullable|array',
                'diagnosis_name' => 'nullable|array',
                'actions' => 'nullable|array',
                'prescriptions' => 'nullable|array',
            ]);
        }

        try {
            DB::transaction(function () use ($request, $queue, $action) {
                // Pertahankan riwayat data assessment perawat yang sudah ada
                $existingPayload = $queue->visit->clinical_payload;
                $nurseAssessment = $existingPayload['nurse_assessment'] ?? ($existingPayload['vital_signs'] ? $existingPayload : []);

                // Susun format diagnosis terstruktur
                $diagnosesList = [];
                $codes = $request->input('diagnosis_code', []);
                $names = $request->input('diagnosis_name', []);
                $types = $request->input('diagnosis_type', []);
                $notes = $request->input('diagnosis_notes', []);

                foreach ($codes as $idx => $code) {
                    if (trim((string) $code) !== '') {
                        $diagnosesList[] = [
                            'code' => trim((string) $code),
                            'name' => trim((string) ($names[$idx] ?? '')),
                            'type' => $types[$idx] ?? ($idx === 0 ? 'primary' : 'secondary'),
                            'notes' => trim((string) ($notes[$idx] ?? '')),
                        ];
                    }
                }

                // Susun tindakan medis
                $actionsList = [];
                $rawActions = $request->input('actions', []);
                if (is_array($rawActions)) {
                    foreach ($rawActions as $act) {
                        $name = trim((string) ($act['name'] ?? ''));
                        if ($name !== '') {
                            $actionsList[] = [
                                'name' => $name,
                                'notes' => trim((string) ($act['notes'] ?? '')),
                            ];
                        }
                    }
                }

                // Susun resep obat
                $prescriptionsList = [];
                $rawPrescriptions = $request->input('prescriptions', []);
                if (is_array($rawPrescriptions)) {
                    foreach ($rawPrescriptions as $rx) {
                        $medName = trim((string) ($rx['medicine_name'] ?? ''));
                        if ($medName !== '') {
                            $prescriptionsList[] = [
                                'medicine_name' => $medName,
                                'dose' => trim((string) ($rx['dose'] ?? '')),
                                'frequency' => trim((string) ($rx['frequency'] ?? '')),
                                'instruction' => trim((string) ($rx['instruction'] ?? '')),
                                'quantity' => (int) ($rx['quantity'] ?? 1),
                                'notes' => trim((string) ($rx['notes'] ?? '')),
                            ];
                        }
                    }
                }

                $doctorExamPayload = [
                    'soap' => [
                        'subjective' => $request->input('subjective'),
                        'objective' => $request->input('objective'),
                        'assessment' => $request->input('assessment'),
                        'plan' => $request->input('plan'),
                    ],
                    'diagnosis' => $diagnosesList,
                    'actions' => $actionsList,
                    'prescriptions' => $prescriptionsList,
                    'doctor_name' => auth()->user()?->name ?? 'dr. Andi Prasetyo',
                    'doctor_id' => auth()->id(),
                    'examined_at' => now()->format('Y-m-d H:i:s'),
                    'is_draft' => ($action === 'draft'),
                ];

                $mergedPayload = [
                    'nurse_assessment' => $nurseAssessment,
                    'doctor_examination' => $doctorExamPayload,
                ];

                // Simpan ke kolom complaint tabel visits secara terstruktur
                $queue->visit->update([
                    'complaint' => json_encode($mergedPayload, JSON_UNESCAPED_UNICODE),
                    'status' => ($action === 'submit') ? 'completed' : 'in_progress',
                ]);

                if ($action === 'submit') {
                    // Update antrean menjadi selesai
                    $queue->updateStatus('selesai', auth()->id());
                } else {
                    // Update antrean menjadi sedang dilayani jika sebelumnya menunggu
                    if ($queue->status === 'menunggu' || $queue->status === 'dipanggil') {
                        $queue->updateStatus('sedang_dilayani', auth()->id());
                    }
                }
            });

            $patientName = $queue->visit->patient->name;
            if ($action === 'submit') {
                return redirect()
                    ->route('dokter.index')
                    ->with('success', "Pemeriksaan medis pasien {$patientName} ({$queue->queue_code}) berhasil diselesaikan.");
            }

            return redirect()
                ->route('dokter.examination', $queue)
                ->with('success', "Draf pemeriksaan medis pasien {$patientName} ({$queue->queue_code}) berhasil disimpan.");
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan pemeriksaan medis: ' . $e->getMessage());
        }
    }

    /**
     * Halaman RME (Rekam Medis Elektronik) Lengkap Pasien.
     * Menampilkan seluruh riwayat kunjungan masa lalu dan catatan medis historis.
     */
    public function rme(Patient $patient): View
    {
        $patient->load(['visits.poli', 'visits.queue', 'visits.creator', 'creator']);
        $visits = $patient->visits;

        return view('doctor.rme', compact('patient', 'visits'));
    }
}

