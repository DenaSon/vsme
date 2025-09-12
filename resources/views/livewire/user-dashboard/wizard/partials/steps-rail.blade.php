@php
    // مقدارهای پایه
    $current = $current ?? 1;

    // چندتا آیتم در ریل نمایش داده شود (بین 8 تا 20 یا 15% کل)
    $visible = $visible
        ?? min( max( (int) round(($this->total ?? 0) * 0.15), 8 ), 20 );

    // اگر total تعریف نشده یا صفر بود، از خطا جلوگیری کن
    $total = (int) ($this->total ?? 0);
    if ($total < 1) { $total = 1; }

    // محاسبه محدوده‌ی پنجره
    $half = intdiv($visible, 2);
    $start = max(1, min($current - $half, $total - $visible + 1));
    $end   = min($total, $start + $visible - 1);
@endphp

<aside class="hidden md:flex relative w-14 shrink-0 justify-start bg-base-100 sticky top-24 h-[calc(100dvh-6rem)]">
    <div class="absolute left-1/2 -translate-x-1/2 top-3 bottom-3 w-px bg-base-300"></div>

    <ol class="mt-1 flex flex-col items-center gap-6 w-full overflow-hidden">

        {{-- اگر ابتدای لیست بریده شد، 1 و … را نشان بده --}}
        @if ($start > 1)
            <li class="relative z-10">
                <div class="grid place-items-center w-8 h-8 rounded-full border border-base-300 bg-base-100">
                    <span class="text-[11px] text-base-content/60">1</span>
                </div>
            </li>
            <li class="text-xs opacity-50 select-none">…</li>
        @endif

        {{-- پنجره‌ی قابل‌مشاهده --}}
        @for ($i = $start; $i <= $end; $i++)
            @if ($i === $current)
                <li class="relative z-10" aria-current="step" wire:key="rail-active-{{ $i }}">
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
            @else
                <li class="relative z-10" wire:key="rail-{{ $i }}">
                    <div class="grid place-items-center w-8 h-8 rounded-full border border-base-300 bg-base-100">
                        <span class="text-[11px] text-base-content/60">{{ $i }}</span>
                    </div>
                </li>
            @endif
        @endfor

        {{-- اگر انتهای لیست بریده شد، … و N را نشان بده --}}
        @if ($end < $total)
            <li class="text-xs opacity-50 select-none">…</li>
            <li class="relative z-10">
                <div class="grid place-items-center w-8 h-8 rounded-full border border-base-300 bg-base-100">
                    <span class="text-[11px] text-base-content/60">{{ $total }}</span>
                </div>
            </li>
        @endif

    </ol>
</aside>
