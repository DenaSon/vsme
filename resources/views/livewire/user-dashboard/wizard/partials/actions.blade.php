@php
    $t_next = __("ui.next");
    $t_back = __("ui.back");

@endphp
<div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4">

    <x-button  wire:click.debounce.50ms="back" label="{{$t_back}}" spinner icon="o-arrow-left" class="btn-outline sm:w-auto min-w-[7.5rem]"/>


    <div class="flex flex-col sm:flex-row items-stretch gap-2 w-full sm:w-auto order-1 sm:order-2">


        <livewire:user-dashboard.wizard.modules.skip-control
            :current-key="$this->currentKey"
            :question-title="($questions[$this->currentKey]['title'] ?? '')"
            wire:key="skip-{{ $this->currentKey }}"
        />





        <x-button wire:loading.attr="disabled" wire:click.debounce.50ms="next" label="{{$t_next}}" spinner icon-right="o-arrow-right" class="btn-primary sm:w-auto min-w-[7.5rem]"/>



    </div>
</div>




