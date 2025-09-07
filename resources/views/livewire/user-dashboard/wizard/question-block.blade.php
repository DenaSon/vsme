<div>
    @php
        $ho = $q['help_official'] ?? null;
        $hf = $q['help_friendly'] ?? null;
    @endphp
    @if(!$q)
        <div class="alert alert-warning mt-6">{{__("No question loaded.")}}</div>
    @else

        {{-- Header --}}
        <div class="mt-6 flex items-start justify-between gap-3">
            <div>
                <p class="text-sm text-base-content/70">
                    @if($q['key'] =='b1.q1')

                    @else
                        {{__('ui.question')}} {{ $q['number'] ?? '-' }} / {{ $this->total ?? '-' }}
                    @endif

                </p>
                <h1 class="text-2xl md:text-3xl font-extrabold leading-tight mt-1">
                    {{ $q['title'] ?? 'Untitled question' }}
                </h1>
            </div>

            <button class="btn btn-ghost btn-circle" aria-label="Read out loud">
                <x-heroicon-o-speaker-wave class="w-5 h-5"/>
            </button>
        </div>


        @includeIf("livewire.user-dashboard.wizard.partials.inputs.{$q['type']}", [
            'q'      => $q,
            'value'  => $value,
            'error' => $error ?? null,
            //'reportId' => $report->id,
        ])
    @endif


</div>
