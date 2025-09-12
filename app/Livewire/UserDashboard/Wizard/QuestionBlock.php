<?php

namespace App\Livewire\UserDashboard\Wizard;

use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class QuestionBlock extends Component
{

    public ?string $moduleChoice = null;
    public ?string $companyType = null;

    public $index;

    use Toast;

    public $helpModal = false;

    /** @var array{key:string,number:int,title:string,type:string,options?:array} */
    public array $q = [];
    public int $total = 0;

    #[Modelable]
    public mixed $value = null;

    public ?int $reportId = null;


    public function mount(array $q, int $total = 0, $value = null, ?string $moduleChoice = null, ?string $companyType = null, ?int $reportId = null): void

    {


        $this->total = $total;
        $this->value = $value;

        $this->q = $q;

        $this->reportId = $reportId;

        $this->moduleChoice = $moduleChoice;
        $this->companyType = $companyType;
        $this->normalizeValueForType();


    }

    public function hydrate(): void
    {
        $this->normalizeValueForType();
    }

    private function normalizeValueForType(): void
    {
        if (($this->q['type'] ?? null) === 'radio-cards') {
            if (is_string($this->value)) {

                $this->value = ['choice' => $this->value, 'desc' => null];
            } elseif (is_array($this->value)) {

                $this->value += ['choice' => null, 'desc' => null];
            } else {

                $this->value = ['choice' => null, 'desc' => null];
            }
        }

        if (($this->q['type'] ?? null) === 'radio-with-other') {
            if (is_string($this->value)) {
                $this->value = ['choice' => $this->value, 'other_text' => null];
            } elseif (is_array($this->value)) {
                $this->value += ['choice' => null, 'other_text' => null];
            } else {
                $this->value = ['choice' => null, 'other_text' => null];
            }
        }


        if (($this->q['type'] ?? null) === 'multi-input') {

            $fieldKeys = collect($this->q['options'] ?? [])
                ->filter(fn($o) => ($o['kind'] ?? 'field') === 'field')
                ->pluck('key')
                ->filter()
                ->values()
                ->all();


            $current = is_array($this->value) ? $this->value : [];


            foreach ($fieldKeys as $fk) {
                if (!array_key_exists($fk, $current)) {
                    $current[$fk] = null;
                }
            }

            $this->value = $current;
        }


    }


    #[On('wizard.prefill')]
    public function prefill(string $key, mixed $val): void
    {
        if (($this->q['key'] ?? null) === $key) {
            $this->value = $val;
        }
    }


    public function updatedValue(): void
    {
        $this->dispatch('wizard.answer-updated', key: $this->q['key'] ?? '', value: $this->value);
    }


    protected function firstUnansweredKey(): ?string
    {
        foreach (array_keys($this->questions) as $key) {
            if (data_get($this->answers, $key) === null || data_get($this->answers, $key) === '') {
                return $key;
            }
        }
        return null;
    }

    public function render()
    {
        return view('livewire.user-dashboard.wizard.question-block')->with(['reportId' => $this->reportId]);
    }


}
