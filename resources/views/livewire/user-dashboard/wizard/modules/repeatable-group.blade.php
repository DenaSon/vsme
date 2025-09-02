<div class="space-y-4">
    @forelse($value as $i => $row)
        <div wire:key="rg-row-{{ $row['_uid'] ?? 'i-'.$i }}" class="card border border-accent-content/10 p-4 grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 shadow-lg">
            @php $rowKey = $row['_uid'] ?? $i; @endphp
            @foreach($fields as $fkey)
                @php
                    $meta    = $fieldMeta[$fkey] ?? [];
                    $label   = $meta['label'] ?? $fkey;
                    $extra   = $meta['extra'] ?? [];
                    $type    = $extra['type'] ?? 'text';
                    $choices = $extra['choices'] ?? [];
                @endphp

                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium">{{ $label }}</label>

                    @if($fkey === 'geolocation')

                        <x-input
                            wire:click.debounce.250ms="openMap({{ $i }})"
                            wire:model.defer="value.{{ $i }}.geolocation"
                            placeholder="00.00000 | 00.00000"
                            readonly
                            class="read-only:bg-base-200">

                            <x-slot:append>
                                <x-button
                                    label="{{ __('Pick') }}"
                                    icon="o-map"
                                    spinner
                                    class="join-item btn-primary btn-sm mt-1 rounded-br-2xl rounded-tr-2xl"
                                    wire:click.debounce.250ms="openMap({{ $i }})"/>
                            </x-slot:append>
                        </x-input>

{{--                    @elseif($type === 'select' && $fkey === 'country')--}}

{{--                        <x-choices-offline--}}

{{--                            wire:model="value.{{ $i }}.{{ $fkey }}"--}}
{{--                            :options="$countryOptions"--}}
{{--                            option-label="label"--}}
{{--                            option-value="value"--}}
{{--                            placeholder="{{ __('Select a country...') }}"--}}
{{--                            searchable--}}
{{--                            clearable--}}
{{--                            single />--}}


{{--                    @elseif($type === 'select' && $fkey === 'nace')--}}
{{--                        --}}{{-- کد NACE: searchable + single + clearable --}}
{{--                        <x-choices-offline--}}
{{--                            wire:model="value.{{ $i }}.{{ $fkey }}"--}}
{{--                            :options="$naceOptions"--}}
{{--                            option-label="label"--}}
{{--                            option-value="value"--}}
{{--                            placeholder="{{__('Select NACE code')}}"--}}
{{--                            searchable--}}
{{--                            clearable--}}
{{--                            single />--}}

                    @elseif($type === 'select')
                        <select class="select select-bordered w-full"
                                wire:model.defer="value.{{ $i }}.{{ $fkey }}"
                                aria-label="{{ $label }}">
                            <option value="">— Select —</option>
                            @foreach($choices as $opt)
                                <option value="{{ $opt['value'] ?? '' }}">
                                    {{ $opt['label'] ?? ($opt['value'] ?? '') }}
                                </option>
                            @endforeach
                        </select>

                    @elseif($type === 'textarea')
                        <textarea class="textarea textarea-bordered w-full"
                                  rows="3"
                                  wire:model.defer="value.{{ $i }}.{{ $fkey }}"
                                  placeholder="{{ $extra['placeholder'] ?? '' }}"
                                  aria-label="{{ $label }}"></textarea>

                    @else
                        <input type="text"
                               class="input input-bordered w-full"
                               wire:model.defer="value.{{ $i }}.{{ $fkey }}"
                               placeholder="{{ $extra['placeholder'] ?? '' }}"
                               aria-label="{{ $label }}"/>
                    @endif
                </div>
            @endforeach

            @if($companyType == 'consolidated')
                <div class="md:col-span-2 flex justify-end">
                    <x-button label="Remove" icon="o-trash" spinner
                              class="btn btn-error btn-outline btn-sm"
                              wire:click="removeRow({{ $i }})"/>
                </div>
            @endif
        </div>
    @empty
        <div role="alert" class="alert mt-3 mb-3">
            <x-icon name="o-exclamation-triangle" class="text-primary"/>
            <span>{{ __('No company row added') }}</span>
        </div>
    @endforelse

    @if($companyType == 'consolidated')
        <x-button label="{{ __('Add company') }}" icon="o-plus"
                  class="btn btn-outline btn-sm"
                  wire:click.debounce.200ms="addRow" spinner/>
    @endif

    @include('livewire.user-dashboard.wizard.partials.inputs.inc.q3-geolocation-modal')
</div>


