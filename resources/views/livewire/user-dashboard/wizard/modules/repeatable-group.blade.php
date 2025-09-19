<div class="space-y-4">
    @forelse($value as $i => $row)
        <div
            wire:key="rg-row-{{ $row['_uid'] ?? 'i-'.$i }}"
            class="card w-full !max-w-none border border-accent-content/10 p-4 grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 shadow-lg"
        >
            @php $rowKey = $row['_uid'] ?? $i; @endphp

            @foreach($fields as $fkey)
                @php
                    $meta      = $fieldMeta[$fkey] ?? [];
                    $label     = $meta['label'] ?? $fkey;
                    $extra     = $meta['extra'] ?? [];
                    $type      = $extra['type'] ?? 'text';
                    $choices   = $extra['choices'] ?? [];
                    $hint      = $extra['hint'] ?? null;
                    $suffix    = $extra['suffix'] ?? null;
                    $readonly  = $extra['readonly'] ?? false;
                    $disabled  = $extra['disabled'] ?? false;
                    $maxlength = $extra['maxlength'] ?? null;
                    $pattern   = $extra['pattern'] ?? null;
                @endphp

                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-medium">{{ $label }}</label>

                    @if($fkey === 'geolocation')
                        <x-input
                            wire:click.debounce.20ms="openMap({{ $i }})"
                            wire:model.defer="value.{{ $i }}.geolocation"
                            placeholder="00.00000 | 00.00000"
                            readonly
                            class="read-only:bg-base-200"
                        >
                            <x-slot:append>
                                <x-button
                                    label="{{ __('Pick') }}"
                                    icon="o-map"
                                    spinner
                                    class="join-item btn-dash btn-sm mt-1 rounded-br-2xl rounded-tr-2xl"
                                    wire:click="openMap({{ $i }})"
                                />
                            </x-slot:append>
                        </x-input>

                    @elseif($type === 'select' && $fkey === 'country')
                        <select
                            class="select select-bordered w-full"
                            wire:model.defer="value.{{ $i }}.{{ $fkey }}"
                            aria-label="{{ $label }}"
                            @disabled($disabled)
                        >
                            <option value="">— Select —</option>
                            @foreach($countryOptions as $opt)
                                <option value="{{ $opt['value'] }}">
                                    {{ $opt['label'] }}
                                </option>
                            @endforeach
                        </select>

                    @elseif($type === 'select' && $fkey === 'nace')
                        <select
                            class="select select-bordered w-full"
                            wire:model.defer="value.{{ $i }}.{{ $fkey }}"
                            aria-label="{{ $label }}"
                            @disabled($disabled)
                        >
                            <option value="">— Select —</option>
                            @foreach($naceOptions as $opt)
                                <option value="{{ $opt['value'] }}">
                                    {{ $opt['label'] }}
                                </option>
                            @endforeach
                        </select>

                    @elseif($type === 'select')
                        @php

                            $opts = $choices;

                            if ($fkey === 'site_uid' && !empty($sourceSites ?? [])) {

                                $opts = $sourceSites;

                            }
                        @endphp
                        <select
                            class="select select-bordered w-full"
                            wire:model.defer="value.{{ $i }}.{{ $fkey }}"
                            aria-label="{{ $label }}"
                            @disabled($disabled)
                        >
                            <option value="">— Select —</option>
                            @foreach($opts as $opt)
                                <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                            @endforeach
                        </select>

                    @elseif($type === 'number')
                        <div class="join w-full">
                            <input
                                type="number"
                                class="input input-bordered join-item w-full"
                                wire:model.defer="value.{{ $i }}.{{ $fkey }}"
                                placeholder="{{ $extra['placeholder'] ?? '' }}"
                                step="{{ $extra['step'] ?? 'any' }}"
                                @if(isset($extra['min'])) min="{{ $extra['min'] }}" @endif
                                @if(isset($extra['max'])) max="{{ $extra['max'] }}" @endif
                                @if($readonly) readonly @endif
                                @disabled($disabled)
                                aria-label="{{ $label }}"
                            />
                            @if($suffix)
                                <span class="join-item inline-flex items-center px-3 text-sm text-base-content/70 border border-l-0 border-base-300 rounded-r-lg bg-base-200">
                                    {{ $suffix }}
                                </span>
                            @endif
                        </div>

                    @elseif($type === 'textarea')
                        <textarea
                            class="textarea textarea-bordered w-full"
                            rows="{{ $extra['rows'] ?? 3 }}"
                            wire:model.defer="value.{{ $i }}.{{ $fkey }}"
                            placeholder="{{ $extra['placeholder'] ?? '' }}"
                            @if($maxlength) maxlength="{{ $maxlength }}" @endif
                            @if($readonly) readonly @endif
                            @disabled($disabled)
                            aria-label="{{ $label }}"
                        ></textarea>

                    @else
                        <div class="join w-full">
                            <input
                                type="text"
                                class="input input-bordered join-item w-full"
                                wire:model.defer="value.{{ $i }}.{{ $fkey }}"
                                placeholder="{{ $extra['placeholder'] ?? '' }}"
                                @if($maxlength) maxlength="{{ $maxlength }}" @endif
                                @if($pattern) pattern="{{ $pattern }}" @endif
                                @if($readonly) readonly @endif
                                @disabled($disabled)
                                aria-label="{{ $label }}"
                            />
                            @if($suffix)
                                <span class="join-item inline-flex items-center px-3 text-sm text-base-content/70 border border-l-0 border-base-300 rounded-r-lg bg-base-200">
                                    {{ $suffix }}
                                </span>
                            @endif
                        </div>
                    @endif


                    @if($hint)
                        <p class="text-xs text-base-content/70 mt-0.5">{{ $hint }}</p>
                    @endif

                    {{-- error نمایش خطا --}}
                    @error("value.$i.$fkey")

                    <span class="text-xs text-error mt-0.5">{{ $message }}</span>
                    @enderror
                </div>
            @endforeach


            @if($this->allowsMultipleRows())
                <div class="md:col-span-2 flex justify-end">
                    <x-button
                        label="Remove"
                        icon="o-trash"
                        spinner
                        class="btn btn-error btn-outline btn-sm"
                        wire:click="removeRow('{{ $row['_uid'] ?? $i }}')"
                    />
                </div>
            @endif

        </div>
    @empty
        <div role="alert" class="alert mt-3 mb-3">
            <x-icon name="o-exclamation-triangle" class="text-primary"/>
            <span>{{ __('No company row added') }}</span>
        </div>
    @endforelse

        @if($this->allowsMultipleRows())
        <x-button
            label="{{ __('Add row') }}"
            icon="o-plus"
            class="btn btn-outline btn-sm"
            wire:click.debounce.200ms="addRow"
            spinner
        />
        @endif


    @include('livewire.user-dashboard.wizard.modules.map-modal')
</div>
