<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\Poli;
use App\Models\Queue;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NurseTest extends TestCase
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
            'name' => 'Ns. Maya Putri',
            'email' => 'perawat@klinika.id',
            'password' => bcrypt('password'),
        ]);

        $this->poli = Poli::create([
            'code' => 'POLI-UMUM',
            'name' => 'Poli Umum',
            'queue_prefix' => 'A',
            'is_active' => true,
        ]);

        $this->patient = Patient::create([
            'medical_record_number' => 'RM-000128',
            'nik' => '3273015405880002',
            'name' => 'Siti Aminah',
            'gender' => 'P',
            'birth_place' => 'Bandung',
            'birth_date' => '1988-05-14',
            'phone' => '081234567890',
            'created_by' => $this->user->id,
        ]);

        $this->visit = Visit::create([
            'visit_number' => 'VISIT-20261022-0001',
            'patient_id' => $this->patient->id,
            'poli_id' => $this->poli->id,
            'visit_date' => date('Y-m-d'),
            'complaint' => 'Batuk dan tenggorokan tidak nyaman',
            'status' => 'registered',
            'created_by' => $this->user->id,
        ]);

        $this->queue = Queue::createForVisit($this->visit);
    }

    public function test_can_render_nurse_dashboard_with_kpi_counters_and_waiting_list(): void
    {
        $response = $this->actingAs($this->user)->get(route('perawat.index'));

        $response->assertStatus(200);
        $response->assertSee('Pemeriksaan awal hari ini');
        $response->assertSee('Menunggu awal');
        $response->assertSee('Sedang diperiksa');
        $response->assertSee('Selesai pemeriksaan');
        $response->assertSee('Draf belum dikirim');
        $response->assertSee('Periksa dua identitas sebelum mencatat');
        $response->assertSee($this->patient->name);
        $response->assertSee($this->queue->queue_code);
    }

    public function test_can_render_nurse_queue_page_with_active_call_and_empty_state(): void
    {
        $response = $this->actingAs($this->user)->get(route('perawat.queue'));

        $response->assertStatus(200);
        $response->assertSee('Antrean pasien');
        $response->assertSee('Daftar antrean awal');
        $response->assertSee($this->patient->name);
        $response->assertSee($this->queue->queue_code);

        // Test filter with no match shows Figma empty state
        $emptyResponse = $this->actingAs($this->user)->get(route('perawat.queue', ['search' => 'NonExistentPatientXYZ']));
        $emptyResponse->assertStatus(200);
        $emptyResponse->assertSee('Tidak ada antrean yang sesuai filter');
        $emptyResponse->assertSee('Reset pencarian & filter', false);
    }

    public function test_can_render_examination_page_for_queue(): void
    {
        $response = $this->actingAs($this->user)->get(route('perawat.examination', $this->queue));

        $response->assertStatus(200);
        $response->assertSee('Pemeriksaan awal');
        $response->assertSee($this->patient->name);
        $response->assertSee('RM-000128');
        $response->assertSee('Keluhan dan riwayat singkat');
        $response->assertSee('Tanda vital');
        $response->assertSee('Sistolik (mmHg)');
        $response->assertSee('Diastolik (mmHg)');
        $response->assertSee('Kirim ke dokter');
    }

    public function test_can_save_draft_examination(): void
    {
        $payload = [
            'action' => 'draft',
            'keluhan_utama' => 'Batuk berdahak 2 hari',
            'sistolik' => 120,
            'diastolik' => 80,
            'nadi' => 80,
            'suhu' => 36.8,
            'spo2' => 99,
            'berat_badan' => 55,
            'kesadaran' => 'Compos mentis',
            'skala_nyeri' => 1,
            'observasi' => 'Pemeriksaan tanda vital awal berjalan lancar',
        ];

        $response = $this->actingAs($this->user)->post(route('perawat.examination.store', $this->queue), $payload);

        $response->assertRedirect(route('perawat.examination', $this->queue));
        $response->assertSessionHas('success');

        $this->visit->refresh();
        $this->queue->refresh();

        $assessment = $this->visit->assessment_data;
        $this->assertTrue($assessment['is_draft']);
        $this->assertEquals('Batuk berdahak 2 hari', $assessment['keluhan_utama']);
        $this->assertEquals(120, $assessment['vital_signs']['sistolik']);
        $this->assertEquals('sedang_dilayani', $this->queue->status);
    }

    public function test_can_submit_examination_to_doctor_and_completes_nurse_stage(): void
    {
        $payload = [
            'action' => 'submit',
            'keluhan_utama' => 'Batuk dan radang tenggorokan',
            'sistolik' => 118,
            'diastolik' => 76,
            'nadi' => 82,
            'suhu' => 37.2,
            'spo2' => 98,
            'berat_badan' => 58,
            'kesadaran' => 'Compos mentis',
            'skala_nyeri' => 2,
            'observasi' => 'Pasien datang mandiri. Siap ditinjau dokter.',
        ];

        $response = $this->actingAs($this->user)->post(route('perawat.examination.store', $this->queue), $payload);

        $response->assertRedirect(route('perawat.index'));
        $response->assertSessionHas('success');

        $this->visit->refresh();
        $this->queue->refresh();

        $assessment = $this->visit->assessment_data;
        $this->assertFalse($assessment['is_draft']);
        $this->assertEquals('Batuk dan radang tenggorokan', $assessment['keluhan_utama']);
        $this->assertEquals(118, $assessment['vital_signs']['sistolik']);
        $this->assertEquals('selesai', $this->queue->status);
    }

    public function test_submitting_examination_validates_required_vital_signs(): void
    {
        $payload = [
            'action' => 'submit',
            'keluhan_utama' => '', // kosong
            'sistolik' => '',
            'diastolik' => '',
            'nadi' => '',
            'suhu' => '',
            'berat_badan' => '',
            'kesadaran' => '',
        ];

        $response = $this->actingAs($this->user)->post(route('perawat.examination.store', $this->queue), $payload);

        $response->assertSessionHasErrors([
            'keluhan_utama',
            'sistolik',
            'diastolik',
            'nadi',
            'suhu',
            'berat_badan',
            'kesadaran',
        ]);
    }
}
