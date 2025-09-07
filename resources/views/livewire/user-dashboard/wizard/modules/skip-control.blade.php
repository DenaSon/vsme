<div class="flex items-center gap-2">
    <details class="dropdown dropdown-end">
        <summary class="btn btn-outline min-w-[7rem]">
            {{__('ui.skip')}}
            <x-heroicon-o-chevron-down class="w-4 h-4 ms-1"/>
        </summary>
        <ul class="dropdown-content menu p-2 shadow-md bg-base-100 rounded-box w-56 right-0">
            <li><button type="button" wire:click="chooseNA">N/A</button></li>
            <li><button type="button" wire:click="openClassified">{{__('ui.classified_information')}}</button></li>
        </ul>
    </details>


    <x-modal wire:model="showModal" title="{{__('ui.classified_information')}}" separator subtitle="{{ $questionTitle ?? '' }}">


            <label class="block text-sm font-medium mb-1"></label>
            <x-textarea
                required
                wire:model.defer="note"
                rows="4"
                placeholder="{{__('Specify the reason for treating this question as confidential')}}"
                class="w-full"
            />


        <x-slot:actions>
            <x-button
                :label="__('ui.cancel')"
                class="btn-ghost"
                wire:click="$set('showModal', false)"
            />

            <x-button
                :label="__('ui.confirm')"
                class="btn-primary"
                wire:click="confirmClassified"
            />
        </x-slot:actions>

    </x-modal>

</div>
