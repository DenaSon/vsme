<?php

namespace App\Livewire\UserDashboard\Wizard\Modules;

use App\Models\Country;
use Illuminate\Support\Str;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Component;

class RepeatableGroup extends Component
{
    use \Mary\Traits\Toast;

    public array $q = [];

    #[Modelable]
    public ?array $value = [];

    public array $fields = [];
    public array $fieldMeta = [];

    public ?string $companyType = null;



    public $countryOptions;
    public array $naceOptions = [];




    public bool $showMap = false;
    public ?int $activeIndex = null;
    public ?string $mapValue = null;
    public ?string $mapDomId = null;

    public function openMap(int $index): void
    {
        $this->activeIndex = $index;
        $this->mapValue    = data_get($this->value, "{$index}.geolocation"); // مقدار قبلی (اگر وجود داشته)
        $this->mapDomId    = 'map-'.$this->id().'-row-'.$index.'-'.uniqid();  // DOM id یکتا برای این نوبت
        $this->showMap     = true;
    }

    public function updatedMapValue($val): void
    {
        if ($this->activeIndex !== null) {
            data_set($this->value, "{$this->activeIndex}.geolocation", $val);
        }
    }


    public function updatedShowMap($isOpen): void
    {

        if ($isOpen) {

            $this->dispatch('leaflet:init', id: 'map-container');
        }
    }




    protected function makeRow(): array
    {
        $row = ['_uid' => (string)Str::ulid()];
        foreach ($this->fields as $k) $row[$k] = '';
        return $row;
    }


    public function mount(array $q, ?array $value = null, ?string $companyType = null): void
    {


        $this->q = $q;
        $this->value = $value ?? [];
        $this->companyType = $companyType;


        $fields = collect($q['options'] ?? [])
            ->filter(fn($opt) => ($opt['kind'] ?? null) === 'field')
            ->values();

        $this->fieldMeta = $fields->mapWithKeys(fn($f) => [
            $f['key'] => [
                'label' => $f['label'] ?? $f['key'],
                'extra' => $f['extra'] ?? [],
            ],
        ])->all();

        $this->fields = $fields->pluck('key')->all();


        if (in_array('country', $this->fields, true)) {

            $this->countryOptions = Country::query()
                ->orderBy('name')
                ->get(['code', 'name'])
                ->map(fn($c) => [
                    'label' => $c->name,
                    'value' => $c->code,
                ])
                ->all();

            if (in_array('nace', $this->fields, true)) {
                $this->naceOptions = collect([
                    ['code' => 'A.1.1.1', 'title' => 'Growing of cereals (except rice), leguminous crops and oil seeds'],
                    ['code' => 'A.1.1.2', 'title' => 'Growing of rice'],
                    ['code' => 'A.1.1.3', 'title' => 'Growing of vegetables and melons, roots and tubers'],
                    ['code' => 'A.1.1.4', 'title' => 'Crop production'],
                    ['code' => 'C.10.1.1', 'title' => 'Meat processing'],
                    ['code' => 'C.10.1.2', 'title' => 'Processing of dairy products'],
                    ['code' => 'G.47.1.1', 'title' => 'Retail sale in non-specialized stores'],
                    ['code' => 'G.47.2.1', 'title' => 'Retail sale of food, beverages and tobacco in specialized stores'],
                ])->map(fn($n) => [
                    'label' => "{$n['code']} - {$n['title']}",
                    'value' => $n['code'],
                ])->all();
            }

        }


        $fields = collect($q['options'] ?? [])
            ->filter(fn($opt) => ($opt['kind'] ?? null) === 'field')
            ->values();

        $this->fields = $fields->pluck('key')->all();
        $this->fieldMeta = $fields->mapWithKeys(fn($f) => [
            $f['key'] => [
                'label' => $f['label'] ?? $f['key'],
                'extra' => $f['extra'] ?? [],
            ],
        ])->all();


        if ($this->value === []) {
            $this->addRow();
        }

        $this->enforceMaxRows();

    }


    public function hydrate(): void
    {
        if (!is_array($this->value)) $this->value = [];
        if (count($this->value) === 0) {
            $this->value = [$this->makeRow()];  // ← اگر والد دوباره null فرستاد، خنثی کن
        }
        $this->enforceMaxRows();
    }

    protected function maxRows(): int
    {
        return ($this->companyType === 'individual') ? 1 : PHP_INT_MAX;
    }

    protected function enforceMaxRows(): void
    {
        if (!is_array($this->value)) $this->value = [];
        $max = $this->maxRows();
        if (count($this->value) > $max) {
            $this->value = array_slice($this->value, 0, $max);
        }
    }

    public function addRow(): void
    {
        if (!is_array($this->value)) $this->value = [];

        if ($this->companyType === 'individual' && count($this->value) > 0) {
            $this->info('Cannot add multiple', 'Single-company selection does not allow multiple rows.');
            return;
        }

        $row = [];
        foreach ($this->fields as $k) $row[$k] = '';
        $this->value[] = $row;
    }

    public function removeRow(int $i): void
    {
        if ($this->companyType === 'individual') return;


        $this->value = $this->value ?? [];

        if (array_key_exists($i, $this->value)) {
            unset($this->value[$i]);
            $this->value = array_values($this->value);
        }
    }

    public function render()
    {


        if (!is_array($this->value)) $this->value = [];
        return view('livewire.user-dashboard.wizard.modules.repeatable-group');
    }
}

