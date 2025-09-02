@php
    $name   = $q['key'] ?? 'q';

    if (!is_array($value)) {
        $value = ['choice' => null, 'other_text' => null];
    }

    $choice    = $value['choice'] ?? null;
    $otherText = $value['other_text'] ?? '';
@endphp

<div class="mt-6 space-y-3" role="radiogroup" aria-label="{{ $q['title'] ?? 'options' }}">
    @foreach(($q['options'] ?? []) as $i => $opt)
        @php
            $id        = "{$name}-{$i}";
            $val       = $opt['value'] ?? '';
            $label     = $opt['label'] ?? $val;
            $showsText = data_get($opt, 'extra.shows_text', false);
            $isOther   = ($val === 'other');
        @endphp

        {{-- برای هر گزینه یک باکس --}}
        <label wire:key="{{$id}}" class="block" for="{{ $id }}"
               {{-- فقط برای گزینه "other" یک state سبک ایجاد می‌کنیم --}}
               @if($isOther)
                   x-data="{ otherSelected: @js($choice === 'other') }"
            @endif
        >
            <input id="{{ $id }}"
                   type="radio"
                   class="peer sr-only"
                   name="{{ $name }}-choice"
                   value="{{ $val }}"

                   wire:model.defer="value.choice"
                   {{-- اگر other است، با تغییر رادیو، state را هم به‌روز کن --}}
                   @if($isOther)
                       @change="otherSelected = ($event.target.checked && $event.target.value === 'other')"
                @endif
            />

            <div class="card bg-base-100 border border-base-300 rounded-2xl transition
                        peer-checked:border-primary peer-checked:bg-primary/5
                        peer-focus-visible:ring peer-focus-visible:ring-primary/30
                        peer-focus-visible:ring-offset-2 peer-focus-visible:ring-offset-base-100">
                <div class="card-body px-5 py-4">
                    <span class="text-base md:text-lg font-semibold peer-checked:text-primary">
                        {{ $label }}
                    </span>


                    @if($showsText)
                        <div class="mt-3">
                            <textarea
                                class="textarea textarea-bordered w-full"
                                rows="1"
                                placeholder="{{ data_get($opt,'extra.placeholder','Please specify') }}"
                                wire:model.defer="value.other_text"
                                {{-- فعال/غیرفعال فوری، بدون درخواست Livewire --}}
                                x-bind:disabled="typeof otherSelected !== 'undefined' ? !otherSelected : {{ $choice === 'other' ? 'false' : 'true' }}"
                            ></textarea>


                            <p class="text-xs text-base-content/60 mt-1"
                               x-show="typeof otherSelected !== 'undefined' ? !otherSelected : {{ $choice === 'other' ? 'false' : 'true' }}">
                                {{ __('Select "Other" to type here.') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </label>
    @endforeach
</div>
