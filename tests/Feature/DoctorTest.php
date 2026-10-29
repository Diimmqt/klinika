<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\Poli;
use App\Models\Queue;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DoctorTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Poli $poli;
    protected Patient $patient;
    protected Visit $visit;
    protected Queue $queue;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'dr. Andi Prasetyo',
            'email' => 'dokter@klinika.id',
            'password' => bcrypt('password'),
        ]);

        $this->poli = Poli::create([
            'code' => 'POLI-UMUM',
            'name' => 'Poli Umum',
            'queue_prefix' => 'A',
            'is_active' => true,
        ]);

        $this->patient = Patient::create([
            'medical_record_number' => 'RM-000201',
            'nik' => '3273015405880009',
            'name' => 'Rina Kartika',
            'gender' => 'P',
            'birth_place' => 'Bandung',
            'birth_date' => '1992-08-20',
            'phone' => '081234567899',
            'created_by' => $this->user->id,
        ]);

        // Kunjungan dengan hasil pemeriksaan awal perawat
        $nurseAssessment = [
            'keluhan_utama' => 'Pusing berputar dan mual sejak 2 hari',
            'vital_signs' => [
                'sistolik' => 125,
                'diastolik' => 85,
                'nadi' => 84,
                'suhu' => 36.7,
                'spo2' => 99,
                'berat_badan' => 54,
                'kesadaran' => 'Compos mentis',
                'skala_nyeri' => 2,
            ],
            'catatan_perawat' => 'Pemeriksaan awal selesai, pasien menunggu dokter',
            'recorded_by' => 'Ns. Maya Putri',
            'recorded_at' => now()->format('Y-m-d H:i:s'),
            'is_draft' => false,
        ];

        $this->visit = Visit::create([
            'visit_number' => 'VISIT-20261028-0001',
            'patient_id' => $this->patient->id,
            'poli_id' => $this->poli->id,
            'visit_date' => date('Y-m-d'),
            'complaint' => json_encode(['nurse_assessment' => $nurseAssessment], JSON_UNESCAPED_UNICODE),
            'status' => 'registered',
            'created_by' => $this->user->id,
        ]);

        $this->queue = Queue::createForVisit($this->visit);
    }

    public function test_can_render_doctor_dashboard_with_kpi_counters_and_waiting_list(): void
    {
        $response = $this->actingAs($this->user)->get(route('dokter.index'));

        $response->assertStatus(200);
        $response->assertSee('Pelayanan Medis Hari Ini');
        $response->assertSee('Menunggu Dokter');
        $response->assertSee('Sedang Diperiksa');
        $response->assertSee('Selesai Hari Ini');
        $response->assertSee('Total Antrean Poli');
        $response->assertSee($this->patient->name);
        $response->assertSee($this->queue->queue_code);
    }

    public function test_can_render_doctor_queue_page_with_filters_and_search(): void
    {
        $response = $this->actingAs($this->user)->get(route('dokter.queue'));

        $response->assertStatus(200);
        $response->assertSee('Antrean Pasien Dokter');
        $response->assertSee($this->patient->name);
        $response->assertSee($this->queue->queue_code);

        // Filter pencarian
        $searchResponse = $this->actingAs($this->user)->get(route('dokter.queue', ['search' => 'Rina Kartika']));
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee($this->patient->name);

        $emptyResponse = $this->actingAs($this->user)->get(route('dokter.queue', ['search' => 'NonExistentPatient123']));
        $emptyResponse->assertStatus(200);
        $emptyResponse->assertSee('Tidak ada data antrean yang sesuai');
    }

    public function test_can_render_doctor_examination_page_with_nurse_vitals(): void
    {
        $response = $this->actingAs($this->user)->get(route('dokter.examination', $this->queue));

        $response->assertStatus(200);
        $response->assertSee('Pemeriksaan Medis', false);
        $response->assertSee($this->patient->name);
        $response->assertSee('RM-000201');
        $response->assertSee('Hasil Pemeriksaan Awal', false);
        $response->assertSee('125/85'); // Tekanan darah perawat
        $response->assertSee('Catatan Medis SOAP');
        $response->assertSee('Diagnosis Medis (ICD-10)');
        $response->assertSee('Tindakan Medis', false);
        $response->assertSee('Resep Obat Medis', false);
        $response->assertSee('Riwayat Kunjungan (RME)');
    }

    public function test_can_save_doctor_examination_draft(): void
    {
        $payload = [
            'action' => 'draft',
            'subjective' => 'Pasien mengeluhkan pusing berputar sejak kemarin sore.',
            'objective' => 'Nistagmus horizontal (+), Romberg test positif ringan.',
            'assessment' => 'Susp. BPPV kanalis posterior',
            'plan' => 'Manuver Dix-Hallpike dan Epley',
            'diagnosis_code' => ['H81.1'],
            'diagnosis_name' => ['Benign paroxysmal vertigo'],
            'diagnosis_type' => ['primary'],
        ];

        $response = $this->actingAs($this->user)->post(route('dokter.examination.store', $this->queue), $payload);

        $response->assertRedirect(route('dokter.examination', $this->queue));
        $response->assertSessionHas('success');

        $this->visit->refresh();
        $this->queue->refresh();

        $this->assertEquals('in_progress', $this->visit->status);
        $this->assertEquals('sedang_dilayani', $this->queue->status);

        $exam = $this->visit->doctor_examination;
        $this->assertTrue($exam['is_draft']);
        $this->assertEquals('Susp. BPPV kanalis posterior', $exam['soap']['assessment']);
        $this->assertEquals('H81.1', $exam['diagnosis'][0]['code']);
    }

    public function test_can_submit_complete_examination_and_completes_doctor_stage(): void
    {
        $payload = [
            'action' => 'submit',
            'subjective' => 'Pusing berputar dipicu gerakan kepala, mual (+), muntah (-).',
            'objective' => 'KU: sedang, Kesadaran: compos mentis. Nistagmus Dix-Hallpike (+). Telinga: membran timpani intak.',
            'assessment' => 'Benign Paroxysmal Positional Vertigo (BPPV)',
            'plan' => 'Manuver Epley, Betahistine mesylate 3x6mg p.o, edukasi istirahat.',
            'diagnosis_code' => ['H81.1', 'R11'],
            'diagnosis_name' => ['Benign paroxysmal vertigo', 'Nausea and vomiting'],
            'diagnosis_type' => ['primary', 'secondary'],
            'diagnosis_notes' => ['Kanalis posterior kanan', 'Gejala penyerta'],
            'actions' => [
                ['name' => 'Manuver Reposisi Kanalit (Epley)', 'notes' => 'Reposisi sukses, nistagmus berkurang'],
                ['name' => 'Konsultasi Medis & KIE', 'notes' => 'Edukasi posisi tidur elevasi 30 derajat'],
            ],
            'prescriptions' => [
                [
                    'medicine_name' => 'Betahistine Mesylate 6mg',
                    'dose' => '6mg',
                    'frequency' => '3x1 hari',
                    'instruction' => 'Sesudah makan',
                    'quantity' => 15,
                    'notes' => 'Bila pusing',
                ],
                [
                    'medicine_name' => 'Dimenhydrinate 50mg',
                    'dose' => '50mg',
                    'frequency' => '1x1 hari',
                    'instruction' => 'Bila mual berat',
                    'quantity' => 5,
                    'notes' => '',
                ],
            ],
        ];

        $response = $this->actingAs($this->user)->post(route('dokter.examination.store', $this->queue), $payload);

        $response->assertRedirect(route('dokter.index'));
        $response->assertSessionHas('success');

        $this->visit->refresh();
        $this->queue->refresh();

        $this->assertEquals('completed', $this->visit->status);
        $this->assertEquals('selesai', $this->queue->status);

        $exam = $this->visit->doctor_examination;
        $this->assertFalse($exam['is_draft']);
        $this->assertEquals('Benign Paroxysmal Positional Vertigo (BPPV)', $exam['soap']['assessment']);
        $this->assertCount(2, $exam['diagnosis']);
        $this->assertEquals('H81.1', $exam['diagnosis'][0]['code']);
        $this->assertCount(2, $exam['actions']);
        $this->assertCount(2, $exam['prescriptions']);
        $this->assertEquals('Betahistine Mesylate 6mg', $exam['prescriptions'][0]['medicine_name']);

        // Pastikan data perawat sebelumnya tidak hilang
        $assessment = $this->visit->assessment_data;
        $this->assertEquals('125', $assessment['vital_signs']['sistolik']);
    }

    public function test_submitting_examination_validates_soap_and_diagnosis(): void
    {
        $payload = [
            'action' => 'submit',
            'subjective' => '',
            'objective' => '',
            'assessment' => '',
            'plan' => '',
            'diagnosis_code' => [],
            'diagnosis_name' => [],
        ];

        $response = $this->actingAs($this->user)->post(route('dokter.examination.store', $this->queue), $payload);

        $response->assertSessionHasErrors([
            'subjective',
            'objective',
            'assessment',
            'plan',
            'diagnosis_code',
        ]);
    }

    public function test_can_render_patient_rme_history_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('dokter.rme', $this->patient));

        $response->assertStatus(200);
        $response->assertSee('Rekam Medis Elektronik (RME)');
        $response->assertSee($this->patient->name);
        $response->assertSee('RM-000201');
        $response->assertSee('VISIT-20261028-0001');
    }
}
