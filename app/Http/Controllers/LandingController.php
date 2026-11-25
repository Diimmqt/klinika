<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\SettingController;
use App\Mail\ContactInquiryMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Throwable;

class LandingController extends Controller
{
    public function index(): View
    {
        $hasLoginRoute = Route::has('login');
        $hasRegisterRoute = Route::has('register');
        $contactInfo = [
            'email' => $this->resolveContactEmail(),
            'phone' => config('klinika.contact.phone', ''),
            'address' => config('klinika.contact.address', ''),
            'hours' => config('klinika.contact.hours', 'Senin – Jumat, 08.00 – 17.00 WIB'),
        ];

        $teamRoles = [
            'Pendaftaran',
            'Perawat',
            'Dokter',
            'Apoteker',
            'Kasir',
            'Admin & Manajemen',
        ];

        $features = [
            [
                'number' => '01',
                'title' => 'Manajemen Pasien & Pendaftaran',
                'description' => 'Kelola identitas pasien, nomor rekam medis, dan registrasi kunjungan baru maupun pasien lama secara tertata.',
                'highlight' => 'Identitas jelas, pelayanan terarah',
                'featured' => true,
                'icon' => 'users',
            ],
            [
                'number' => '02',
                'title' => 'Rekam Medis Elektronik (RME)',
                'description' => 'Catat pemeriksaan awal, SOAP, diagnosis, tindakan medis, dan resep dalam satu riwayat kunjungan yang terstruktur.',
                'highlight' => 'Riwayat pasien mudah ditelusuri',
                'featured' => false,
                'icon' => 'document-text',
            ],
            [
                'number' => '03',
                'title' => 'Manajemen Antrean & Kunjungan',
                'description' => 'Pantau status antrean per poli dari pendaftaran, pemeriksaan awal perawat, konsultasi dokter, hingga selesai.',
                'highlight' => 'Alur poli berjalan lebih tenang',
                'featured' => false,
                'icon' => 'queue-list',
            ],
            [
                'number' => '04',
                'title' => 'Manajemen Farmasi & Persediaan',
                'description' => 'Teruskan resep dari dokter ke apoteker serta kelola katalog obat, pemantauan stok minimum, dan mutasi persediaan.',
                'highlight' => 'Resep dan stok saling terhubung',
                'featured' => false,
                'icon' => 'beaker',
            ],
            [
                'number' => '05',
                'title' => 'Kasir, Laporan & Analitik',
                'description' => 'Susun tagihan layanan, proses invoice, cetak kwitansi, tutup shift rekap kas, dan pantau ringkasan operasional klinik.',
                'highlight' => 'Gambaran klinik dalam satu tempat',
                'featured' => false,
                'icon' => 'chart-bar',
            ],
            [
                'number' => '06',
                'title' => 'Akun, Hak Akses & Audit Aktivitas',
                'description' => 'Atur peran setiap petugas klinik dengan pembatasan akses berbasis peran (RBAC) dan pencatatan aktivitas administratif.',
                'highlight' => 'Tanggung jawab setiap tim tetap jelas',
                'featured' => false,
                'icon' => 'shield-check',
            ],
        ];

        $journeySteps = [
            [
                'step' => '01',
                'title' => 'Pendaftaran Pasien',
                'role' => 'Petugas Pendaftaran',
                'description' => 'Identitas pasien dan kunjungan dicatat, lalu nomor antrean poli disiapkan otomatis.',
            ],
            [
                'step' => '02',
                'title' => 'Pemeriksaan Awal',
                'role' => 'Perawat',
                'description' => 'Perawat mencatat tanda vital, keluhan awal, dan kesiapan pasien sebelum masuk poli.',
            ],
            [
                'step' => '03',
                'title' => 'Pemeriksaan Dokter',
                'role' => 'Dokter',
                'description' => 'Dokter meninjau riwayat RME, melengkapi SOAP, diagnosis, tindakan, serta menerbitkan resep.',
            ],
            [
                'step' => '04',
                'title' => 'Resep & Farmasi',
                'role' => 'Apoteker',
                'description' => 'Apoteker memverifikasi resep final, menyiapkan obat, dan mencatat pengurangan stok.',
            ],
            [
                'step' => '05',
                'title' => 'Pembayaran & Selesai',
                'role' => 'Kasir',
                'description' => 'Kasir menyusun invoice, memproses pembayaran, mencetak kwitansi, dan merekap kas.',
            ],
        ];

        $workspaceMatrix = [
            [
                'role' => 'Pendaftaran',
                'scope' => 'Data pasien, kunjungan & antrean poli',
                'badge' => 'Operasional',
            ],
            [
                'role' => 'Perawat',
                'scope' => 'Antrean poli & pemeriksaan awal (tanda vital)',
                'badge' => 'Asesmen Awal',
            ],
            [
                'role' => 'Dokter',
                'scope' => 'Pemeriksaan medis, SOAP, RME & resep',
                'badge' => 'Klinis',
            ],
            [
                'role' => 'Apoteker',
                'scope' => 'Verifikasi resep, data obat & stok farmasi',
                'badge' => 'Farmasi',
            ],
            [
                'role' => 'Kasir',
                'scope' => 'Penyusunan tagihan, invoice & rekap kas',
                'badge' => 'Keuangan',
            ],
            [
                'role' => 'Admin & Manajemen',
                'scope' => 'Master poli/jadwal, pengguna, RBAC & audit',
                'badge' => 'Administrasi',
            ],
        ];

        $satusehatPillars = [
            [
                'title' => 'Struktur Data Klinis Terorganisir',
                'description' => 'Identitas pasien, nomor RM, kunjungan, pemeriksaan awal, catatan SOAP, dan resep disimpan terstruktur per ID kunjungan untuk memudahkan pemetaan ke resource standar HL7 FHIR.',
            ],
            [
                'title' => 'Keterlacakan & Finalisasi RME',
                'description' => 'Pemisahan status draf dan catatan final serta pencatatan audit aktivitas mendukung tata kelola data medis yang konsisten sebelum integrasi pertukaran data diaktifkan.',
            ],
            [
                'title' => 'Arsitektur Siap Ekstensi API',
                'description' => 'Dibangun dengan Laravel modular sehingga modul sinkronisasi SATUSEHAT (Organization, Patient, Encounter, Observation, MedicationRequest) dapat ditambahkan saat kredensial faskes tersedia.',
            ],
        ];

        $advantages = [
            [
                'title' => 'Kemudahan Pengelolaan Klinik',
                'description' => 'Master data poli, jadwal dokter, pengguna klinik, hingga rekap operasional dikelola dari satu antarmuka yang ringkas dan mudah dipahami petugas.',
            ],
            [
                'title' => 'Integrasi Alur Pelayanan',
                'description' => 'Data pasien dan kunjungan mengalir langsung dari pendaftaran ke perawat, dokter, farmasi, hingga kasir tanpa pencatatan ulang di ruang kerja terpisah.',
            ],
            [
                'title' => 'Pengelolaan Informasi Pasien',
                'description' => 'Nomor rekam medis, riwayat kunjungan, tanda vital, dan catatan klinis tersimpan rapi sehingga tenaga medis memperoleh konteks pasien dengan cepat.',
            ],
            [
                'title' => 'Keamanan & Pengelolaan Data',
                'description' => 'Hak akses dibatasi sesuai peran aktif (RBAC), sesi akun terkendali, dan aktivitas administratif penting tercatat untuk menjaga akuntabilitas klinik.',
            ],
        ];

        $faqs = [
            [
                'question' => 'Apa itu KLINIKA?',
                'answer' => 'KLINIKA adalah aplikasi manajemen operasional klinik dan Rekam Medis Elektronik (RME) berbasis web yang menghubungkan pendaftaran pasien, pemeriksaan awal perawat, pemeriksaan dokter, pelayanan farmasi, kasir, serta administrasi klinik dalam satu sistem.',
            ],
            [
                'question' => 'Siapa saja yang dapat menggunakan KLINIKA?',
                'answer' => 'Seluruh unit kerja di klinik memiliki ruang kerja khusus sesuai perannya, meliputi Petugas Pendaftaran, Perawat, Dokter, Apoteker, Kasir, Admin Klinik, dan Super Admin.',
            ],
            [
                'question' => 'Apakah semua pengguna bisa melihat data yang sama?',
                'answer' => 'Tidak. KLINIKA menerapkan kontrol akses berbasis peran (Role-Based Access Control). Menu, modul, dan informasi yang dapat diakses mengikuti peran serta izin yang ditetapkan oleh administrator klinik.',
            ],
            [
                'question' => 'Bagaimana cara mendapatkan akun untuk masuk ke KLINIKA?',
                'answer' => 'Pada sistem KLINIKA, pembuatan dan pengelolaan akun petugas dilakukan secara terpusat oleh Admin Klinik atau Super Admin untuk menjaga keamanan akses data medis. Hubungi administrator klinik Anda untuk aktivasi akun.',
            ],
            [
                'question' => 'Apakah KLINIKA sudah terhubung langsung dengan SATUSEHAT?',
                'answer' => 'Pada versi saat ini, koneksi langsung ke API SATUSEHAT Kementerian Kesehatan belum diaktifkan dan masih berada pada tahap persiapan (roadmap). Namun, pencatatan pasien, kunjungan, dan RME di KLINIKA telah disusun secara terstruktur agar siap dikembangkan menuju integrasi tersebut.',
            ],
            [
                'question' => 'Bagaimana keamanan data dan pencatatan RME dijaga?',
                'answer' => 'Setiap perubahan akun, peran, dan pengaturan tercatat pada audit aktivitas. Akses dilindungi autentikasi sesi dan pembatasan peran, serta pengelolaan kebijakan akses tetap mengikuti tata kelola masing-masing klinik.',
            ],
        ];

        return view('landing.index', compact(
            'hasLoginRoute',
            'hasRegisterRoute',
            'contactInfo',
            'teamRoles',
            'features',
            'journeySteps',
            'workspaceMatrix',
            'satusehatPillars',
            'advantages',
            'faqs',
        ));
    }

    public function submitContact(Request $request): RedirectResponse
    {
        if ($request->filled('website')) {
            return redirect(route('landing') . '#kontak')
                ->with('contact_success', 'Terima kasih. Pesan Anda telah kami terima.');
        }

        $validated = $request->validateWithBag('contact', [
            'name' => ['required', 'string', 'max:120'],
            'clinic_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'topic' => ['required', 'in:demo,fitur,satusehat,akses,lainnya'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'clinic_name.required' => 'Nama klinik atau instansi wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'topic.required' => 'Silakan pilih topik kebutuhan.',
            'message.required' => 'Pesan wajib diisi.',
            'message.min' => 'Pesan minimal terdiri dari 10 karakter.',
        ]);

        $inquiry = [
            'name' => $validated['name'],
            'clinic_name' => $validated['clinic_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'topic' => $validated['topic'],
            'message' => $validated['message'],
            'submitted_at' => now()->format('d M Y, H:i') . ' WIB',
        ];

        $recipientEmail = $this->resolveContactEmail();

        Log::info('KLINIKA landing contact inquiry received', [
            ...$inquiry,
            'recipient' => $recipientEmail,
            'ip' => $request->ip(),
        ]);

        try {
            Mail::to($recipientEmail)->send(new ContactInquiryMail($inquiry));
        } catch (Throwable $exception) {
            Log::error('Failed to send KLINIKA landing contact inquiry email', [
                'recipient' => $recipientEmail,
                'sender_email' => $inquiry['email'],
                'error' => $exception->getMessage(),
            ]);

            if (app()->isLocal()) {
                return redirect(route('landing') . '#kontak')
                    ->with('contact_warning', 'Pesan tersimpan di log, namun pengiriman email SMTP gagal: ' . $exception->getMessage());
            }
        }

        return redirect(route('landing') . '#kontak')
            ->with('contact_success', 'Terima kasih, ' . $validated['name'] . '. Pesan Anda telah tercatat dan akan ditindaklanjuti oleh tim administrasi KLINIKA.');
    }

    private function resolveContactEmail(): string
    {
        $configured = config('klinika.contact.email');
        if (is_string($configured) && trim($configured) !== '') {
            return trim($configured);
        }

        $systemSettings = Cache::get('system_settings', SettingController::DEFAULT_SETTINGS);

        return $systemSettings['admin_email'] ?? 'admin@klinika.test';
    }
}
