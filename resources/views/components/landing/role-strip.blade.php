@props([
    'teamRoles' => [],
])

<section aria-label="Peran tim klinik" class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-9 sm:py-11 border-b border-[#DDE8E8]">
        <p class="text-center text-[11px] sm:text-xs font-semibold tracking-[0.1em] uppercase text-[#627680]">
            Dirancang untuk seluruh tim klinik
        </p>

        <ul class="mt-5 flex flex-wrap items-center justify-center gap-x-4 sm:gap-x-6 gap-y-2.5 text-sm sm:text-[17px] font-semibold text-[#182B35]">
            @foreach ($teamRoles as $role)
                <li class="inline-flex items-center gap-4 sm:gap-6">
                    <span>{{ $role }}</span>
                    @if (! $loop->last)
                        <span class="text-[#B8CECE] font-normal select-none" aria-hidden="true">/</span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</section>
