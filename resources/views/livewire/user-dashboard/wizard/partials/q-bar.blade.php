<div class="mt-5 flex items-center gap-3 relative">

    <span class="badge badge-primary text-xs flex items-center gap-1">
        Q {{ $index }}


        <span wire:loading wire:target="next,back">
            <svg class="animate-spin h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
            </svg>
        </span>
    </span>


    <div class="flex-1 relative min-w-0 overflow-hidden pe-10">
        <progress
            class="progress progress-primary w-full h-4 rounded-full"
            value="{{ $index }}"
            max="{{$this->total}}"
            aria-label="Progress">
        </progress>

        <span class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 text-primary">
            <x-heroicon-o-flag class="w-5 h-5 sm:w-6 sm:h-6"/>
        </span>


    </div>
</div>
