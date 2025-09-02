@php
    $name = $name ?? 'option';
    $model = $model ?? null; // e.g. 'answer'
    $options = $options ?? [];
@endphp

<div class="mt-6 space-y-3" role="radiogroup" aria-label="{{ $name }}">
    @foreach ($options as $opt)
        @php($val = $opt['value'] ?? '')
        @php($label = $opt['label'] ?? $val)
        <label class="block">
            <input type="radio" name="{{ $name }}"
                   class="peer sr-only"
                   @if($model) wire:model="{{ $model }}" @endif
                   value="{{ $val }}" />
            <div
                class="card bg-base-100 border border-base-300 rounded-2xl transition
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
