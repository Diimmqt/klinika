@props([
    'satusehatPillars' => [],
])

<section id="satusehat" class="bg-[#F3F9F8] border-y border-[#DDE8E8] py-16 sm:py-20 lg:py-24 scroll-mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-start">
            {{-- Left Column: Honest positioning & readiness details --}}
            <div data-reveal class="lg:col-span-7">
                <div class="flex flex-wrap items-center gap-2.5">
                    <span class="text-xs font-semibold tracking-[0.08em] uppercase text-[#087F83]">
                        Integrasi SATUSEHAT
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#FFF5E4] border border-[#F5D9A8] text-[#96611C] text-[11px] font-semibold">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#D97706]" aria-hidden="true"></span>
                        Tahap Persiapan &bull; Roadmap
                    </span>
                </div>

                <h2 class="mt-3 text-3xl sm:text-[36px] font-semibold text-[#182B35] leading-[1.2] tracking-[-0.02em]">
                    Dirancang terstruktur agar siap menuju integrasi SATUSEHAT.
                </h2>

                <p class="mt-4 text-sm sm:text-base text-[#627680] leading-[1.65]">
                    Sebagai bentuk transparansi, <strong class="font-semibold text-[#182B35]">koneksi langsung ke API SATUSEHAT Kementerian Kesehatan belum diaktifkan pada versi aplikasi saat ini</strong>. Meski demikian, fondasi data klinis di KLINIKA telah disusun secara rapi per pasien dan kunjungan agar siap dikembangkan ke tahap interoperabilitas HL7 FHIR berikutnya.
                </p>

                <div class="mt-8 space-y-4">
                    @foreach ($satusehatPillars as $pillar)
                        <div class="bg-white border border-[#DDE8E8] rounded-[12px] p-5 flex items-start gap-4">
                            <span class="w-8 h-8 rounded-lg bg-[#E8F5F3] text-[#087F83] font-semibold text-xs flex items-center justify-center shrink-0 mt-0.5">
                                0{{ $loop->iteration }}
                            </span>
                            <div>
                                <h3 class="text-[15px] font-semibold text-[#182B35]">{{ $pillar['title'] }}</h3>
                                <p class="mt-1 text-sm text-[#627680] leading-[1.6]">{{ $pillar['description'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Right Column: Honest Readiness Architecture Card --}}
            <div data-reveal class="lg:col-span-5">
                <div class="bg-white border border-[#CAE3DE] rounded-[16px] p-6 sm:p-7 shadow-[0_10px_30px_rgba(8,127,131,0.06)]">
                    <div class="flex items-center justify-between gap-3 pb-4 border-b border-[#DDE8E8]">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.06em] text-[#087F83]">Peta Kesiapan Interoperabilitas</p>
                            <p class="text-sm font-semibold text-[#182B35] mt-0.5">Alur Data KLINIKA &rarr; SATUSEHAT</p>
                        </div>
                        <span class="w-10 h-10 rounded-xl bg-[#E8F5F3] text-[#087F83] flex items-center justify-center shrink-0" aria-hidden="true">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                            </svg>
                        </span>
                    </div>

                    <div class="mt-5 space-y-3.5">
                        {{-- Stage 1: Ready --}}
                        <div class="rounded-xl border border-[#CAE3DE] bg-[#E8F5F3]/60 p-4">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-xs font-semibold text-[#182B35]">1. Data Pasien &amp; Kunjungan</span>
                                <span class="px-2 py-0.5 rounded bg-white text-[#087F83] text-[10px] font-semibold border border-[#CAE3DE]">Tersedia di Aplikasi</span>
                            </div>
                            <p class="mt-1.5 text-xs text-[#627680] leading-relaxed">
                                Registrasi pasien, nomor RM, poli tujuan, dan antrean kunjungan tercatat terstruktur.
                            </p>
                        </div>

                        <div class="flex justify-center text-[#087F83]/60" aria-hidden="true">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3"/>
                            </svg>
                        </div>

                        {{-- Stage 2: Ready --}}
                        <div class="rounded-xl border border-[#CAE3DE] bg-[#E8F5F3]/60 p-4">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-xs font-semibold text-[#182B35]">2. Rekam Medis &amp; Resep Terstruktur</span>
                                <span class="px-2 py-0.5 rounded bg-white text-[#087F83] text-[10px] font-semibold border border-[#CAE3DE]">Tersedia di Aplikasi</span>
                            </div>
                            <p class="mt-1.5 text-xs text-[#627680] leading-relaxed">
                                Asesmen perawat, catatan SOAP dokter, diagnosis, dan resep terhubung pada satu ID kunjungan.
                            </p>
                        </div>

                        <div class="flex justify-center text-[#96611C]/60" aria-hidden="true">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3"/>
                            </svg>
                        </div>

                        {{-- Stage 3: Planned --}}
                        <div class="rounded-xl border border-dashed border-[#D9A760] bg-[#FFF5E4]/70 p-4">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-xs font-semibold text-[#182B35]">3. Konektor HL7 FHIR &amp; API SATUSEHAT</span>
                                <span class="px-2 py-0.5 rounded bg-white text-[#96611C] text-[10px] font-semibold border border-[#F5D9A8]">Rencana Pengembangan</span>
                            </div>
                            <p class="mt-1.5 text-xs text-[#627680] leading-relaxed">
                                Integrasi OAuth2 &amp; pengiriman resource FHIR ke platform SATUSEHAT Kemenkes akan diaktifkan setelah konfigurasi kredensial fasilitas kesehatan.
                            </p>
                        </div>
                    </div>

                    <p class="mt-5 pt-4 border-t border-[#DDE8E8] text-[11px] text-[#627680] leading-relaxed">
                        Status integrasi dapat diperbarui sewaktu-waktu apabila kredensial dan modul penghubung SATUSEHAT fasilitas kesehatan telah diimplementasikan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
