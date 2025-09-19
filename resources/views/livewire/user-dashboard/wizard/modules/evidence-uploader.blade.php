{{-- evidence-uploader.blade.php --}}
<div class="space-y-4">

    {{-- Row: uploader (left) + inline list (right) --}}
    <div class="md:flex md:items-start md:gap-6">

        {{-- File input + Upload button --}}
        <div class="md:w-80">
            <div class="flex flex-col gap-2">

                <div class="flex items-stretch gap-2">
                    {{-- File input --}}
                    <x-file
                        wire:model="uploads"
                        :multiple="true"
                        :accept="$accept"
                        hint-class="text-xs text-base-content/60 mt-1"
                        error-class="text-sm text-error mt-1"
                        class="flex-1 h-11"
                    />

                    {{-- Upload button --}}
                    <button
                        type="button"
                        wire:click="save"
                        @disabled(count($uploads) === 0)
                        class="btn btn-primary btn-sm h-10"
                    >
                        {{ __('Upload') }}



                    </button>
                </div>

                {{-- Validation errors --}}
                @error('uploads.*')
                <div class="text-error text-sm">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Inline list (right) : current uploads + existing evidences --}}
        <div class="mt-3 md:mt-0 flex-1">
            <div class="min-h-[2rem] px-3 py-1 rounded-lg
                        flex flex-wrap items-center gap-1">


                {{-- Existing evidences --}}
                @foreach($evidences as $evidence)
                    <div class="flex items-center gap-2 max-w-[18rem] pl-1 pr-1 py-1 rounded-md border-base-content/50
                                text-sm">
                        <span class="truncate" title="{{ $evidence?->original_name }}">
                            {{ $evidence?->original_name }}
                        </span>
                        <button type="button"
                                wire:click="deleteEvidence({{ $evidence->id }})"
                                class="w-5 h-5 flex items-center justify-center rounded-full
                                       bg-base-300 text-base-content/60 hover:bg-error hover:text-error-content transition"
                                title="{{ __('Delete') }}">
                            <x-icon name="o-trash" class="w-3.5 h-3.5"/>
                        </button>
                    </div>
                @endforeach

                {{-- Empty state --}}
                @if(count($uploads) === 0 && count($evidences) === 0)

                    <div role="alert" class="alert alert-info alert-dash">
                        {{ __('No files selected yet') }}
                    </div>

                @endif
            </div>
        </div>
    </div>
</div>
