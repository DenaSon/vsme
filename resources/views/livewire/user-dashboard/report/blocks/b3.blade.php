{{-- B3 – Energy and greenhouse gas emissions --}}
@php
    // بلوک B3 و هِلپر دسترسی به آیتم‌ها
    $b3 = $block ?? collect(data_get($payload, 'blocks', []))->firstWhere('code', 'b3');
    $q  = fn(string $key) => collect(data_get($b3,'items',[]))->firstWhere('question_key',$key);

    // ===== (29) Energy consumption breakdown (MWh)
    $e = data_get($q('b3.q1'), 'value', []);
    $toNum = function($v) { $v = is_string($v) ? trim($v) : $v; return is_numeric($v) ? (float)$v : 0.0; };
    $fmt   = fn($n) => is_null($n) ? '—' : rtrim(rtrim(number_format((float)$n, 2, '.', ','), '0'), '.');

    $elecRen = $toNum(data_get($e,'electricity_renewable_mwh'));
    $elecNon = $toNum(data_get($e,'electricity_nonrenewable_mwh'));
    $fuelRen = $toNum(data_get($e,'fuel_renewable_mwh'));
    $fuelNon = $toNum(data_get($e,'fuel_nonrenewable_mwh'));

    $elecTot = $elecRen + $elecNon;
    $fuelTot = $fuelRen + $fuelNon;
    $grandRen = $elecRen + $fuelRen;
    $grandNon = $elecNon + $fuelNon;
    $grandTot = $elecTot + $fuelTot;

    // ===== (30) GHG emissions
    $approach    = data_get($q('b3.q2'),'value.choice'); // e.g., control_operational
    $scope1      = $toNum(data_get($q('b3.q3'),'value.scope1_tco2e'));
    $scope2_loc  = $toNum(data_get($q('b3.q4'),'value.scope2_loc_tco2e'));
    $approachLbl = $approach ? ucwords(str_replace('_',' ', $approach)) : null;

    // ===== (31) GHG intensity = (scope1 + scope2_loc) / turnover (EUR) from B1
    $b1   = collect(data_get($payload,'blocks',[]))->firstWhere('code','b1');
    $b1q7 = collect(data_get($b1,'items',[]))->firstWhere('question_key','b1.q7');
    $turnover = $toNum(data_get($b1q7,'value.turnover_eur')); // EUR
    $ghgTotal = $scope1 + $scope2_loc;
    $intensity = ($turnover > 0) ? ($ghgTotal / $turnover) : null; // tCO2e per EUR
@endphp

<section class="card bg-base-100 shadow">
    <div class="card-body space-y-5">

        {{-- Header: Code + Title --}}
        <div>
            <h3 class="card-title">
                {{ strtoupper(data_get($b3,'code','B3')) }}
                <span class="text-base-content/70 font-normal">— {{ data_get($b3,'title','Energy and greenhouse gas emissions') }}</span>
            </h3>
            <div class="text-xs text-base-content/60 mt-1">{{ __('Section B3') }}</div>
        </div>

        {{-- (29) Energy consumption breakdown (MWh) --}}
        <div>
            <div class="text-sm font-semibold mb-2">{{ __('(29) Total energy consumption (MWh) with breakdown') }}</div>
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                    <tr>
                        <th></th>
                        <th class="text-right">{{ __('Renewable') }}</th>
                        <th class="text-right">{{ __('Non-renewable') }}</th>
                        <th class="text-right">{{ __('Total') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="font-medium">{{ __('Electricity (utility billings)') }}</td>
                        <td class="text-right">{{ $fmt($elecRen) }}</td>
                        <td class="text-right">{{ $fmt($elecNon) }}</td>
                        <td class="text-right">{{ $fmt($elecTot) }}</td>
                    </tr>
                    <tr>
                        <td class="font-medium">{{ __('Fuels') }}</td>
                        <td class="text-right">{{ $fmt($fuelRen) }}</td>
                        <td class="text-right">{{ $fmt($fuelNon) }}</td>
                        <td class="text-right">{{ $fmt($fuelTot) }}</td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr class="font-semibold">
                        <td>{{ __('Total') }}</td>
                        <td class="text-right">{{ $fmt($grandRen) }}</td>
                        <td class="text-right">{{ $fmt($grandNon) }}</td>
                        <td class="text-right">{{ $fmt($grandTot) }}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- (30) GHG emissions (Scope 1 & Scope 2 location-based) + Approach --}}
        <div class="grid md:grid-cols-3 gap-4">
            <div class="bg-base-200 rounded-xl p-3">
                <div class="text-xs opacity-70">{{ __('GHG accounting boundary approach') }}</div>
                <div class="font-medium">{{ $approachLbl ?? '—' }}</div>
            </div>
            <div class="bg-base-200 rounded-xl p-3">
                <div class="text-xs opacity-70">{{ __('Scope 1 GHG emissions (tCO2e)') }}</div>
                <div class="font-medium">{{ $fmt($scope1) }}</div>
            </div>
            <div class="bg-base-200 rounded-xl p-3">
                <div class="text-xs opacity-70">{{ __('Scope 2 GHG emissions (location-based, tCO2e)') }}</div>
                <div class="font-medium">{{ $fmt($scope2_loc) }}</div>
            </div>
        </div>

        {{-- (31) GHG intensity --}}
        <div class="bg-base-200 rounded-xl p-3">
            <div class="text-xs opacity-70 mb-1">
                {{ __('(31) GHG intensity = (Scope 1 + Scope 2 location-based) / Turnover (EUR)') }}
            </div>
            <div class="font-medium">
                @if(!is_null($intensity))
                    {{ $fmt($intensity) }} <span class="text-xs opacity-70">{{ __('tCO2e per EUR') }}</span>
                    <span class="ms-2 text-xs opacity-70">({{ __('Total GHG') }}: {{ $fmt($ghgTotal) }}, {{ __('Turnover') }}: {{ $fmt($turnover) }} {{ __('EUR') }})</span>
                @else
                    —
                    <span class="ms-2 text-xs opacity-70">
            {{ __('Missing or zero turnover to calculate intensity.') }}
          </span>
                @endif
            </div>
        </div>

    </div>
</section>
