@props([
    'journeySteps' => [],
])

<section id="alur-layanan" class="bg-[#103D40] text-white py-16 sm:py-20 lg:py-24 scroll-mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header (exact Figma 351:7 deep-teal split) --}}
        <div data-reveal class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-8 items-end">
            <div class="lg:col-span-6">
                <p class="text-xs font-semibold tracking-[0.08em] uppercase text-[#9FD4CD]">
                    Alur Pelayanan
                </p>
                <h2 class="mt-3 text-3xl sm:text-[38px] lg:text-[40px] font-semibold text-white leading-[1.2] tracking-[-0.02em]">
                    Terhubung di setiap<br>tahap pelayanan.
                </h2>
            </div>
            <div class="lg:col-span-6 lg:pb-1">
                <p class="text-sm sm:text-base text-[#C0D8D7] leading-[1.6] max-w-xl lg:ml-auto">
                    Bukan sekadar kumpulan modul terpisah. KLINIKA menjaga alur kunjungan pasien tetap menyambung dari pendaftaran hingga penyelesaian tagihan.
                </p>
            </div>
        </div>

        {{-- 5-Stage Connected Care Timeline --}}
        <div class="relative mt-14">
            {{-- Horizontal connecting rail on desktop --}}
            <div class="hidden lg:block absolute top-[23px] left-6 right-6 h-px bg-[#3E6566]" aria-hidden="true"></div>

            <ol class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-6 relative">
                @foreach ($journeySteps as $item)
                    <li data-reveal
                        style="transition-delay: {{ $loop->index * 65 }}ms"
                        class="relative flex lg:flex-col items-start gap-4 lg:gap-0">
                        {{-- Mobile vertical rail --}}
                        @if (! $loop->last)
                            <span class="sm:hidden absolute left-[22px] top-[46px] bottom-[-32px] w-px bg-[#3E6566]" aria-hidden="true"></span>
                        @endif

                        <div class="relative z-10 w-[46px] h-[46px] rounded-full bg-[#C8E8E1] text-[#103D40] font-semibold text-base flex items-center justify-center shrink-0 ring-4 ring-[#103D40]">
                            {{ $item['step'] }}
                        </div>

                        <div class="lg:mt-6">
                            <span class="inline-block text-[11px] font-semibold tracking-wide uppercase text-[#9FD4CD]">
                                {{ $item['role'] }}
                            </span>
                            <h3 class="mt-1.5 text-lg sm:text-[19px] font-semibold text-white leading-snug">
                                {{ $item['title'] }}
                            </h3>
                            <p class="mt-2 text-sm text-[#C0D8D7] leading-[1.6]">
                                {{ $item['description'] }}
                            </p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</section>
