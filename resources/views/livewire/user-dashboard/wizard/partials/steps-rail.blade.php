@php
    $current = $current ?? 1;
    $visible = $visible ?? 12;
@endphp

<aside class="hidden md:flex relative w-14 shrink-0 justify-start bg-base-100 sticky top-24 h-[calc(100dvh-6rem)]">
    <div class="absolute left-1/2 -translate-x-1/2 top-3 bottom-3 w-px bg-base-300"></div>
    <ol class="mt-1 flex flex-col items-center gap-7 w-full overflow-hidden">
        @for ($i = 1; $i <= min($this->total, $visible); $i++)
            @if ($i === $current)
                <li class="relative z-10" aria-current="step">
                    <div class="grid place-items-center w-12 h-12 rounded-full border-2 border-primary bg-base-100">

                        <div wire:loading wire:target="next,back">
                            <x-icon name="o-arrow-path" class="w-5 h-5 text-primary animate-spin"/>
                        </div>


                        <div wire:loading.remove wire:target="next,back"
                             class="flex flex-col items-center leading-none">
                            <span class="text-primary text-sm font-semibold">{{ $i }}</span>
                            <span class="text-[10px] text-primary/80">{{ $this->total }}</span>
                        </div>
                    </div>
                    <div class="w-px h-4 bg-primary mx-auto"></div>
                </li>
            @else
                <li class="relative z-10">
                    <div class="grid place-items-center w-8 h-8 rounded-full border border-base-300 bg-base-100">
                        <span class="text-[11px] text-base-content/60">{{ $i }}</span>
                    </div>
                </li>
            @endif
        @endfor
    </ol>

</aside>


