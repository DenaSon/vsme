<?php

namespace App\Livewire\UserDashboard\Wizard\Modules;

use Livewire\Attributes\On;
use Livewire\Component;

class SkipControl extends Component
{

    public string $currentKey;


    public string $questionTitle = '';


    public bool $showModal = false;


    public ?string $type = null;


    public ?string $note = null;

    public function mount(string $currentKey, string $questionTitle = ''): void
    {
        $this->currentKey = $currentKey;
        $this->questionTitle = $questionTitle;
    }


    public function chooseNA(): void
    {
        $this->type = 'na';
        $this->note = null;
        $this->dispatch('wizard.skip', $this->currentKey, $this->type, $this->note);
    }


    public function openClassified(): void
    {

        $this->type = 'classified';
        $this->showModal = true;
    }


    public function confirmClassified(): void
    {

        $this->validate(['note' =>'string|required|max:500|min:10']);

        $this->dispatch('wizard.skip', $this->currentKey, 'classified', $this->note);
        $this->reset(['showModal', 'note', 'type']);
    }

    public function render()
    {
        return view('livewire.user-dashboard.wizard.modules.skip-control');
    }
}
