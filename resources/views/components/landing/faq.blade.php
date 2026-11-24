@props([
    'faqs' => [],
])

<section id="faq" class="bg-white py-16 sm:py-20 lg:py-24 scroll-mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Exact Figma 351:10 two-column FAQ split --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-start">
            <div data-reveal class="lg:col-span-4 lg:sticky lg:top-28">
                <p class="text-xs font-semibold tracking-[0.08em] uppercase text-[#087F83]">
                    Pertanyaan Umum
                </p>
                <h2 class="mt-3 text-3xl sm:text-[38px] lg:text-[40px] font-semibold text-[#182B35] leading-[1.2] tracking-[-0.02em]">
                    Kenali Klinika<br>lebih dekat.
                </h2>
                <p class="mt-4 text-sm sm:text-base text-[#627680] leading-[1.6]">
                    Beberapa hal yang mungkin ingin Anda ketahui sebelum mulai menggunakan KLINIKA.
                </p>
            </div>

            <div data-reveal class="lg:col-span-8 divide-y divide-[#DDE8E8] border-t border-b border-[#DDE8E8]">
                @foreach ($faqs as $index => $faq)
                    @php
                        $isFirst = $index === 0;
                        $panelId = 'faq-panel-' . $index;
                    @endphp
                    <div class="py-1">
                        <h3>
                            <button type="button"
                                    data-faq-trigger
                                    aria-expanded="{{ $isFirst ? 'true' : 'false' }}"
                                    aria-controls="{{ $panelId }}"
                                    class="w-full py-5 flex items-center justify-between gap-4 text-left cursor-pointer group focus:outline-none focus-visible:ring-2 focus-visible:ring-[#087F83] rounded">
                                <span class="text-base sm:text-[17px] font-semibold text-[#182B35] group-hover:text-[#087F83] transition-colors">
                                    {{ $faq['question'] }}
                                </span>
                                <span class="w-8 h-8 rounded-full bg-[#F3F9F8] text-[#087F83] flex items-center justify-center shrink-0 transition-colors group-hover:bg-[#E8F5F3]" aria-hidden="true">
                                    <svg data-faq-chevron
                                         class="w-4 h-4 transition-transform duration-200 {{ $isFirst ? 'rotate-180' : '' }}"
                                         fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                                    </svg>
                                </span>
                            </button>
                        </h3>
                        <div id="{{ $panelId }}"
                             data-open="{{ $isFirst ? 'true' : 'false' }}"
                             class="landing-accordion-panel">
                            <div>
                                <p class="pb-5 pr-10 text-sm text-[#627680] leading-[1.68]">
                                    {{ $faq['answer'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
