<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\Poli;
use App\Models\Queue;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitAndQueueTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Poli $poliUmum;
    protected Poli $poliGigi;
    protected Patient $patient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Petugas Pendaftaran',
            'email' => 'admin@klinika.id',
            'password' => bcrypt('password'),
        ]);

        $this->poliUmum = Poli::create([
            'code' => 'POLI-UMUM',
            'name' => 'Poli Umum',
            'queue_prefix' => 'A',
            'is_active' => true,
        ]);

        $this->poliGigi = Poli::create([
            'code' => 'POLI-GIGI',
            'name' => 'Poli Gigi & Mulut',
            'queue_prefix' => 'B',
            'is_active' => true,
        ]);

        $this->patient = Patient::create([
            'medical_record_number' => Patient::generateMedicalRecordNumber(),
            'nik' => '3273010101900001',
            'name' => 'Siti Aminah',
            'gender' => 'P',
            'birth_place' => 'Bandung',
            'birth_date' => '1990-05-15',
            'phone' => '081234567890',
            'created_by' => $this->user->id,
        ]);
    }

    public function test_can_render_visits_index_page(): void
    {
        $response = $this->get(route('visits.index'));

        $response->assertStatus(200);
        $response->assertSee('Kunjungan Pasien');
        $response->assertSee('Daftarkan Kunjungan Baru');
    }

    public function test_can_create_visit_and_auto_generates_visit_number_and_queue_ticket(): void
    {
        $payload = [
            'patient_id' => $this->patient->id,
            'poli_id' => $this->poliUmum->id,
            'visit_date' => date('Y-m-d'),
            'complaint' => 'Demam dan sakit kepala sejak 2 hari yang lalu',
        ];

        $response = $this->post(route('visits.store'), $payload);

        $visit = Visit::first();
        $this->assertNotNull($visit);
        $this->assertEquals($this->patient->id, $visit->patient_id);
        $this->assertEquals($this->poliUmum->id, $visit->poli_id);
        $this->assertStringStartsWith('VISIT-' . date('Ymd') . '-', $visit->visit_number);
        $this->assertEquals('registered', $visit->status);

        // Pastikan queue tiket otomatis terbentuk dan terhubung
        $queue = Queue::where('visit_id', $visit->id)->first();
        $this->assertNotNull($queue);
        $this->assertEquals(1, $queue->queue_number);
        $this->assertEquals('A-001', $queue->queue_code);
        $this->assertEquals('menunggu', $queue->status);

        // Pastikan initial status log terbentuk
        $this->assertDatabaseHas('queue_status_logs', [
            'queue_id' => $queue->id,
            'to_status' => 'menunggu',
        ]);

        $response->assertRedirect(route('visits.show', $visit));
        $response->assertSessionHas('success');
    }

    public function test_cannot_create_visit_without_valid_patient_or_poli(): void
    {
        $response = $this->post(route('visits.store'), [
            'patient_id' => 99999, // tidak ada di DB
            'poli_id' => 88888,    // tidak ada di DB
            'visit_date' => 'invalid-date',
        ]);

        $response->assertSessionHasErrors([
            'patient_id',
            'poli_id',
            'visit_date',
        ]);
    }

    public function test_queue_numbers_increment_sequentially_per_poli(): void
    {
        // Kunjungan 1 ke Poli Umum -> A-001
        $this->post(route('visits.store'), [
            'patient_id' => $this->patient->id,
            'poli_id' => $this->poliUmum->id,
            'visit_date' => date('Y-m-d'),
        ]);

        $patient2 = Patient::create([
            'medical_record_number' => Patient::generateMedicalRecordNumber(),
            'nik' => '3273010101900002',
            'name' => 'Budi Santoso',
            'gender' => 'L',
            'birth_place' => 'Jakarta',
            'birth_date' => '1988-08-08',
            'created_by' => $this->user->id,
        ]);

        // Kunjungan 2 ke Poli Gigi -> B-001 (prefix B, mulai dari 1)
        $this->post(route('visits.store'), [
            'patient_id' => $patient2->id,
            'poli_id' => $this->poliGigi->id,
            'visit_date' => date('Y-m-d'),
        ]);

        // Kunjungan 3 kembali ke Poli Umum -> A-002 (urutan ke-2 untuk Poli Umum)
        $this->post(route('visits.store'), [
            'patient_id' => $patient2->id,
            'poli_id' => $this->poliUmum->id,
            'visit_date' => date('Y-m-d'),
        ]);

        $queues = Queue::orderBy('id', 'asc')->get();
        $this->assertCount(3, $queues);
        $this->assertEquals('A-001', $queues[0]->queue_code);
        $this->assertEquals('B-001', $queues[1]->queue_code);
        $this->assertEquals('A-002', $queues[2]->queue_code);
    }

    public function test_multiple_visits_for_same_patient(): void
    {
        // Satu pasien melakukan 2 kunjungan di tanggal berbeda
        $visit1 = Visit::create([
            'visit_number' => Visit::generateVisitNumber(),
            'patient_id' => $this->patient->id,
            'poli_id' => $this->poliUmum->id,
            'visit_date' => '2026-10-01',
            'status' => 'completed',
            'created_by' => $this->user->id,
        ]);
        Queue::createForVisit($visit1);

        $visit2 = Visit::create([
            'visit_number' => Visit::generateVisitNumber(),
            'patient_id' => $this->patient->id,
            'poli_id' => $this->poliGigi->id,
            'visit_date' => '2026-10-15',
            'status' => 'registered',
            'created_by' => $this->user->id,
        ]);
        Queue::createForVisit($visit2);

        $this->patient->refresh();
        $this->assertCount(2, $this->patient->visits);
    }

    public function test_can_render_visit_detail_page_and_queue_ticket(): void
    {
        $visit = Visit::create([
            'visit_number' => Visit::generateVisitNumber(),
            'patient_id' => $this->patient->id,
            'poli_id' => $this->poliUmum->id,
            'visit_date' => date('Y-m-d'),
            'status' => 'registered',
            'complaint' => 'Sakit tenggorokan',
            'created_by' => $this->user->id,
        ]);
        $queue = Queue::createForVisit($visit);

        $response = $this->get(route('visits.show', $visit));

        $response->assertStatus(200);
        $response->assertSee($visit->visit_number);
        $response->assertSee($queue->queue_code);
        $response->assertSee($this->patient->name);
        $response->assertSee('Sakit tenggorokan');
    }

    public function test_can_render_queue_monitor_page(): void
    {
        $visit = Visit::create([
            'visit_number' => Visit::generateVisitNumber(),
            'patient_id' => $this->patient->id,
            'poli_id' => $this->poliUmum->id,
            'visit_date' => date('Y-m-d'),
            'status' => 'registered',
            'created_by' => $this->user->id,
        ]);
        $queue = Queue::createForVisit($visit);

        $response = $this->get(route('queues.index'));

        $response->assertStatus(200);
        $response->assertSee('Antrean Pasien');
        $response->assertSee($queue->queue_code);
        $response->assertSee($this->patient->name);
    }

    public function test_can_update_queue_status_and_logs_history(): void
    {
        $visit = Visit::create([
            'visit_number' => Visit::generateVisitNumber(),
            'patient_id' => $this->patient->id,
            'poli_id' => $this->poliUmum->id,
            'visit_date' => date('Y-m-d'),
            'status' => 'registered',
            'created_by' => $this->user->id,
        ]);
        $queue = Queue::createForVisit($visit);

        // Ubah status ke 'dipanggil'
        $response = $this->patch(route('queues.update-status', $queue), [
            'status' => 'dipanggil',
        ]);

        $response->assertRedirect();
        $queue->refresh();
        $this->assertEquals('dipanggil', $queue->status);
        $this->assertNotNull($queue->called_at);

        // Pastikan status log tercatat
        $this->assertDatabaseHas('queue_status_logs', [
            'queue_id' => $queue->id,
            'from_status' => 'menunggu',
            'to_status' => 'dipanggil',
        ]);

        // Lanjut ke 'sedang_dilayani'
        $this->patch(route('queues.update-status', $queue), [
            'status' => 'sedang_dilayani',
        ]);
        $queue->refresh();
        $visit->refresh();
        $this->assertEquals('sedang_dilayani', $queue->status);
        $this->assertEquals('in_progress', $visit->status);

        // Lanjut ke 'selesai'
        $this->patch(route('queues.update-status', $queue), [
            'status' => 'selesai',
        ]);
        $queue->refresh();
        $visit->refresh();
        $this->assertEquals('selesai', $queue->status);
        $this->assertEquals('completed', $visit->status);
    }

    public function test_patient_detail_page_displays_visit_history(): void
    {
        $visit = Visit::create([
            'visit_number' => Visit::generateVisitNumber(),
            'patient_id' => $this->patient->id,
            'poli_id' => $this->poliUmum->id,
            'visit_date' => date('Y-m-d'),
            'status' => 'registered',
            'created_by' => $this->user->id,
        ]);
        $queue = Queue::createForVisit($visit);

        $response = $this->get(route('patients.show', $this->patient));

        $response->assertStatus(200);
        $response->assertSee('Riwayat Kunjungan Pasien');
        $response->assertSee($visit->visit_number);
        $response->assertSee($queue->queue_code);
        $response->assertSee('+ Buat Kunjungan');
    }

    public function test_can_cancel_visit(): void
    {
        $visit = Visit::create([
            'visit_number' => Visit::generateVisitNumber(),
            'patient_id' => $this->patient->id,
            'poli_id' => $this->poliUmum->id,
            'visit_date' => date('Y-m-d'),
            'status' => 'registered',
            'created_by' => $this->user->id,
        ]);
        Queue::createForVisit($visit);

        $response = $this->delete(route('visits.destroy', $visit));

        $response->assertRedirect(route('visits.index'));
        $visit->refresh();
        $this->assertEquals('cancelled', $visit->status);
    }
}
