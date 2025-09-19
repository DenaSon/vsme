@php
    $name       = $q['key'] ?? 'option';
    $showBadge  = data_get($q, 'meta.ui.show_evidence_badge', false);
    $reportId   = $reportId ?? null;
    $compact    = data_get($q, 'meta.ui.compact', true);
    $wireModel  = $q['key'] === 'b1.q1' ? 'wire:model.live' : 'wire:model';
@endphp

<div class="mt-6 grid grid-cols-1 gap-3"
     x-data="{ sel: @entangle('value.choice').live }"
     role="radiogroup"
     aria-label="{{ $q['title'] ?? 'options' }}">

    @foreach(($q['options'] ?? []) as $i => $opt)
        @php
            $id         = "{$name}-{$i}";
            $val        = $opt['value'] ?? '';
            $label      = $opt['label'] ?? $val;
            $hint       = data_get($opt, 'extra.hint');
            $metaChips  = (array) data_get($opt, 'extra.chips', []);
            $disabled   = (bool) data_get($opt, 'extra.disabled', false);

            // Evidence uploader config
            $uploaderCfg   = data_get($opt, 'extra.uploader', []);
            $needsEvidence = (bool) data_get($opt, 'extra.requires_evidence', false)
                             && (bool) data_get($uploaderCfg, 'enabled', false);

            $upLabel   = data_get($uploaderCfg, 'label.'.app()->getLocale())
                      ?? data_get($uploaderCfg, 'label.en')
                      ?? __('Attach files');
            $maxFiles  = data_get($uploaderCfg, 'max_files', 5);
            $maxSizeMb = data_get($uploaderCfg, 'max_size_mb', 10);
            $mimes     = (array) data_get($uploaderCfg, 'mimes', []);
            $accept    = $mimes
                ? implode(',', array_map(fn($m) =>
                      $m === 'pdf'  ? 'application/pdf'
                    : ($m === 'jpg' || $m === 'jpeg' ? 'image/jpeg'
                    : ($m === 'png' ? 'image/png' : ".{$m}")), $mimes))
                : null;
            $pathPat   = data_get($uploaderCfg, 'path_pattern', 'reports/{report_id}/'.$name);

            // Dynamic inner fields for the selected option
            $extraFields = (array) data_get($opt, 'extra.fields', []);
        @endphp

        <label class="relative block w-full select-none {{ $disabled ? 'opacity-60 cursor-not-allowed' : 'cursor-pointer' }}"
               for="{{ $id }}">

            <input id="{{ $id }}" type="radio"
                   class="peer sr-only"
                   name="{{ $name }}" value="{{ $val }}"
                   @disabled($disabled)
                   wire:key="rc-{{ $name }}-{{ $i }}"
            {{$wireModel}}="value.choice" />

            <div class="relative overflow-hidden rounded-2xl border border-base-300 bg-base-100
                        transition-all duration-200 hover:shadow-sm hover:-translate-y-[1px]
                        focus-within:ring focus-within:ring-primary/25 focus-within:ring-offset-2 focus-within:ring-offset-base-100
                        {{ $compact ? 'px-4 py-3' : 'px-5 py-4' }}
                        peer-checked:border-1 peer-checked:border-primary/80
                        peer-checked:shadow-md peer-checked:shadow-primary/10 peer-checked:bg-primary/5">

                <span class="absolute left-0 top-0 h-full w-1 bg-base-300 transition-all duration-200
                             peer-checked:w-1.5 peer-checked:bg-primary"></span>

                <div class="pointer-events-none absolute inset-0 opacity-0 transition-opacity duration-200
                            peer-checked:opacity-100 bg-gradient-to-r from-primary/5 via-primary/3 to-transparent"></div>

                <div class="pointer-events-none absolute inset-0 opacity-0 transition-opacity duration-200
                            peer-checked:opacity-100
                            [mask-image:radial-gradient(black,transparent_70%)]
                            [background-image:radial-gradient(currentColor_1px,transparent_1px)]
                            text-primary/20 [background-size:10px_10px]"></div>

                <svg class="pointer-events-none absolute top-2.5 right-2.5 h-5 w-5 scale-75 opacity-0 transition-all duration-200
                            peer-checked:opacity-100 peer-checked:scale-100"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M20 6L9 17l-5-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

                <div class="relative z-10 flex flex-col gap-2">
                    <div class="flex items-center justify-between gap-3">
                        <div class="min-w-0 flex items-center gap-2">
                            <span class="shrink-0 inline-flex h-4 w-4 items-center justify-center rounded-full border border-base-300 transition-colors
                                         peer-checked:border-primary">
                                <span class="h-2.5 w-2.5 rounded-full bg-transparent transition-colors
                                             peer-checked:bg-primary"></span>
                            </span>

                            <span class="truncate font-medium
                                {{ $compact ? 'text-sm' : 'text-base md:text-lg' }}
                                peer-checked:text-primary">
                                {{ $label }}
                            </span>
                        </div>

                        <div class="flex items-center gap-1.5 shrink-0">
                            @if($showBadge && $needsEvidence)
{{--                                <span class="badge badge-soft badge-sm peer-checked:badge-primary">--}}
{{--                                    {{ __('Evidence') }}--}}
{{--                                </span>--}}
                            @endif

                            @foreach($metaChips as $chip)
                                <span class="badge badge-ghost badge-sm peer-checked:badge-outline peer-checked:text-primary">
                                    {{ $chip }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    @if($hint)
                        <p class="text-xs text-base-content/70 leading-relaxed peer-checked:text-base-content/80">
                            {!! nl2br(e($hint)) !!}
                        </p>
                    @endif

                    {{-- Inner fields shown only when this option is selected --}}
                    <div x-show="sel === '{{ $val }}'" x-collapse x-cloak class="mt-1 space-y-3 {{ $compact ? 'text-sm' : '' }}">
                        @foreach($extraFields as $fld)
                            @php
                                $fKey    = $fld['key'] ?? null;
                                if (!$fKey) continue;
                                $fType   = $fld['type'] ?? 'text';
                                $fLabel  = data_get($fld, 'label.'.app()->getLocale())
                                        ?? data_get($fld, 'label.en')
                                        ?? \Illuminate\Support\Str::headline($fKey);
                                $ph      = data_get($fld, 'placeholder.'.app()->getLocale())
                                        ?? data_get($fld, 'placeholder.en')
                                        ?? '';
                                $min     = data_get($fld, 'min');
                                $max     = data_get($fld, 'max');
                                $step    = data_get($fld, 'step', 'any');
                                $rows    = data_get($fld, 'rows', $compact ? 2 : 3);
                                $maxlength = data_get($fld, 'maxlength');
                                $suffix  = data_get($fld, 'suffix');
                                $choices = (array) data_get($fld, 'choices', []);
                                $readonly= (bool) data_get($fld, 'readonly', false);
                                $disabledF = (bool) data_get($fld, 'disabled', false);
                            @endphp

                            @if($fType === 'textarea')
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-medium text-base-content/70">{{ $fLabel }}</label>
                                    <textarea
                                        class="textarea textarea-bordered w-full {{ $compact ? 'textarea-sm' : 'textarea-md' }}"
                                        rows="{{ $rows }}"
                                        placeholder="{{ $ph }}"
                                        @if($maxlength) maxlength="{{ $maxlength }}" @endif
                                        @if($readonly) readonly @endif
                                        @disabled($disabledF)
                                        wire:model.defer="value.{{ $fKey }}"></textarea>
                                    @error("value.$fKey")
                                    <span class="text-xs text-error mt-0.5">{{ $message }}</span>
                                    @enderror
                                </div>

                            @elseif($fType === 'number')
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-medium text-base-content/70">{{ $fLabel }}</label>
                                    <div class="join w-full">
                                        <input type="number"
                                               class="input input-bordered join-item w-full"
                                               placeholder="{{ $ph }}"
                                               @if(!is_null($min)) min="{{ $min }}" @endif
                                               @if(!is_null($max)) max="{{ $max }}" @endif
                                               step="{{ $step }}"
                                               @if($readonly) readonly @endif
                                               @disabled($disabledF)
                                               wire:model.defer="value.{{ $fKey }}" />
                                        @if($suffix)
                                            <span class="join-item inline-flex items-center px-3 text-sm text-base-content/70
                                                         border border-l-0 border-base-300 rounded-r-lg bg-base-200">
                                                {{ $suffix }}
                                            </span>
                                        @endif
                                    </div>
                                    @error("value.$fKey")
                                    <span class="text-xs text-error mt-0.5">{{ $message }}</span>
                                    @enderror
                                </div>

                            @elseif($fType === 'select')
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-medium text-base-content/70">{{ $fLabel }}</label>
                                    <select class="select select-bordered w-full"
                                            aria-label="{{ $fLabel }}"
                                            @if($readonly) readonly @endif
                                            @disabled($disabledF)
                                            wire:model.defer="value.{{ $fKey }}">
                                        <option value="">{{ $ph ?: '— Select —' }}</option>
                                        @foreach($choices as $c)
                                            <option value="{{ $c['value'] ?? '' }}">{{ $c['label'] ?? ($c['value'] ?? '') }}</option>
                                        @endforeach
                                    </select>
                                    @error("value.$fKey")
                                    <span class="text-xs text-error mt-0.5">{{ $message }}</span>
                                    @enderror
                                </div>

                            @else
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-medium text-base-content/70">{{ $fLabel }}</label>
                                    <input type="text"
                                           class="input input-bordered w-full"
                                           placeholder="{{ $ph }}"
                                           @if($maxlength) maxlength="{{ $maxlength }}" @endif
                                           @if($readonly) readonly @endif
                                           @disabled($disabledF)
                                           wire:model.defer="value.{{ $fKey }}" />
                                    @error("value.$fKey")
                                    <span class="text-xs text-error mt-0.5">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                        @endforeach

                        @if($needsEvidence)
                            <div class="flex items-start gap-2">
                                <svg class="mt-1 h-4 w-4 opacity-70 peer-checked:opacity-100" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M21 12.79V7a5 5 0 0 0-10 0v9a3 3 0 1 0 6 0V8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>

                                <livewire:user-dashboard.wizard.modules.evidence-uploader
                                    :report-id="$reportId"
                                    :question-key="$q['key']"
                                    :label="$upLabel"
                                    :multiple="true"
                                    :max-files="$maxFiles"
                                    :max-size-mb="$maxSizeMb"
                                    :accept="$accept"
                                    :path-pattern="$pathPat"
                                    wire:key="eu-{{ $q['key'] }}-{{ $val }}" />
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </label>
    @endforeach
</div>
