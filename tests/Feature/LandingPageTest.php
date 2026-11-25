<?php

namespace Tests\Feature;

use App\Mail\ContactInquiryMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_landing_page_with_all_required_sections(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Pelayanan Kesehatan Lebih Terintegrasi, Dimulai dari', false);
        $response->assertSee('KLINIKA.');
        $response->assertSee('Fitur Unggulan');
        $response->assertSee('Alur Pelayanan');
        $response->assertSee('Preview Dashboard');
        $response->assertSee('Integrasi SATUSEHAT');
        $response->assertSee('Tentang Kami');
        $response->assertSee('Pertanyaan Umum');
        $response->assertSee('Kontak');
        $response->assertSee(route('landing.contact'), false);
        $response->assertSee(route('login'), false);

        if (! Route::has('register')) {
            $response->assertDontSee('/register', false);
        }
    }

    public function test_contact_form_accepts_valid_submission(): void
    {
        Mail::fake();
        config(['klinika.contact.email' => 'penerima-test@example.test']);

        $response = $this->post(route('landing.contact'), [
            'name' => 'dr. Nadia Pratama',
            'clinic_name' => 'Klinik Pratama Sehat',
            'email' => 'nadia@klinik.example',
            'phone' => '081234567890',
            'topic' => 'demo',
            'message' => 'Kami ingin menjadwalkan demo KLINIKA untuk 3 poli minggu depan.',
        ]);

        $response->assertRedirect(route('landing') . '#kontak');
        $response->assertSessionHas('contact_success');

        Mail::assertSent(ContactInquiryMail::class, function (ContactInquiryMail $mail) {
            return $mail->hasTo('penerima-test@example.test')
                && $mail->hasReplyTo('nadia@klinik.example', 'dr. Nadia Pratama')
                && $mail->inquiry['clinic_name'] === 'Klinik Pratama Sehat';
        });
    }

    public function test_contact_form_ignores_honeypot_spam_without_sending_mail(): void
    {
        Mail::fake();

        $response = $this->post(route('landing.contact'), [
            'website' => 'https://spam.example',
            'name' => 'Bot',
            'clinic_name' => 'Spam',
            'email' => 'bot@spam.example',
            'topic' => 'demo',
            'message' => 'Spam message content.',
        ]);

        $response->assertRedirect(route('landing') . '#kontak');
        Mail::assertNothingSent();
    }

    public function test_contact_form_validates_required_fields(): void
    {
        $response = $this->from('/#kontak')->post(route('landing.contact'), [
            'name' => '',
            'clinic_name' => '',
            'email' => 'bukan-email',
            'topic' => 'invalid',
            'message' => 'pendek',
        ]);

        $response->assertSessionHasErrors(['name', 'clinic_name', 'email', 'topic', 'message'], null, 'contact');
    }

    public function test_authenticated_user_can_view_landing_page_and_sees_dashboard_cta(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee(route('dashboard'), false);
        $response->assertSee('Buka Dashboard');
    }

    public function test_login_page_remains_accessible(): void
    {
        $this->get('/login')->assertStatus(200);
    }
}
