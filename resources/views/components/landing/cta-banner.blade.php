@props([
    'hasLoginRoute' => true,
])

<section aria-label="Ajakan mulai menggunakan KLINIKA" class="bg-white pb-16 sm:pb-20 lg:pb-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Exact Figma 351:11 soft-mint rounded callout --}}
        <div data-reveal class="bg-[#E8F5F3] border border-[#CAE3DE]/80 rounded-[20px] px-6 py-12 sm:px-12 sm:py-14 text-center">
            <p class="text-xs font-semibold tracking-[0.08em] uppercase text-[#087F83]">
                Langkah berikutnya untuk klinik Anda
            </p>
            <h2 class="mt-3 text-2xl sm:text-4xl font-semibold text-[#182B35] leading-[1.22] tracking-[-0.02em] max-w-3xl mx-auto">
                Lebih sedikit hambatan.<br>Lebih banyak waktu untuk pasien.
            </h2>
            <p class="mt-4 text-sm sm:text-base text-[#627680] leading-[1.6] max-w-xl mx-auto">
                Kenali bagaimana KLINIKA menyatukan pendaftaran, pelayanan medis, farmasi, dan kasir dalam satu alur kerja tim.
            </p>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3.5">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 h-12 px-7 rounded-lg bg-[#087F83] hover:bg-[#066669] active:scale-[0.99] text-white text-sm font-semibold transition-all shadow-xs focus:outline-none focus-visible:ring-2 focus-visible:ring-[#087F83] focus-visible:ring-offset-2">
                        <span>Buka Dashboard</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                @else
                    @if ($hasLoginRoute)
                        <a href="{{ route('login') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 h-12 px-7 rounded-lg bg-[#087F83] hover:bg-[#066669] active:scale-[0.99] text-white text-sm font-semibold transition-all shadow-xs focus:outline-none focus-visible:ring-2 focus-visible:ring-[#087F83] focus-visible:ring-offset-2">
                            <span>Masuk ke KLINIKA</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    @endif
                @endauth

                <a href="#kontak"
                   class="w-full sm:w-auto inline-flex items-center justify-center h-12 px-6 rounded-lg bg-white border border-[#CAE3DE] hover:border-[#087F83]/45 text-[#182B35] text-sm font-semibold transition-all">
                    Hubungi Kami / Jadwalkan Demo
                </a>
            </div>
        </div>
    </div>
</section>
