<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\Poli;
use App\Models\Practitioner;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PoliAndPractitionerTest extends TestCase
{
    use RefreshDatabase;

    public function test_poli_and_practitioner_pages_are_accessible_directly_without_login(): void
    {
        $poli = Poli::create([
            'code' => 'POLI-UMUM',
            'name' => 'Poli Umum',
            'queue_prefix' => 'A',
            'is_active' => true,
        ]);

        $practitioner = Practitioner::create([
            'name' => 'dr. Andi Prasetyo',
            'practitioner_type' => 'dokter',
            'is_active' => true,
        ]);

        $this->get('/poli')->assertOk()->assertSee('Pengelolaan Data Poli')->assertSee('Poli Umum');
        $this->get('/poli/create')->assertOk()->assertSee('Tambah Data Poli');
        $this->get("/poli/{$poli->id}")->assertOk()->assertSee('Poli Umum');
        $this->get("/poli/{$poli->id}/edit")->assertOk()->assertSee('Edit Data Poli');

        $this->get('/tenaga-kesehatan')->assertOk()->assertSee('Pengelolaan Data Tenaga Kesehatan')->assertSee('dr. Andi Prasetyo');
        $this->get('/tenaga-kesehatan/create')->assertOk()->assertSee('Tambah Tenaga Kesehatan');
        $this->get("/tenaga-kesehatan/{$practitioner->id}")->assertOk()->assertSee('dr. Andi Prasetyo');
        $this->get("/tenaga-kesehatan/{$practitioner->id}/edit")->assertOk()->assertSee('Edit Tenaga Kesehatan');
    }

    public function test_poli_crud_validation_status_toggle_and_delete_protection(): void
    {
        // Create valid poli (code & queue_prefix normalized uppercase)
        $response = $this->post('/poli', [
            'code' => 'poli-gigi',
            'name' => 'Poli Gigi & Mulut',
            'queue_prefix' => 'b',
            'is_active' => '1',
        ]);

        $poli = Poli::where('code', 'POLI-GIGI')->firstOrFail();
        $response->assertRedirect(route('polis.show', $poli));
        $this->assertSame('B', $poli->queue_prefix);
        $this->assertTrue($poli->is_active);

        // Validation rejects duplicate code and queue_prefix
        $this->post('/poli', [
            'code' => 'POLI-GIGI',
            'name' => 'Duplikat',
            'queue_prefix' => 'B',
            'is_active' => '1',
        ])->assertSessionHasErrors(['code', 'queue_prefix']);

        // Update poli
        $this->put("/poli/{$poli->id}", [
            'code' => 'POLI-GIGI',
            'name' => 'Poli Gigi Terpadu',
            'queue_prefix' => 'G',
            'is_active' => '1',
        ])->assertRedirect(route('polis.show', $poli));

        $this->assertDatabaseHas('polis', [
            'id' => $poli->id,
            'name' => 'Poli Gigi Terpadu',
            'queue_prefix' => 'G',
        ]);

        // Toggle status (Aktif -> Nonaktif -> Aktif)
        $this->patch("/poli/{$poli->id}/toggle-status")->assertRedirect();
        $this->assertFalse($poli->fresh()->is_active);

        $this->patch("/poli/{$poli->id}/toggle-status")->assertRedirect();
        $this->assertTrue($poli->fresh()->is_active);

        // Delete poli without visits succeeds
        $emptyPoli = Poli::create([
            'code' => 'POLI-TEMP',
            'name' => 'Poli Sementara',
            'queue_prefix' => 'Z',
            'is_active' => true,
        ]);
        $this->delete("/poli/{$emptyPoli->id}")->assertRedirect(route('polis.index'));
        $this->assertDatabaseMissing('polis', ['id' => $emptyPoli->id]);

        // Poli with existing visit cannot be deleted permanently
        $user = User::factory()->create();
        $patient = Patient::create([
            'medical_record_number' => 'RM-00001',
            'name' => 'Siti Aminah',
            'gender' => 'P',
            'birth_place' => 'Bandung',
            'birth_date' => '1990-01-01',
            'created_by' => $user->id,
        ]);
        Visit::create([
            'visit_number' => 'KJ-001',
            'patient_id' => $patient->id,
            'poli_id' => $poli->id,
            'visit_date' => now()->toDateString(),
            'status' => 'registered',
            'created_by' => $user->id,
        ]);

        $this->delete("/poli/{$poli->id}")->assertSessionHas('error');
        $this->assertDatabaseHas('polis', ['id' => $poli->id]);
    }

    public function test_practitioner_crud_profession_grouping_and_poli_connection_via_existing_schedules(): void
    {
        $poliUmum = Poli::create(['code' => 'POLI-UMUM', 'name' => 'Poli Umum', 'queue_prefix' => 'A', 'is_active' => true]);
        $poliKia = Poli::create(['code' => 'POLI-KIA', 'name' => 'Poli KIA & Anak', 'queue_prefix' => 'C', 'is_active' => true]);

        // Validation rejects invalid profession
        $this->post('/tenaga-kesehatan', [
            'name' => 'Test Invalid',
            'practitioner_type' => 'bidan_invalid',
            'is_active' => '1',
        ])->assertSessionHasErrors(['practitioner_type']);

        // Store practitioner connected to Poli Umum & Poli KIA
        $response = $this->post('/tenaga-kesehatan', [
            'name' => 'dr. Laila Nuraini',
            'practitioner_type' => 'dokter',
            'str_number' => 'STR-12345',
            'sip_number' => 'SIP-67890',
            'poli_ids' => [$poliUmum->id, $poliKia->id],
            'is_active' => '1',
        ]);

        $doctor = Practitioner::where('name', 'dr. Laila Nuraini')->firstOrFail();
        $response->assertRedirect(route('practitioners.show', $doctor));

        // Relation works bidirectionally via existing schedules table without new migrations
        $this->assertCount(2, $doctor->polis);
        $this->assertTrue($poliUmum->practitioners->contains('id', $doctor->id));
        $this->assertTrue($poliKia->practitioners->contains('id', $doctor->id));

        // Detail poli shows connected practitioner; detail practitioner shows connected polis
        $this->get("/poli/{$poliUmum->id}")->assertOk()->assertSee('dr. Laila Nuraini');
        $this->get("/tenaga-kesehatan/{$doctor->id}")->assertOk()->assertSee('Poli Umum')->assertSee('Poli KIA & Anak');

        // Profession grouping & filter
        $nurse = Practitioner::create([
            'name' => 'Ns. Rina Susanti',
            'practitioner_type' => 'perawat',
            'is_active' => true,
        ]);
        $nurse->syncPoliAssignments([$poliUmum->id]);

        $this->get('/tenaga-kesehatan?profession=dokter')
            ->assertOk()
            ->assertSee('dr. Laila Nuraini')
            ->assertDontSee('Ns. Rina Susanti');

        $this->get("/tenaga-kesehatan?poli_id={$poliKia->id}")
            ->assertOk()
            ->assertSee('dr. Laila Nuraini')
            ->assertDontSee('Ns. Rina Susanti');

        // Preserve existing custom schedule slot on kept poli when updating poli assignments
        DB::table('schedules')
            ->where('practitioner_id', $doctor->id)
            ->where('poli_id', $poliUmum->id)
            ->update(['day_of_week' => 3, 'start_time' => '09:30:00', 'end_time' => '13:00:00', 'quota' => 25]);

        $this->put("/tenaga-kesehatan/{$doctor->id}", [
            'name' => 'dr. Laila Nuraini, Sp.A',
            'practitioner_type' => 'dokter',
            'poli_ids' => [$poliUmum->id],
            'is_active' => '1',
        ])->assertRedirect(route('practitioners.show', $doctor));

        $this->assertDatabaseHas('schedules', [
            'practitioner_id' => $doctor->id,
            'poli_id' => $poliUmum->id,
            'day_of_week' => 3,
            'start_time' => '09:30:00',
            'quota' => 25,
        ]);
        $this->assertDatabaseMissing('schedules', [
            'practitioner_id' => $doctor->id,
            'poli_id' => $poliKia->id,
        ]);

        // Toggle status & delete protection
        $this->patch("/tenaga-kesehatan/{$nurse->id}/toggle-status")->assertRedirect();
        $this->assertFalse($nurse->fresh()->is_active);

        $this->delete("/tenaga-kesehatan/{$nurse->id}")->assertRedirect(route('practitioners.index'));
        $this->assertDatabaseMissing('practitioners', ['id' => $nurse->id]);
    }
}
