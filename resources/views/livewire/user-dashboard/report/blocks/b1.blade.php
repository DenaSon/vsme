{{-- B1 – Basis for preparation (Section) --}}
@php
    // بلوک B1 و هِلپر دسترسی به آیتم‌ها
    $b1   = $block ?? collect(data_get($payload,'blocks',[]))->firstWhere('code','b1');
    $item = function(string $qKey) use ($b1) {
        return collect(data_get($b1,'items',[]))->firstWhere('question_key',$qKey);
    };


    $modChoice     = strtoupper((string) data_get($item('b1.q1'), 'value.choice'));        // A|B
    $reportMode    = strtolower((string) data_get($item('b1.q2'), 'value.choice'));        // individual|consolidated
    $companies     = data_get($item('b1.q3'), 'value', []);                                 // array
    $legalForm     = data_get($item('b1.q4'), 'value.choice');                              // string
    $balanceSheet  = data_get($item('b1.q6'), 'value.balance_sheet_eur');                   // string/number
    $turnover      = data_get($item('b1.q7'), 'value.turnover_eur');                        // string/number
    $employees     = data_get($item('b1.q8'), 'value.employees');                           // string/number


    $show = fn($v) => (is_null($v) || $v==='') ? '—' : $v;
@endphp

<section class="card bg-base-100 shadow">
    <div class="card-body space-y-5">

        {{-- Header: Code + Title --}}
        <div>
            <h3 class="card-title">
                {{ strtoupper(data_get($b1,'code','B1')) }}
                <span class="text-base-content/70 font-normal">— {{ data_get($b1,'title','Basis for preparation') }}</span>
            </h3>
            <div class="text-xs text-base-content/60 mt-1">{{ __('Section B1') }}</div>
        </div>

        {{-- (a) Module selection & (c) Report basis --}}
        <div class="grid md:grid-cols-2 gap-4">
            <div class="bg-base-200 rounded-xl p-3">
                <div class="text-xs opacity-70 mb-1">{{ __('(a) Module selection') }}</div>
                @if($modChoice === 'B')
                    <span class="badge badge-primary">OPTION B — {{ __('Basic + Comprehensive') }}</span>
                @else
                    <span class="badge badge-outline">OPTION A — {{ __('Basic only') }}</span>
                @endif
            </div>

            <div class="bg-base-200 rounded-xl p-3">
                <div class="text-xs opacity-70 mb-1">{{ __('(c) Report basis') }}</div>
                @if($reportMode === 'consolidated')
                    <span class="badge badge-info">{{ __('Consolidated (group)') }}</span>
                    <span class="text-xs opacity-70 ms-2">{{ __('Includes subsidiaries') }}</span>
                @else
                    <span class="badge badge-ghost">{{ __('Individual (single entity)') }}</span>
                @endif
            </div>
        </div>

        {{-- (d) Reporting companies / subsidiaries list (if consolidated or present) --}}
        @if($reportMode === 'consolidated' || (is_array($companies) && count($companies)))
            <div>
                <div class="text-sm font-semibold mb-2">
                    {{ __('(d) Reporting company/companies') }}
                </div>
                @if(is_array($companies) && count($companies))
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Country') }}</th>
                                <th>{{ __('City') }}</th>
                                <th>{{ __('NACE') }}</th>
                                <th>{{ __('Geolocation') }}</th>
                                <th>{{ __('Address') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($companies as $c)
                                <tr>
                                    <td>{{ $show(data_get($c,'name')) }}</td>
                                    <td>{{ $show(data_get($c,'country')) }}</td>
                                    <td>{{ $show(data_get($c,'city')) }}</td>
                                    <td>{{ $show(data_get($c,'nace')) }}</td>
                                    <td>{{ $show(data_get($c,'geolocation')) }}</td>
                                    <td>{{ $show(data_get($c,'street_address')) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-sm opacity-60">—</div>
                @endif
            </div>
        @endif

        {{-- (e) Company information tiles --}}
        <div>
            <div class="text-sm font-semibold mb-2">{{ __('(e) Company information') }}</div>
            <div class="grid md:grid-cols-3 gap-4">
                <div class="bg-base-200 rounded-xl p-3">
                    <div class="text-xs opacity-70">{{ __('Legal form') }}</div>
                    <div class="font-medium">{{ $show($legalForm) }}</div>
                </div>
                <div class="bg-base-200 rounded-xl p-3">
                    <div class="text-xs opacity-70">{{ __('Size of the balance sheet (EUR)') }}</div>
                    <div class="font-medium">{{ $show($balanceSheet) }}</div>
                </div>
                <div class="bg-base-200 rounded-xl p-3">
                    <div class="text-xs opacity-70">{{ __('Turnover (EUR)') }}</div>
                    <div class="font-medium">{{ $show($turnover) }}</div>
                </div>
                <div class="bg-base-200 rounded-xl p-3 md:col-span-1">
                    <div class="text-xs opacity-70">{{ __('Employees (HC/FTE)') }}</div>
                    <div class="font-medium">{{ $show($employees) }}</div>
                </div>
                {{-- جای آیتم‌های (e.vi) و (e.vii) اگر در B1 فعلی‌ات باشند، بعداً اضافه می‌کنیم --}}
            </div>
        </div>

    </div>
</section>
