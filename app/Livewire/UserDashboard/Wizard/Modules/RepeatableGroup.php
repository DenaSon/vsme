<?php

namespace App\Livewire\UserDashboard\Wizard\Modules;

use App\Models\Country;
use Illuminate\Support\Str;
use Livewire\Attributes\Modelable;
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

    public bool $showMapModal = false;
    public ?int $activeIndex = null;
    public ?string $picked = null;
    public string $mapDomId; // unique id per component
    public string $componentId;


    public $countryOptions;
    public array $naceOptions = [];


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

        $this->mapDomId = 'leaflet-picker-' . uniqid();
        $this->componentId = $this->id();

        if ($this->value === []) {
            $this->addRow();
        }

        $this->enforceMaxRows();

    }

    public function openMap(int $index): void
    {
        $this->activeIndex = $index;
        $this->picked = null;

        $existing = data_get($this->value, "$index.geolocation");
        if (is_string($existing) && str_contains($existing, '|')) {
            $this->picked = $existing;
        }

        $this->showMapModal = true;


        $this->dispatch('open-map', id: $this->mapDomId, coords: $this->picked, componentId: $this->componentId);


    }

    #[\Livewire\Attributes\On('map-picked')]
    public function onMapPicked($payload = null): void
    {
        if (is_array($payload) && isset($payload['lat'], $payload['lng'])) {
            $this->picked = "{$payload['lat']} | {$payload['lng']}";
        }
    }

    public function confirmPick(): void
    {
        if ($this->activeIndex === null) return;
        if (!$this->picked) {
            $this->warning('No location selected', 'Click on the map to pick one.');
            return;
        }

        data_set($this->value, "{$this->activeIndex}.geolocation", $this->picked);

        $this->showMapModal = false;
        $this->success('Location set', $this->picked);
    }

    /** Livewire هر رندر این را صدا می‌زند */
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

