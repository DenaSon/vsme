@php
    $total = max(1, $this->total);
    $idx   = min($index, $total);
    $pct   = max(0, min(100, $progress));
@endphp
<div class="mt-5 flex items-center gap-3 relative"
     wire:loading.class="opacity-80"
     wire:target="next,back">


    <span class="badge badge-primary text-xs flex items-center gap-1">
        Q {{ min($index, $total) }} / {{ $total }}

        <span class="inline-flex items-center" aria-hidden="true">
            <span wire:loading wire:target="next,back" class="ms-1">
                <svg class="animate-spin h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
            </span>
        </span>
    </span>

    {{-- Progress bar: بر اساس درصد محاسبه‌شده --}}
    <div class="flex-1 relative min-w-0 overflow-hidden pe-10">
        <progress
            class="progress progress-primary w-full h-4 rounded-full"
            value="{{ $progress }}"
            max="100"
            role="progressbar"
            aria-valuemin="0"
            aria-valuemax="100"
            aria-valuenow="{{ $progress }}"
            aria-label="Questionnaire progress">
        </progress>

        {{-- آیکن پرچم انتهای نوار --}}
        <span class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 text-primary">
            <x-heroicon-o-flag class="w-5 h-5 sm:w-6 sm:h-6"/>
        </span>

        {{-- متن پنهان برای اسکرین‌ریدرها --}}
        <span class="sr-only">
            {{ $progress }}% completed (Question {{ min($index, $total) }} of {{ $total }})
        </span>
    </div>
</div>
