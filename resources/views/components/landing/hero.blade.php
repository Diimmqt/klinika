@props([
    'hasLoginRoute' => true,
])

<section id="beranda" class="relative bg-[#F3F9F8] overflow-hidden border-b border-[#DDE8E8]/80">
    {{-- Subtle atmospheric mint highlight --}}
    <div class="pointer-events-none absolute inset-x-0 top-0 h-[420px] bg-[radial-gradient(ellipse_at_top,rgba(8,127,131,0.08),transparent_68%)]" aria-hidden="true"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 sm:pt-14 lg:pt-16 pb-0">
        {{-- Copy block --}}
        <div class="text-center max-w-4xl mx-auto">
            <div class="inline-flex items-center gap-2 bg-[#E1F0ED] text-[#087F83] px-4 py-1.5 rounded-full text-[11px] sm:text-xs font-semibold tracking-[0.08em] uppercase">
                <span class="w-1.5 h-1.5 rounded-full bg-[#087F83]" aria-hidden="true"></span>
                Sistem Manajemen Klinik &amp; RME
            </div>

            <h1 class="mt-6 text-3xl sm:text-5xl lg:text-[54px] font-semibold text-[#182B35] leading-[1.15] tracking-[-0.025em] text-balance">
                Pelayanan Kesehatan Lebih Terintegrasi, Dimulai dari <span class="text-[#087F83]">KLINIKA.</span>
            </h1>

            <p class="mt-5 text-base sm:text-[17px] text-[#627680] leading-[1.6] max-w-2xl mx-auto">
                Satukan pendaftaran pasien, rekam medis elektronik, manajemen antrean, farmasi, dan kasir dalam satu ruang kerja. Bantu tim fokus pada yang paling penting: pelayanan pasien.
            </p>

            {{-- Primary & Secondary CTA --}}
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3.5">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 h-[52px] px-7 rounded-lg bg-[#087F83] hover:bg-[#066669] active:scale-[0.99] text-white text-[15px] font-semibold transition-all shadow-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-[#087F83] focus-visible:ring-offset-2">
                        <span>Buka Dashboard</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                @else
                    @if ($hasLoginRoute)
                        <a href="{{ route('login') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 h-[52px] px-7 rounded-lg bg-[#087F83] hover:bg-[#066669] active:scale-[0.99] text-white text-[15px] font-semibold transition-all shadow-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-[#087F83] focus-visible:ring-offset-2">
                            <span>Masuk ke KLINIKA</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    @endif
                @endauth

                <a href="#fitur"
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 h-[52px] px-7 rounded-lg bg-white border border-[#DDE8E8] hover:border-[#087F83]/45 hover:bg-[#FAFCFC] text-[#182B35] text-[15px] font-semibold transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-[#087F83] focus-visible:ring-offset-2">
                    <span>Jelajahi Fitur</span>
                </a>
            </div>

            {{-- Micro assurance strip --}}
            <div class="mt-6 flex flex-wrap items-center justify-center gap-x-4 gap-y-1.5 text-[13px] text-[#627680]">
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#087F83]/60" aria-hidden="true"></span>
                    Satu alur layanan
                </span>
                <span class="text-[#B8CECE]" aria-hidden="true">&bull;</span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#087F83]/60" aria-hidden="true"></span>
                    Akses sesuai peran
                </span>
                <span class="text-[#B8CECE]" aria-hidden="true">&bull;</span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#087F83]/60" aria-hidden="true"></span>
                    Aktivitas tercatat
                </span>
            </div>
        </div>

        {{-- Figma-aligned Floating Product Preview Card (cropped at bottom edge of Hero) --}}
        <div class="mt-10 sm:mt-12 max-w-[1044px] mx-auto">
            <div class="bg-white border border-b-0 border-[#CCDCDC] rounded-t-[14px] shadow-[0_14px_40px_rgba(10,51,51,0.10)] overflow-hidden">
                {{-- Browser Chrome Toolbar --}}
                <div class="bg-[#F4F8F8] border-b border-[#DFE7EA] px-4 py-2.5 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-1.5 shrink-0" aria-hidden="true">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#9BB6B6]"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-[#B4C9C9]"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-[#CBDADA]"></span>
                    </div>
                    <p class="text-[11px] sm:text-xs font-medium text-[#627680] truncate">
                        Klinika &nbsp;/&nbsp; Ruang kerja dokter
                    </p>
                    <span class="hidden sm:inline-flex items-center px-2 py-0.5 rounded bg-[#E8F5F3] text-[#087F83] text-[10px] font-semibold shrink-0">
                        Ilustrasi • Data Simulasi
                    </span>
                </div>

                {{-- Simulated Workspace Body (matching Figma 351:32) --}}
                <div class="bg-[#F4F7F8] flex min-h-[300px] sm:min-h-[355px]">
                    {{-- Simulated Sidebar --}}
                    <div class="hidden md:flex w-[180px] bg-white border-r border-[#DFE7EA] flex-col justify-between p-4 shrink-0 select-none" aria-hidden="true">
                        <div class="space-y-4">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded bg-[#087F83] text-white text-xs font-bold flex items-center justify-center">+</span>
                                <span class="font-bold text-sm text-[#087F83]">Klinika</span>
                            </div>
                            <div class="text-[10px] text-[#627680] leading-relaxed pb-3 border-b border-[#DFE7EA]">
                                <p class="font-medium text-[#182B35]">Klinik Sehat Sentosa</p>
                                <p>Bandung &bull; KSS-01</p>
                            </div>
                            <div class="space-y-1 text-[11px]">
                                <div class="px-2.5 py-1.5 rounded bg-[#087F83] text-white font-semibold flex items-center gap-1.5">
                                    <span>&rsaquo;</span><span>Dashboard</span>
                                </div>
                                <div class="px-2.5 py-1.5 text-[#627680]">Antrean dokter</div>
                                <div class="px-2.5 py-1.5 text-[#627680]">Pemeriksaan dokter</div>
                                <div class="px-2.5 py-1.5 text-[#627680]">Riwayat RME</div>
                                <div class="px-2.5 py-1.5 text-[#627680]">Jadwal saya</div>
                            </div>
                        </div>
                        <div class="pt-3 border-t border-[#DFE7EA] text-[10px] text-[#627680] space-y-1">
                            <p class="font-semibold">AKSES: Dokter</p>
                            <p>Bantuan &amp; panduan</p>
                        </div>
                    </div>

                    {{-- Simulated Main Area --}}
                    <div class="flex-1 min-w-0 flex flex-col" aria-hidden="true">
                        {{-- Top Utility Bar --}}
                        <div class="bg-white border-b border-[#DFE7EA] px-4 sm:px-6 py-3 flex items-center justify-between gap-4">
                            <span class="text-[11px] text-[#627680] truncate">Contoh Tampilan &bull; Ruang Kerja Klinik</span>
                            <div class="flex items-center gap-3 shrink-0">
                                <div class="inline-flex items-center gap-1.5">
                                    <img src="{{ asset('images/landing/bell.svg') }}" alt="" width="15" height="15" class="w-[15px] h-[15px] shrink-0">
                                    <span class="text-[11px] font-semibold text-[#087F83]">3</span>
                                </div>
                                <div class="text-right leading-tight">
                                    <p class="text-[11px] font-semibold text-[#182B35]">dr. Andi Prasetyo</p>
                                    <p class="text-[10px] text-[#627680]">Dokter</p>
                                </div>
                            </div>
                        </div>

                        {{-- Content Preview --}}
                        <div class="p-4 sm:p-6 space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div>
                                    <p class="text-[10px] text-[#627680]">Beranda / Ruang praktik dr. Andi</p>
                                    <p class="text-base sm:text-lg font-semibold text-[#182B35] mt-0.5">Ruang praktik dr. Andi</p>
                                    <p class="text-[11px] text-[#627680]">Poli Umum &bull; Sesi Pagi 08.00–12.00 &bull; Ruang 01</p>
                                </div>
                                <span class="self-start sm:self-auto inline-flex items-center px-3 py-1.5 rounded bg-[#087F83] text-white text-[11px] font-semibold">
                                    Buka antrean dokter
                                </span>
                            </div>

                            {{-- 4 Metric Mockup Cards --}}
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                                <div class="bg-white border border-[#DFE7EA] rounded-md p-3.5">
                                    <p class="text-[10px] text-[#627680]">Menunggu dokter</p>
                                    <p class="text-xl font-semibold text-[#182B35] mt-1">5</p>
                                    <p class="text-[10px] text-[#627680] mt-0.5">Pemeriksaan awal selesai</p>
                                </div>
                                <div class="bg-white border border-[#DFE7EA] rounded-md p-3.5">
                                    <p class="text-[10px] text-[#627680]">Dalam pemeriksaan</p>
                                    <p class="text-xl font-semibold text-[#182B35] mt-1">1</p>
                                    <p class="text-[10px] text-[#627680] mt-0.5">Kunjungan aktif saat ini</p>
                                </div>
                                <div class="bg-white border border-[#DFE7EA] rounded-md p-3.5">
                                    <p class="text-[10px] text-[#627680]">RME final hari ini</p>
                                    <p class="text-xl font-semibold text-[#182B35] mt-1">18</p>
                                    <p class="text-[10px] text-[#627680] mt-0.5">Terkunci dan tercatat</p>
                                </div>
                                <div class="bg-white border border-[#DFE7EA] rounded-md p-3.5">
                                    <p class="text-[10px] text-[#627680]">Draf RME</p>
                                    <p class="text-xl font-semibold text-[#182B35] mt-1">2</p>
                                    <p class="text-[10px] text-[#627680] mt-0.5">Belum diteruskan ke farmasi</p>
                                </div>
                            </div>

                            {{-- Amber Draft Alert (using downloaded Figma info.svg) --}}
                            <div class="bg-[#FFF5E4] border border-[#F5D9A8]/70 rounded-md px-3.5 py-2.5 flex items-start gap-2.5">
                                <img src="{{ asset('images/landing/info.svg') }}" alt="" width="15" height="15" class="w-[15px] h-[15px] mt-0.5 shrink-0">
                                <div class="text-[#96611C] leading-snug">
                                    <p class="text-[11px] font-semibold">2 draf perlu dituntaskan</p>
                                    <p class="text-[10px] mt-0.5">Resep hanya diteruskan setelah konfirmasi finalisasi RME. Jangan meninggalkan draf tanpa penyerahan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
