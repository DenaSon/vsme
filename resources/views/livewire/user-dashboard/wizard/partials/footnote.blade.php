@php
    $ho = data_get($this->currentQuestion, 'help_official');
    $hf = data_get($this->currentQuestion, 'help_friendly');
@endphp

@if($ho || $hf)
    <div class="mt-8 pt-6 border-t border-base-300 w-full clear-both">
        @if($ho)
            <div class="alert alert-soft items-start mb-3">
                <x-heroicon-o-academic-cap class="w-5 h-5 shrink-0 mt-0.5"/>
                <div class="text-sm leading-relaxed">
                    {!! nl2br(e($ho)) !!}
                </div>
            </div>
        @endif

        @if($hf)
            <div x-data="{open:false}" class="rounded-xl p-3">
                <button type="button" class="btn btn-ghost btn-sm gap-2" @click="open = !open">
                    <x-heroicon-o-light-bulb class="w-5 h-5"/>
                    <span>{{ __('Tips') }}</span>
                </button>
                <div x-show="open" x-transition class="mt-2 text-sm text-base-content/80 leading-relaxed">
                    {!! nl2br(e($hf)) !!}
                </div>
            </div>
        @endif
    </div>
@endif
