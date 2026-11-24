@props([
    'features' => [],
])

<section id="fitur" class="bg-white py-16 sm:py-20 lg:py-24 scroll-mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header (exact Figma 351:6 split alignment) --}}
        <div data-reveal class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-8 items-end">
            <div class="lg:col-span-7">
                <p class="text-xs font-semibold tracking-[0.08em] uppercase text-[#087F83]">
                    Fitur Unggulan
                </p>
                <h2 class="mt-3 text-3xl sm:text-[38px] lg:text-[40px] font-semibold text-[#182B35] leading-[1.2] tracking-[-0.02em]">
                    Satu platform.<br>Seluruh kebutuhan operasional.
                </h2>
            </div>
            <div class="lg:col-span-5 lg:pb-1">
                <p class="text-sm sm:text-base text-[#627680] leading-[1.6]">
                    Dari meja pendaftaran hingga laporan manajemen, setiap bagian klinik bekerja dalam satu konteks data yang sama.
                </p>
            </div>
        </div>

        {{-- 3x2 Feature Grid --}}
        <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
            @foreach ($features as $feature)
                <article data-reveal
                         style="transition-delay: {{ $loop->index * 55 }}ms"
                         class="group rounded-[12px] border p-6 flex flex-col justify-between min-h-[228px] transition-all duration-200 hover:-translate-y-0.5 hover:shadow-[0_10px_28px_rgba(8,127,131,0.08)] {{ $feature['featured'] ? 'bg-[#E8F5F3] border-[#CAE3DE] hover:border-[#087F83]/50' : 'bg-[#FAFCFC] border-[#DDE8E8] hover:border-[#087F83]/45 hover:bg-white' }}">
                    <div>
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-[13px] font-semibold text-[#087F83] tracking-wide">
                                {{ $feature['number'] }}
                            </span>
                            <span class="w-9 h-9 rounded-lg flex items-center justify-center {{ $feature['featured'] ? 'bg-white/80 text-[#087F83]' : 'bg-[#E8F5F3] text-[#087F83]' }}" aria-hidden="true">
                                @switch($feature['icon'])
                                    @case('users')
                                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                                        </svg>
                                        @break
                                    @case('document-text')
                                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                        </svg>
                                        @break
                                    @case('queue-list')
                                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/>
                                        </svg>
                                        @break
                                    @case('beaker')
                                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/>
                                        </svg>
                                        @break
                                    @case('chart-bar')
                                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                                        </svg>
                                        @break
                                    @default
                                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                                        </svg>
                                @endswitch
                            </span>
                        </div>

                        <h3 class="mt-4 text-xl font-semibold text-[#182B35] leading-snug">
                            {{ $feature['title'] }}
                        </h3>
                        <p class="mt-2.5 text-sm text-[#627680] leading-[1.6]">
                            {{ $feature['description'] }}
                        </p>
                    </div>

                    <a href="#preview-dashboard"
                       class="mt-6 inline-flex items-center gap-1.5 text-xs font-semibold text-[#087F83] group-hover:gap-2 transition-all">
                        <span>{{ $feature['highlight'] }}</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25"/>
                        </svg>
                    </a>
                </article>
            @endforeach
        </div>
    </div>
</section>
