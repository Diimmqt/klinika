<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RbacSeeder::class);

        $this->user = User::where('email', 'superadmin@klinika.id')->firstOrFail();
        $this->actingAs($this->user);
    }

    public function test_can_render_patient_index_page(): void
    {
        $response = $this->get(route('patients.index'));

        $response->assertStatus(200);
        $response->assertSee('Data Pasien');
        $response->assertSee('Tambah Pasien Baru');
    }

    public function test_can_create_patient_and_auto_generates_medical_record_number(): void
    {
        $payload = [
            'nik' => '3273010101900001',
            'name' => 'Budi Santoso',
            'gender' => 'L',
            'birth_place' => 'Bandung',
            'birth_date' => '1990-01-01',
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 10, Bandung',
            'blood_type' => 'O',
            'satusehat_ihs_number' => 'P12345678901',
        ];

        $response = $this->post(route('patients.store'), $payload);

        $patient = Patient::where('nik', '3273010101900001')->first();
        $this->assertNotNull($patient);
        $this->assertStringStartsWith('RM-' . date('Y') . '-', $patient->medical_record_number);
        $this->assertEquals('Budi Santoso', $patient->name);

        $response->assertRedirect(route('patients.show', $patient));
        $response->assertSessionHas('success');
    }

    public function test_validation_fails_when_required_fields_are_missing(): void
    {
        $response = $this->post(route('patients.store'), []);

        $response->assertSessionHasErrors([
            'name',
            'gender',
            'birth_place',
            'birth_date',
        ]);
    }

    public function test_validation_fails_for_invalid_nik(): void
    {
        $response = $this->post(route('patients.store'), [
            'name' => 'Siti Aminah',
            'gender' => 'P',
            'birth_place' => 'Jakarta',
            'birth_date' => '1995-05-15',
            'nik' => '12345', // Kurang dari 16 digit
        ]);

        $response->assertSessionHasErrors('nik');
    }

    public function test_can_search_patients_by_name_or_nik_or_mrn(): void
    {
        $patient = Patient::create([
            'medical_record_number' => Patient::generateMedicalRecordNumber(),
            'nik' => '3273010202920002',
            'name' => 'Dewi Lestari',
            'gender' => 'P',
            'birth_place' => 'Surabaya',
            'birth_date' => '1992-02-02',
            'created_by' => $this->user->id,
        ]);

        // Cari berdasarkan nama
        $resName = $this->get(route('patients.index', ['search' => 'Dewi']));
        $resName->assertStatus(200);
        $resName->assertSee('Dewi Lestari');

        // Cari berdasarkan NIK
        $resNik = $this->get(route('patients.index', ['search' => '3273010202920002']));
        $resNik->assertStatus(200);
        $resNik->assertSee('Dewi Lestari');

        // Cari berdasarkan No. RM
        $resMrn = $this->get(route('patients.index', ['search' => $patient->medical_record_number]));
        $resMrn->assertStatus(200);
        $resMrn->assertSee('Dewi Lestari');
    }

    public function test_can_render_patient_detail_page(): void
    {
        $patient = Patient::create([
            'medical_record_number' => Patient::generateMedicalRecordNumber(),
            'nik' => '3273010303930003',
            'name' => 'Ahmad Fauzi',
            'gender' => 'L',
            'birth_place' => 'Cimahi',
            'birth_date' => '1993-03-03',
            'address' => 'Jl. Kebon Jeruk No. 5',
            'created_by' => $this->user->id,
        ]);

        $response = $this->get(route('patients.show', $patient));

        $response->assertStatus(200);
        $response->assertSee('Ahmad Fauzi');
        $response->assertSee($patient->medical_record_number);
        $response->assertSee('Cimahi');
    }

    public function test_can_update_patient_data(): void
    {
        $patient = Patient::create([
            'medical_record_number' => Patient::generateMedicalRecordNumber(),
            'nik' => '3273010404940004',
            'name' => 'Rina Wijaya',
            'gender' => 'P',
            'birth_place' => 'Semarang',
            'birth_date' => '1994-04-04',
            'created_by' => $this->user->id,
        ]);

        $response = $this->put(route('patients.update', $patient), [
            'nik' => '3273010404940004',
            'name' => 'Rina Wijaya Handayani',
            'gender' => 'P',
            'birth_place' => 'Semarang',
            'birth_date' => '1994-04-04',
            'phone' => '087788990011',
            'address' => 'Jl. Dago Atas No. 12',
        ]);

        $response->assertRedirect(route('patients.show', $patient));
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'name' => 'Rina Wijaya Handayani',
            'phone' => '087788990011',
        ]);
    }

    public function test_can_soft_delete_patient(): void
    {
        $patient = Patient::create([
            'medical_record_number' => Patient::generateMedicalRecordNumber(),
            'nik' => '3273010505950005',
            'name' => 'Bambang Sudirman',
            'gender' => 'L',
            'birth_place' => 'Solo',
            'birth_date' => '1995-05-05',
            'created_by' => $this->user->id,
        ]);

        $response = $this->delete(route('patients.destroy', $patient));

        $response->assertRedirect(route('patients.index'));
        $this->assertSoftDeleted('patients', [
            'id' => $patient->id,
        ]);
    }
}
