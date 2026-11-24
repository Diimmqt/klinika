@props([
    'advantages' => [],
])

<section id="tentang-kami" class="bg-[#F4F8F8] py-16 sm:py-20 lg:py-24 scroll-mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Exact Figma 351:9 header & accent-bar pillar grid --}}
        <div data-reveal class="max-w-3xl">
            <p class="text-xs font-semibold tracking-[0.08em] uppercase text-[#087F83]">
                Tentang Kami &amp; Keunggulan KLINIKA
            </p>
            <h2 class="mt-3 text-3xl sm:text-[38px] font-semibold text-[#182B35] leading-[1.22] tracking-[-0.02em]">
                Informasi terjaga. Tanggung jawab jelas.
            </h2>
            <p class="mt-3.5 text-sm sm:text-base text-[#627680] leading-[1.6]">
                KLINIKA dibangun untuk membantu operasional klinik berjalan tertib melalui ruang kerja yang rapi, terintegrasi, dan terkendali.
            </p>
        </div>

        <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-7">
            @foreach ($advantages as $item)
                <article data-reveal style="transition-delay: {{ $loop->index * 60 }}ms">
                    <div class="w-10 h-[3px] bg-[#087F83] rounded-full" aria-hidden="true"></div>
                    <h3 class="mt-5 text-[19px] font-semibold text-[#182B35] leading-snug">
                        {{ $item['title'] }}
                    </h3>
                    <p class="mt-3 text-sm text-[#627680] leading-[1.65]">
                        {{ $item['description'] }}
                    </p>
                </article>
            @endforeach
        </div>

        <p data-reveal class="mt-12 pt-6 border-t border-[#DDE8E8] text-xs text-[#627680]">
            Pengaturan akses, retensi catatan, dan tata kelola operasional tetap mengikuti kebijakan serta tanggung jawab masing-masing klinik.
        </p>
    </div>
</section>
