@props([
    'hasLoginRoute' => true,
    'hasRegisterRoute' => false,
    'contactInfo' => [],
])

<footer class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="border-t border-[#DDE8E8] py-12 grid grid-cols-1 md:grid-cols-12 gap-10">
            {{-- Brand & App info --}}
            <div class="md:col-span-6">
                <a href="#beranda" class="inline-flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-[#087F83] flex items-center justify-center text-white shrink-0" aria-hidden="true">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                        </svg>
                    </span>
                    <span class="text-2xl font-bold tracking-tight text-[#087F83]">Klinika</span>
                </a>
                <p class="mt-3.5 text-sm sm:text-[15px] text-[#627680] leading-[1.6] max-w-sm">
                    Pelayanan terhubung.<br>Rekam medis tertata.
                </p>
                <p class="mt-4 text-xs text-[#627680] leading-relaxed max-w-md">
                    Sistem Manajemen Klinik &amp; Rekam Medis Elektronik. Kontak administrasi:
                    <a href="mailto:{{ $contactInfo['email'] ?? 'admin@klinika.test' }}" class="font-medium text-[#087F83] hover:underline">{{ $contactInfo['email'] ?? 'admin@klinika.test' }}</a>
                </p>
            </div>

            {{-- Column 1: Kenali Klinika --}}
            <div class="md:col-span-3">
                <p class="text-xs font-semibold tracking-[0.08em] uppercase text-[#627680]">
                    Kenali Klinika
                </p>
                <ul class="mt-4 space-y-2.5 text-sm text-[#182B35]">
                    <li><a href="#beranda" class="hover:text-[#087F83] transition-colors">Beranda</a></li>
                    <li><a href="#fitur" class="hover:text-[#087F83] transition-colors">Fitur Unggulan</a></li>
                    <li><a href="#alur-layanan" class="hover:text-[#087F83] transition-colors">Alur Layanan</a></li>
                    <li><a href="#tentang-kami" class="hover:text-[#087F83] transition-colors">Tentang Kami</a></li>
                    <li><a href="#faq" class="hover:text-[#087F83] transition-colors">FAQ</a></li>
                </ul>
            </div>

            {{-- Column 2: Mulai Terhubung --}}
            <div class="md:col-span-3">
                <p class="text-xs font-semibold tracking-[0.08em] uppercase text-[#627680]">
                    Mulai Terhubung
                </p>
                <ul class="mt-4 space-y-2.5 text-sm text-[#182B35]">
                    <li>
                        <a href="#kontak" class="inline-flex items-center gap-1 hover:text-[#087F83] transition-colors">
                            <span>Hubungi Kami</span>
                            <span aria-hidden="true">&#8599;</span>
                        </a>
                    </li>
                    <li>
                        <a href="#preview-dashboard" class="inline-flex items-center gap-1 hover:text-[#087F83] transition-colors">
                            <span>Preview Dashboard</span>
                            <span aria-hidden="true">&#8599;</span>
                        </a>
                    </li>
                    <li>
                        <a href="#satusehat" class="inline-flex items-center gap-1 hover:text-[#087F83] transition-colors">
                            <span>Kesiapan SATUSEHAT</span>
                            <span aria-hidden="true">&#8599;</span>
                        </a>
                    </li>
                    @auth
                        <li>
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1 font-semibold text-[#087F83] hover:underline">
                                <span>Buka Dashboard</span>
                                <span aria-hidden="true">&#8599;</span>
                            </a>
                        </li>
                    @else
                        @if ($hasLoginRoute)
                            <li>
                                <a href="{{ route('login') }}" class="inline-flex items-center gap-1 font-semibold text-[#087F83] hover:underline">
                                    <span>Masuk ke Klinika</span>
                                    <span aria-hidden="true">&#8599;</span>
                                </a>
                            </li>
                        @endif
                        @if ($hasRegisterRoute)
                            <li>
                                <a href="{{ route('register') }}" class="inline-flex items-center gap-1 hover:text-[#087F83] transition-colors">
                                    <span>Daftar Akun</span>
                                    <span aria-hidden="true">&#8599;</span>
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
            </div>
        </div>

        {{-- Legal & Simulation Notice Divider (exact Figma 351:240–242) --}}
        <div class="border-t border-[#DDE8E8] py-6 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs text-[#627680]">
            <p>&copy; {{ date('Y') }} KLINIKA. Seluruh hak cipta dilindungi.</p>
            <p class="text-[11px] sm:text-right">
                Cuplikan produk menggunakan data simulasi, bukan data pasien nyata.
            </p>
        </div>
    </div>
</footer>
