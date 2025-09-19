@php
    $current = max(1, (int) ($current ?? 1));
    $total   = max(1, (int) ($this->total ?? 0));

    // اندازهٔ تکه
    $chunk   = (int) ($chunk ?? 10);     // هر 10 تا یک تکه
    $chunk   = max(3, min($chunk, 30)); // محدودیت منطقی

    $chunkIndex = intdiv($current - 1, $chunk); // 0-based
    $start      = $chunkIndex * $chunk + 1;
    $end        = min($total, $start + $chunk - 1);

    $hasPrevChunk = $start > 1;
    $hasNextChunk = $end < $total;
@endphp

<aside  class="hidden md:flex relative w-14 shrink-0 justify-start bg-base-100 sticky top-24 h-[calc(100dvh-6rem)] mt-10">
    <div class="absolute left-1/2 -translate-x-1/2 top-3 bottom-3 w-px bg-base-300"></div>

    <ol class="mt-1 flex flex-col items-center gap-6 w-full">

        {{-- اگر تکهٔ قبلی وجود دارد --}}
        @if ($hasPrevChunk)
            <li class="text-xs opacity-60 select-none">1…{{ $start - 1 }}</li>
        @endif

        {{-- حلقه اصلی --}}
        @for ($i = $start; $i <= $end; $i++)
            @if ($i === $current)
                {{-- وضعیت جاری --}}
                <li  class="relative z-10" aria-current="step" wire:key="rail-active-{{ $i }}">
                    <div class="grid place-items-center w-12 h-12 rounded-full border-2 border-primary bg-base-100">
                        <div wire:loading wire:target="next,back">
                            <x-icon name="o-arrow-path" class="w-5 h-5 text-primary animate-spin"/>
                        </div>
                        <div wire:loading.remove wire:target="next,back" class="flex flex-col items-center leading-none">
                            <span class="text-primary text-sm font-semibold">{{ $i }}</span>
                            <span class="text-[10px] text-primary/80">{{ $total }}</span>
                        </div>
                    </div>
                    <div class="w-px h-4 bg-primary mx-auto"></div>
                </li>
            @elseif ($i < $current)
                {{-- سوالات قبلی → border سبز نازک --}}
                <li class="relative z-10" wire:key="rail-done-{{ $i }}">
                    <div class="grid place-items-center w-8 h-8 rounded-full border border-success/70 bg-base-100">
                        <span class="text-[11px] text-success/80">{{ $i }}</span>
                    </div>
                </li>
            @else
                {{-- سوالات آینده → حالت عادی --}}
                <li class="relative z-10" wire:key="rail-{{ $i }}">
                    <div class="grid place-items-center w-8 h-8 rounded-full border border-base-300 bg-base-100">
                        <span class="text-[11px] text-base-content/60">{{ $i }}</span>
                    </div>
                </li>
            @endif
        @endfor

        {{-- اگر تکهٔ بعدی وجود دارد --}}
        @if ($hasNextChunk)
            <li class="text-xs opacity-60 select-none">{{ $end }}…{{ $total }}</li>
        @endif

    </ol>
</aside>
