@props([
    'hasLoginRoute' => true,
    'hasRegisterRoute' => false,
])

<header id="landing-navbar"
        class="sticky top-0 z-50 bg-white border-b border-[#DDE8E8] backdrop-blur-md transition-all duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-[76px] lg:h-[88px] flex items-center justify-between gap-4">
            {{-- Brand Logo --}}
            <a href="#beranda"
               class="inline-flex items-center gap-2.5 group focus:outline-none focus-visible:ring-2 focus-visible:ring-[#087F83] focus-visible:ring-offset-2 rounded-lg">
                <span class="w-9 h-9 rounded-lg bg-[#087F83] group-hover:bg-[#066669] transition-colors flex items-center justify-center text-white shadow-xs shrink-0" aria-hidden="true">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                    </svg>
                </span>
                <span class="text-[22px] sm:text-[25px] font-bold tracking-tight text-[#087F83] leading-none">
                    Klinika
                </span>
            </a>

            {{-- Desktop Navigation --}}
            <nav aria-label="Navigasi utama" class="hidden lg:flex items-center gap-8">
                <a href="#beranda" data-nav-link
                   class="text-sm font-semibold text-[#087F83] hover:text-[#087F83] transition-colors py-1 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#087F83] rounded">
                    Beranda
                </a>
                <a href="#fitur" data-nav-link
                   class="text-sm font-semibold text-[#182B35] hover:text-[#087F83] transition-colors py-1 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#087F83] rounded">
                    Fitur
                </a>
                <a href="#alur-layanan" data-nav-link
                   class="text-sm font-semibold text-[#182B35] hover:text-[#087F83] transition-colors py-1 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#087F83] rounded">
                    Alur Layanan
                </a>
                <a href="#tentang-kami" data-nav-link
                   class="text-sm font-semibold text-[#182B35] hover:text-[#087F83] transition-colors py-1 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#087F83] rounded">
                    Tentang Kami
                </a>
                <a href="#faq" data-nav-link
                   class="text-sm font-semibold text-[#182B35] hover:text-[#087F83] transition-colors py-1 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#087F83] rounded">
                    FAQ
                </a>
                <a href="#kontak" data-nav-link
                   class="text-sm font-semibold text-[#182B35] hover:text-[#087F83] transition-colors py-1 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#087F83] rounded">
                    Kontak
                </a>
            </nav>

            {{-- Desktop CTAs --}}
            <div class="hidden lg:flex items-center gap-4">
                @auth
                    <a href="#preview-dashboard"
                       class="text-sm font-semibold text-[#182B35] hover:text-[#087F83] transition-colors px-2 py-2">
                        Preview
                    </a>
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center justify-center gap-2 h-11 px-6 rounded-lg bg-[#087F83] hover:bg-[#066669] active:scale-[0.99] text-white text-sm font-semibold transition-all shadow-xs focus:outline-none focus-visible:ring-2 focus-visible:ring-[#087F83] focus-visible:ring-offset-2">
                        <span>Buka Dashboard</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                @else
                    <a href="#preview-dashboard"
                       class="text-sm font-semibold text-[#182B35] hover:text-[#087F83] transition-colors px-2 py-2">
                        Lihat Preview
                    </a>
                    @if ($hasRegisterRoute)
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center justify-center h-11 px-5 rounded-lg bg-white border border-[#DDE8E8] hover:border-[#087F83]/40 hover:bg-[#F3F9F8] text-[#182B35] text-sm font-semibold transition-all">
                            Daftar
                        </a>
                    @endif
                    @if ($hasLoginRoute)
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center gap-2 h-11 px-6 rounded-lg bg-[#087F83] hover:bg-[#066669] active:scale-[0.99] text-white text-sm font-semibold transition-all shadow-xs focus:outline-none focus-visible:ring-2 focus-visible:ring-[#087F83] focus-visible:ring-offset-2">
                            <span>Masuk</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    @endif
                @endauth
            </div>

            {{-- Mobile Menu Button --}}
            <button type="button"
                    id="landing-mobile-toggle"
                    aria-controls="landing-mobile-menu"
                    aria-expanded="false"
                    aria-label="Buka menu navigasi"
                    class="lg:hidden inline-flex items-center justify-center w-11 h-11 rounded-lg border border-[#DDE8E8] bg-white text-[#182B35] hover:bg-[#F3F9F8] transition-colors cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-[#087F83]">
                <svg id="landing-icon-open" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg id="landing-icon-close" class="w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Dropdown Drawer --}}
    <div id="landing-mobile-menu"
         class="hidden lg:hidden border-t border-[#DDE8E8] bg-white px-4 pt-3 pb-5 shadow-lg">
        <nav aria-label="Navigasi seluler" class="flex flex-col space-y-1">
            <a href="#beranda" class="px-3 py-2.5 rounded-lg text-sm font-semibold text-[#182B35] hover:bg-[#F3F9F8] hover:text-[#087F83] transition-colors">Beranda</a>
            <a href="#fitur" class="px-3 py-2.5 rounded-lg text-sm font-semibold text-[#182B35] hover:bg-[#F3F9F8] hover:text-[#087F83] transition-colors">Fitur</a>
            <a href="#alur-layanan" class="px-3 py-2.5 rounded-lg text-sm font-semibold text-[#182B35] hover:bg-[#F3F9F8] hover:text-[#087F83] transition-colors">Alur Layanan</a>
            <a href="#preview-dashboard" class="px-3 py-2.5 rounded-lg text-sm font-semibold text-[#182B35] hover:bg-[#F3F9F8] hover:text-[#087F83] transition-colors">Preview Dashboard</a>
            <a href="#tentang-kami" class="px-3 py-2.5 rounded-lg text-sm font-semibold text-[#182B35] hover:bg-[#F3F9F8] hover:text-[#087F83] transition-colors">Tentang Kami</a>
            <a href="#faq" class="px-3 py-2.5 rounded-lg text-sm font-semibold text-[#182B35] hover:bg-[#F3F9F8] hover:text-[#087F83] transition-colors">FAQ</a>
            <a href="#kontak" class="px-3 py-2.5 rounded-lg text-sm font-semibold text-[#182B35] hover:bg-[#F3F9F8] hover:text-[#087F83] transition-colors">Kontak</a>
        </nav>

        <div class="mt-4 pt-4 border-t border-[#DDE8E8] flex flex-col gap-2.5">
            @auth
                <a href="{{ route('dashboard') }}"
                   class="w-full inline-flex items-center justify-center gap-2 h-11 px-5 rounded-lg bg-[#087F83] hover:bg-[#066669] text-white text-sm font-semibold transition-colors">
                    <span>Buka Dashboard</span>
                </a>
            @else
                @if ($hasLoginRoute)
                    <a href="{{ route('login') }}"
                       class="w-full inline-flex items-center justify-center gap-2 h-11 px-5 rounded-lg bg-[#087F83] hover:bg-[#066669] text-white text-sm font-semibold transition-colors">
                        <span>Masuk ke KLINIKA</span>
                    </a>
                @endif
                @if ($hasRegisterRoute)
                    <a href="{{ route('register') }}"
                       class="w-full inline-flex items-center justify-center h-11 px-5 rounded-lg bg-white border border-[#DDE8E8] text-[#182B35] text-sm font-semibold">
                        Daftar
                    </a>
                @endif
            @endauth
        </div>
    </div>
</header>
