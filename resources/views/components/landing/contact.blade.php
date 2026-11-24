@props([
    'contactInfo' => [],
])

<section id="kontak" class="bg-[#F4F8F8] border-t border-[#DDE8E8] py-16 sm:py-20 lg:py-24 scroll-mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-start">
            {{-- Left Column: Intro & Contact Channels --}}
            <div data-reveal class="lg:col-span-5">
                <p class="text-xs font-semibold tracking-[0.08em] uppercase text-[#087F83]">
                    Kontak &amp; Jadwalkan Demo
                </p>
                <h2 class="mt-3 text-3xl sm:text-[36px] font-semibold text-[#182B35] leading-[1.2] tracking-[-0.02em]">
                    Diskusikan kebutuhan klinik Anda bersama kami.
                </h2>
                <p class="mt-4 text-sm sm:text-base text-[#627680] leading-[1.65]">
                    Punya pertanyaan seputar alur pelayanan, manajemen hak akses, atau ingin melihat demo KLINIKA lebih dekat? Kirim pesan melalui formulir di samping.
                </p>

                <div class="mt-8 space-y-4">
                    {{-- Email Card --}}
                    <div class="bg-white border border-[#DDE8E8] rounded-[12px] p-4 flex items-start gap-3.5">
                        <span class="w-10 h-10 rounded-lg bg-[#E8F5F3] text-[#087F83] flex items-center justify-center shrink-0" aria-hidden="true">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                        </span>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-[#627680]">Email Administrasi</p>
                            <a href="mailto:{{ $contactInfo['email'] ?? 'admin@klinika.test' }}"
                               class="mt-0.5 block text-sm font-semibold text-[#182B35] hover:text-[#087F83] transition-colors break-all">
                                {{ $contactInfo['email'] ?? 'admin@klinika.test' }}
                            </a>
                            <p class="mt-0.5 text-xs text-[#627680]">Untuk permintaan demo, pertanyaan produk, dan informasi akun.</p>
                        </div>
                    </div>

                    {{-- Optional Phone / WhatsApp Card (only shown if configured in .env) --}}
                    @if (! empty($contactInfo['phone']))
                        <div class="bg-white border border-[#DDE8E8] rounded-[12px] p-4 flex items-start gap-3.5">
                            <span class="w-10 h-10 rounded-lg bg-[#E8F5F3] text-[#087F83] flex items-center justify-center shrink-0" aria-hidden="true">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                                </svg>
                            </span>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-[#627680]">Telepon / WhatsApp</p>
                                <p class="mt-0.5 text-sm font-semibold text-[#182B35]">{{ $contactInfo['phone'] }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Support Hours & Scope --}}
                    <div class="bg-white border border-[#DDE8E8] rounded-[12px] p-4 flex items-start gap-3.5">
                        <span class="w-10 h-10 rounded-lg bg-[#E8F5F3] text-[#087F83] flex items-center justify-center shrink-0" aria-hidden="true">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-xs font-semibold text-[#627680]">Layanan &amp; Konsultasi</p>
                            <p class="mt-0.5 text-sm font-semibold text-[#182B35]">{{ $contactInfo['hours'] ?? 'Senin – Jumat, 08.00 – 17.00 WIB' }}</p>
                            <p class="mt-0.5 text-xs text-[#627680]">
                                {{ ! empty($contactInfo['address']) ? $contactInfo['address'] : 'Sesi pengenalan fitur dan konsultasi operasional tersedia secara daring.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Contact Form Card --}}
            <div data-reveal class="lg:col-span-7">
                <div class="bg-white border border-[#DDE8E8] rounded-[16px] p-6 sm:p-8 shadow-[0_10px_30px_rgba(8,127,131,0.06)]">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-[#182B35]">Kirim Pesan atau Permintaan Demo</h3>
                        <p class="mt-1 text-xs sm:text-sm text-[#627680]">Lengkapi detail di bawah ini. Kolom bertanda <span class="text-[#087F83] font-semibold">*</span> wajib diisi.</p>
                    </div>

                    @if (session('contact_success'))
                        <div role="status" class="mb-6 rounded-xl border border-[#A7F3D0] bg-[#ECFDF5] p-4 flex items-start gap-3 text-[#065F46]">
                            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-sm">
                                <p class="font-semibold">Pesan Anda berhasil dikirim</p>
                                <p class="mt-0.5 text-xs sm:text-sm opacity-90">{{ session('contact_success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($errors->contact->any())
                        <div role="alert" class="mb-6 rounded-xl border border-[#FECDD3] bg-[#FFF1F2] p-4 text-[#9F1239] text-sm">
                            <p class="font-semibold">Mohon periksa kembali isian formulir Anda:</p>
                            <ul class="mt-1.5 list-disc list-inside text-xs space-y-0.5">
                                @foreach ($errors->contact->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('landing.contact') }}" class="space-y-4" novalidate>
                        @csrf

                        {{-- Honeypot field (anti-bot) --}}
                        <div class="hidden" aria-hidden="true">
                            <label for="contact-website">Website</label>
                            <input type="text" id="contact-website" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="contact-name" class="block text-xs font-semibold text-[#182B35] mb-1.5">
                                    Nama Lengkap <span class="text-[#087F83]">*</span>
                                </label>
                                <input id="contact-name"
                                       type="text"
                                       name="name"
                                       value="{{ old('name') }}"
                                       required
                                       autocomplete="name"
                                       placeholder="Contoh: dr. Nadia Pratama"
                                       class="w-full h-11 px-3.5 rounded-lg border text-sm text-[#182B35] bg-[#FAFCFC] placeholder:text-[#627680]/70 transition-colors focus:outline-none focus:bg-white focus:border-[#087F83] focus:ring-2 focus:ring-[#087F83]/15 {{ $errors->contact->has('name') ? 'border-[#E11D48]' : 'border-[#DDE8E8]' }}">
                                @error('name', 'contact')
                                    <p class="mt-1 text-xs text-[#BE123C]">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="contact-clinic" class="block text-xs font-semibold text-[#182B35] mb-1.5">
                                    Nama Klinik / Instansi <span class="text-[#087F83]">*</span>
                                </label>
                                <input id="contact-clinic"
                                       type="text"
                                       name="clinic_name"
                                       value="{{ old('clinic_name') }}"
                                       required
                                       autocomplete="organization"
                                       placeholder="Contoh: Klinik Pratama Sehat"
                                       class="w-full h-11 px-3.5 rounded-lg border text-sm text-[#182B35] bg-[#FAFCFC] placeholder:text-[#627680]/70 transition-colors focus:outline-none focus:bg-white focus:border-[#087F83] focus:ring-2 focus:ring-[#087F83]/15 {{ $errors->contact->has('clinic_name') ? 'border-[#E11D48]' : 'border-[#DDE8E8]' }}">
                                @error('clinic_name', 'contact')
                                    <p class="mt-1 text-xs text-[#BE123C]">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="contact-email" class="block text-xs font-semibold text-[#182B35] mb-1.5">
                                    Email <span class="text-[#087F83]">*</span>
                                </label>
                                <input id="contact-email"
                                       type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required
                                       autocomplete="email"
                                       placeholder="nama@klinik.id"
                                       class="w-full h-11 px-3.5 rounded-lg border text-sm text-[#182B35] bg-[#FAFCFC] placeholder:text-[#627680]/70 transition-colors focus:outline-none focus:bg-white focus:border-[#087F83] focus:ring-2 focus:ring-[#087F83]/15 {{ $errors->contact->has('email') ? 'border-[#E11D48]' : 'border-[#DDE8E8]' }}">
                                @error('email', 'contact')
                                    <p class="mt-1 text-xs text-[#BE123C]">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="contact-phone" class="block text-xs font-semibold text-[#182B35] mb-1.5">
                                    Nomor WhatsApp / Telepon <span class="text-[#627680] font-normal">(opsional)</span>
                                </label>
                                <input id="contact-phone"
                                       type="tel"
                                       name="phone"
                                       value="{{ old('phone') }}"
                                       autocomplete="tel"
                                       placeholder="08xx-xxxx-xxxx"
                                       class="w-full h-11 px-3.5 rounded-lg border text-sm text-[#182B35] bg-[#FAFCFC] placeholder:text-[#627680]/70 transition-colors focus:outline-none focus:bg-white focus:border-[#087F83] focus:ring-2 focus:ring-[#087F83]/15 {{ $errors->contact->has('phone') ? 'border-[#E11D48]' : 'border-[#DDE8E8]' }}">
                                @error('phone', 'contact')
                                    <p class="mt-1 text-xs text-[#BE123C]">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="contact-topic" class="block text-xs font-semibold text-[#182B35] mb-1.5">
                                Topik Kebutuhan <span class="text-[#087F83]">*</span>
                            </label>
                            <select id="contact-topic"
                                    name="topic"
                                    required
                                    class="w-full h-11 px-3.5 rounded-lg border text-sm text-[#182B35] bg-[#FAFCFC] transition-colors focus:outline-none focus:bg-white focus:border-[#087F83] focus:ring-2 focus:ring-[#087F83]/15 {{ $errors->contact->has('topic') ? 'border-[#E11D48]' : 'border-[#DDE8E8]' }}">
                                @foreach ([
                                    'demo' => 'Jadwalkan Demo Produk',
                                    'fitur' => 'Pertanyaan Fitur & Alur Pelayanan',
                                    'satusehat' => 'Kesiapan Integrasi SATUSEHAT',
                                    'akses' => 'Bantuan Akses & Akun Klinik',
                                    'lainnya' => 'Lainnya',
                                ] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('topic', 'demo') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('topic', 'contact')
                                <p class="mt-1 text-xs text-[#BE123C]">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact-message" class="block text-xs font-semibold text-[#182B35] mb-1.5">
                                Pesan <span class="text-[#087F83]">*</span>
                            </label>
                            <textarea id="contact-message"
                                      name="message"
                                      rows="4"
                                      required
                                      placeholder="Ceritakan kebutuhan klinik Anda, jumlah poli, atau jadwal demo yang diinginkan..."
                                      class="w-full px-3.5 py-2.5 rounded-lg border text-sm text-[#182B35] bg-[#FAFCFC] placeholder:text-[#627680]/70 transition-colors focus:outline-none focus:bg-white focus:border-[#087F83] focus:ring-2 focus:ring-[#087F83]/15 resize-y {{ $errors->contact->has('message') ? 'border-[#E11D48]' : 'border-[#DDE8E8]' }}">{{ old('message') }}</textarea>
                            @error('message', 'contact')
                                <p class="mt-1 text-xs text-[#BE123C]">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <p class="text-[11px] text-[#627680] leading-relaxed">
                                Jangan mencantumkan data medis atau informasi sensitif pasien di formulir ini.
                            </p>
                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 h-11 px-6 rounded-lg bg-[#087F83] hover:bg-[#066669] active:scale-[0.99] text-white text-sm font-semibold transition-all shadow-xs cursor-pointer shrink-0 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#087F83] focus-visible:ring-offset-2">
                                <span>Kirim Pesan</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
