{{-- evidence-uploader.blade.php --}}
<div class="space-y-3">
    {{-- File input --}}
    <x-file
        wire:model="uploads"
        :multiple="true"
        :accept="$accept"
        hint="{{ $label }}"
        hint-class="text-xs text-gray-500 mt-1"
        error-class="text-sm text-red-500 mt-1"
        class=""
    />

    {{-- Validation errors --}}
    @error('uploads.*')
    <div class="text-error text-sm">{{ $message }}</div>
    @enderror

    {{-- Uploaded files list --}}
    @if(count($uploads) > 0)
        <div class="flex flex-wrap gap-3 mt-3">
            @foreach($uploads as $idx => $file)
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-100 text-gray-700 shadow-sm border border-gray-200">
                    <span class="text-sm font-medium truncate max-w-[150px]">
                        {{ $file->getClientOriginalName() }}
                    </span>
                    <button
                        type="button"
                        wire:click="remove({{ $idx }})"
                        class="w-5 h-5 flex items-center justify-center rounded-full bg-red-500 text-white text-xs hover:bg-red-600 transition"
                        title="Remove"
                    >
                        ×
                    </button>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Action buttons --}}
    <div class="flex justify-end gap-2 mt-3">
        <x-button class="btn-ghost btn-sm" wire:click="resetUploads" label="{{ __('Clean') }}"/>
        <x-button class="btn-primary btn-sm" wire:click="save" spinner label="{{ __('Upload') }}"/>
    </div>
</div>
