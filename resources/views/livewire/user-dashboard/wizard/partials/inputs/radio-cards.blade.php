@php
    $name = $q['key'] ?? 'option';
@endphp

<div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3"
     role="radiogroup"
     aria-label="{{ $q['title'] ?? 'options' }}">
    @foreach(($q['options'] ?? []) as $i => $opt)
        @php
            $id    = "{$name}-{$i}";
            $val   = $opt['value'] ?? '';
            $label = $opt['label'] ?? $val;
        @endphp

        <label class="block w-full cursor-pointer select-none" for="{{ $id }}">
            <input  id="{{ $id }}"
                   type="radio"
                   class="peer sr-only"
                   name="{{ $name }}"
                   value="{{ $val }}"
                    wire:model="value" />

            <div class="card bg-base-100 border border-base-300 rounded-2xl
                        transition-all duration-200 ease-out
                        hover:shadow-md hover:-translate-y-[1px]
                        active:scale-[.99]
                        peer-checked:border-primary peer-checked:bg-primary/5
                        peer-focus-visible:ring peer-focus-visible:ring-primary/30
                        peer-focus-visible:ring-offset-2 peer-focus-visible:ring-offset-base-100">
                <div class="card-body px-5 py-4">
                    <span class="text-base md:text-lg font-semibold peer-checked:text-primary">
                        {{ $label }}
                    </span>
                </div>
            </div>
        </label>
    @endforeach
</div>
