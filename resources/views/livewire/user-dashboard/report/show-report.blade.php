<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">
            {{ __('Report') }} #{{ $report->id }}
            <span class="text-base-content/60 text-sm ms-2">
                ({{ strtoupper($report->mode) }} • {{ $report->module_choice }} • {{ $report->year ?? '—' }})
            </span>
        </h1>


    </div>

    {{-- No snapshot yet --}}
    @if(!$payload)
        <div class="alert alert-info">
            <div>
                <span class="font-medium">{{ __('No snapshot found') }}.</span>
                <span class="ms-1">{{ __('Click the button above to generate the Basic module snapshot.') }}</span>
            </div>
        </div>
        @php return; @endphp
    @endif


    @include('livewire.user-dashboard.report._partials.static-head')



    @foreach(data_get($payload, 'blocks', []) as $block)
        @php
            $code = strtolower(data_get($block,'code'));
            $view = "livewire.user-dashboard.report.blocks.$code";
        @endphp


        @includeIf($view, ['block'=>$block, 'report'=>$report, 'payload'=>$payload])


        @includeUnless(View::exists($view), 'livewire.user-dashboard.report.blocks._default-table', [
            'block'=>$block
        ])
    @endforeach


</div>
