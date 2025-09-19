{{-- B2 – Practices, policies and future initiatives (Section) --}}
@php
    // بلوک B2 و هِلپر دسترسی به آیتم‌ها
    $b2   = $block ?? collect(data_get($payload,'blocks',[]))->firstWhere('code','b2');
    $q    = fn(string $key) => collect(data_get($b2,'items',[]))->firstWhere('question_key',$key);

    // استخراج مقادیر چهار محور
    $practices   = data_get($q('b2.q1'),'value.choice'); // yes|no
    $pr_desc     = data_get($q('b2.q1'),'value.desc');

    $policies    = data_get($q('b2.q2'),'value.choice'); // yes|no
    $po_desc     = data_get($q('b2.q2'),'value.desc');
    // اگر بعداً فیلدی مثل public_available بیاد، اینجا بخون:
    // $pol_public  = data_get($q('b2.q2'),'value.public_available');

    $initiatives = data_get($q('b2.q3'),'value.choice'); // yes|no
    $in_desc     = data_get($q('b2.q3'),'value.desc');

    $targets     = data_get($q('b2.q4'),'value.choice'); // yes|no
    $ta_desc     = data_get($q('b2.q4'),'value.desc');

    $yesBadge = fn($v) => $v === 'yes'
        ? '<span class="badge badge-success">'.__('Yes').'</span>'
        : ($v === 'no' ? '<span class="badge badge-ghost">'.__('No').'</span>' : '<span class="opacity-60">—</span>');

    $show = fn($v) => (is_null($v) || $v==='') ? '—' : e($v);
@endphp

<section class="card bg-base-100 shadow">
    <div class="card-body space-y-5">

        {{-- Header: Code + Title --}}
        <div>
            <h3 class="card-title">
                {{ strtoupper(data_get($b2,'code','B2')) }}
                <span class="text-base-content/70 font-normal">— {{ data_get($b2,'title','Practices, policies and future initiatives') }}</span>
            </h3>
            <div class="text-xs text-base-content/60 mt-1">{{ __('Section B2') }}</div>
        </div>

        {{-- چهار محور اصلی --}}
        <div class="grid md:grid-cols-2 gap-4">
            <div class="bg-base-200 rounded-xl p-3">
                <div class="text-xs opacity-70 mb-1">{{ __('(a) Practices') }}</div>
                <div class="font-medium">{!! $yesBadge($practices) !!}</div>
                @if($pr_desc)
                    <div class="mt-2 text-xs opacity-70">{{ __('Details') }}</div>
                    <div class="text-sm">{{ $show($pr_desc) }}</div>
                @endif
            </div>

            <div class="bg-base-200 rounded-xl p-3">
                <div class="text-xs opacity-70 mb-1">{{ __('(b) Policies on sustainability issues') }}</div>
                <div class="font-medium">{!! $yesBadge($policies) !!}</div>
                @if($po_desc)
                    <div class="mt-2 text-xs opacity-70">{{ __('Details') }}</div>
                    <div class="text-sm">{{ $show($po_desc) }}</div>
                @endif
                {{-- اگر فیلد public_available اضافه شد، اینجا badge نشان بده --}}
                {{-- @if(!is_null($pol_public)) <div class="mt-2 text-xs">{{ __('Publicly available') }}: {!! $yesBadge($pol_public ? 'yes':'no') !!}</div> @endif --}}
            </div>

            <div class="bg-base-200 rounded-xl p-3">
                <div class="text-xs opacity-70 mb-1">{{ __('(c) Future initiatives / forward-looking plans') }}</div>
                <div class="font-medium">{!! $yesBadge($initiatives) !!}</div>
                @if($in_desc)
                    <div class="mt-2 text-xs opacity-70">{{ __('Details') }}</div>
                    <div class="text-sm">{{ $show($in_desc) }}</div>
                @endif
            </div>

            <div class="bg-base-200 rounded-xl p-3">
                <div class="text-xs opacity-70 mb-1">{{ __('(d) Targets to monitor implementation and progress') }}</div>
                <div class="font-medium">{!! $yesBadge($targets) !!}</div>
                @if($ta_desc)
                    <div class="mt-2 text-xs opacity-70">{{ __('Details') }}</div>
                    <div class="text-sm">{{ $show($ta_desc) }}</div>
                @endif
            </div>
        </div>

    </div>
</section>

