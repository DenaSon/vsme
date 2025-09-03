<livewire:user-dashboard.wizard.modules.repeatable-group
    :q="$q"
    :moduleChoice="$this->moduleChoice"
    :companyType="$this->companyType"

    wire:model="value"
    wire:key="rg-{{ $q['key'] }}"

/>



