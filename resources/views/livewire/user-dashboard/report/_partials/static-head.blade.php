{{-- ==================== Report Hero (Static Top Section) ==================== --}}
@php
    $companyName = data_get($payload, 'company.name') ?? __('Company');
    $year        = data_get($payload, 'company.year', $report->year) ?? '—';
    $qnTitle     = __('Sustainability Assessment');
    $preparedFor = $companyName;
    $isSubmitted = ($report->status ?? 'draft') !== 'draft';
@endphp

<div class="space-y-4">

    {{-- Breadcrumb --}}
    <nav class="text-sm breadcrumbs text-base-content/60">
        <ul>
            <li><a href="">{{ __('Reports') }}</a></li>
            <li>{{ $qnTitle }}</li>
        </ul>
    </nav>


    <div class="rounded-full px-6 py-4 bg-success/15 text-success font-semibold flex items-center justify-between">
        <span>{{ __('Congratulations on completing your Sustainability Survey!') }} ✓ 🎉</span>
        <span class="text-success/70 text-xl leading-none">✨</span>
    </div>

    {{-- Company pill + actions --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div class="flex items-center gap-3 w-full">
            {{-- Logo/Initial --}}
            <div class="w-12 h-12 rounded-xl bg-base-300 grid place-items-center text-base-content/70 text-lg font-bold shrink-0">
                {{ Str::of($companyName)->trim()->explode(' ')->map(fn($p)=>Str::substr($p,0,1))->take(2)->implode('') }}
            </div>

            <div class="flex-1">
                <div class="font-semibold leading-tight">
                    {{ $companyName }}
                </div>
                <div class="text-xs text-base-content/60">
                    {{ __('Driving sustainability with data-backed reporting.') }}
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" class="btn btn-outline btn-sm">
                <x-icon name="o-swatch" class="w-4 h-4 me-1" /> {{ __('Edit theme') }}
            </button>

            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-primary btn-sm">
                    <x-icon name="o-arrow-down-tray" class="w-4 h-4 me-1" /> {{ __('Download') }}
                    <x-icon name="o-chevron-down" class="w-4 h-4 ms-1" />
                </label>
                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-44">
                    <li><a>{{ __('PDF (Basic)') }}</a></li>
                    <li><a>{{ __('Excel (Basic)') }}</a></li>
                </ul>
            </div>
        </div>
    </div>


    @if($isSubmitted)
        <div class="rounded-2xl bg-success/10 text-success font-medium px-5 py-3 flex items-center justify-between">
            <span>{{ __('Congratulations on completing your Sustainability Survey!') }} ✓ 🎉</span>
        </div>
    @endif

    {{-- Big title --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold">
                {{ __('Sustainability Report for :year', ['year' => $year]) }}
            </h1>
            <div class="text-sm text-base-content/60">
                {{ __('Prepared for :name', ['name' => $preparedFor]) }}
            </div>
        </div>


        <div class="flex gap-2">
            <button class="btn btn-ghost btn-sm" onclick="window.print()">
                <x-icon name="o-printer" class="w-4 h-4 me-1" /> {{ __('Print') }}
            </button>

        </div>
    </div>
</div>
{{-- ==================== /Report Hero ==================== --}}
