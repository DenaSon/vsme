@php
    $fields = collect($q['options'] ?? [])
        ->filter(fn($opt) => ($opt['kind'] ?? 'field') === 'field')
        ->values();
@endphp

{{-- کل فرم را به value بایند می‌کنیم تا بتوانیم نمایش شرطی داشته باشیم --}}
<div class="mt-6 grid gap-3"
     x-data="{ form: @entangle('value').live }">

    @foreach($fields as $i => $f)
        @php
            $fkey        = $f['key'] ?? "f_$i";
            $label       = $f['label'] ?? ucfirst(str_replace('_',' ', $fkey));
            $extra       = $f['extra'] ?? [];
            $type        = $extra['type'] ?? 'number';     // number | text | select
            $placeholder = $extra['placeholder'] ?? '';
            $unit        = $extra['unit'] ?? ($extra['suffix'] ?? null); // سازگاری با قبلی
            $step        = $extra['step'] ?? ($type === 'number' ? 'any' : null);
            $min         = $extra['min']  ?? null;
            $max         = $extra['max']  ?? null;
            $choices     = $extra['choices'] ?? [];        // برای select
            $disabled    = (bool)($extra['disabled'] ?? false);

            // نمایش شرطی ساده: اگر این فیلد فقط وقتی نشان داده شود که has_high_stress = 'yes'
            // در MVP شما فقط برای withdrawal_high_stress_l نیاز داریم:
            $visibleWhen = $extra['visible_if'] ?? null; // مثال: ['key' => 'has_high_stress', 'eq' => 'yes']
            $xShow = null;
            if (is_array($visibleWhen) && ($visibleWhen['key'] ?? null)) {
                $vk = $visibleWhen['key'];
                $vv = $visibleWhen['eq'] ?? null;
                // توجه: این شرط ساده است؛ برای حالات پیچیده‌تر می‌توانید تعمیم دهید
                $xShow = "form?.$vk === '".e($vv)."'";
            }
        @endphp

        <div class="form-control"
             wire:key="mi-{{ $q['key'] ?? 'q' }}-{{ $fkey }}"
             @if($xShow) x-show="{{ $xShow }}" x-collapse x-cloak @endif
        >
            <label class="label pb-1">
                <span class="label-text text-xs font-medium text-base-content/70">
                    {{ $label }}
                </span>
            </label>

            {{-- نوع number/text --}}
            @if(in_array($type, ['number','text'], true))
                <div class="relative">
                    <input
                        type="{{ $type === 'number' ? 'number' : 'text' }}"
                        class="input input-bordered w-full pr-3 input-neutral inline border-primary"
                        placeholder="{{ $placeholder }}"
                        wire:model.defer="value.{{ $fkey }}"
                        @if($step) step="{{ $step }}" @endif
                        @if(!is_null($min)) min="{{ $min }}" @endif
                        @if(!is_null($max)) max="{{ $max }}" @endif
                        @disabled($disabled)
                        aria-label="{{ $label }}"
                    />
                    @if($unit)
                        <div class="absolute inset-y-0 right-0 flex items-center">
                            <span class="px-3 text-sm text-base-content/70">{{ $unit }}</span>
                        </div>
                    @endif
                </div>

                {{-- نوع select (مثلاً yes/no) --}}
            @elseif($type === 'select')
                <select
                    class="select select-bordered w-full"
                    wire:model.defer="value.{{ $fkey }}"
                    @disabled($disabled)
                    aria-label="{{ $label }}"
                >
                    <option value="">{{ $extra['placeholder'] ?? '— Select —' }}</option>
                    @foreach($choices as $opt)
                        <option value="{{ $opt['value'] ?? '' }}">
                            {{ $opt['label'] ?? ($opt['value'] ?? '') }}
                        </option>
                    @endforeach
                </select>
            @endif

            {{-- نمایش خطا --}}
            @error("answers." . ($q['key'] ?? '') . ".$fkey")
            <span class="text-error text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>
    @endforeach
</div>
