<?php

namespace App\Livewire\UserDashboard\Wizard\Modules;

use App\Models\Country;
use App\Models\Report;
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

    public ?int $reportId = null;


    public $countryOptions;
    public array $naceOptions = [];

    public ?array $sourceSites = [];

    public bool $showMap = false;
    public ?int $activeIndex = null;
    public ?string $mapValue = null;
    public ?string $mapDomId = null;
    public array $siteOptions = [];






    public function mount(array $q, ?array $value = null, ?string $companyType = null, array $sourceSites = [], ?int $reportId = null): void
    {

        $this->reportId = $reportId;

        $siteSource = data_get($q, 'meta.sources.site_list');


        if ($siteSource && $this->reportId) {



            $report = Report::with('answers')->find($reportId);
            if ($report) {

                $rows = $report->rowsByRole('reporting_sites');

                if (empty($rows)) {

                    $rows = $report->answerValue(data_get($siteSource, 'from_question', 'b1.q3'), []);
                }


                $this->sourceSites = collect($rows)->map(function ($r) use ($siteSource) {

                    $valKey = data_get($siteSource, 'value_key', '_uid');
                    $tpl = data_get($siteSource, 'label_tpl', '{{name}}');
                    $label = str($tpl)->replace(['{{name}}', '{{city}}', '{{country}}'], [
                        data_get($r, 'name', '-'),
                        data_get($r, 'city', '-'),
                        data_get($r, 'country', '-'),
                    ])->value();

                    return ['value' => data_get($r, $valKey), 'label' => $label];
                })->all();


            }
        }


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

        $this->backfillUids();

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

        }

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



        if (in_array('site_uid', $this->fields, true)) {
            $this->siteOptions = collect($sourceSites)->map(function ($r) {
                $uid = $r['_uid'] ?? null;
                if (!$uid) return null;
                $label = trim(($r['name'] ?? '') . ' — ' . ($r['city'] ?? '') . (isset($r['country']) ? ', ' . $r['country'] : ''));
                return ['value' => $uid, 'label' => $label ?: $uid];
            })->filter()->values()->all();


        }


        if ($this->value === []) {
            $this->value[] = $this->makeRow();
        }

        $this->enforceMaxRows();


    }




    public function openMap(int $index): void
    {
        $this->activeIndex = $index;
        $this->mapValue = data_get($this->value, "{$index}.geolocation"); // مقدار قبلی (اگر وجود داشته)
        $this->mapDomId = 'map-' . $this->id() . '-row-' . $index . '-' . uniqid();  // DOM id یکتا برای این نوبت
        $this->showMap = true;
    }

    public function updatedMapValue($val): void
    {
        if ($this->activeIndex !== null) {
            data_set($this->value, "{$this->activeIndex}.geolocation", $val);
        }
    }


    public function updatedShowMap($isOpen): void
    {

    }

    private function backfillUids(): void
    {
        if (!is_array($this->value)) {
            $this->value = [];
            return;
        }

        foreach ($this->value as &$row) {
            if (!isset($row['_uid']) || !is_string($row['_uid']) || $row['_uid'] === '') {
                $row['_uid'] = (string)Str::ulid();
            }

            foreach ($this->fields as $k) {
                if (!array_key_exists($k, $row)) $row[$k] = '';
            }
        }
        unset($row);
    }


    protected function makeRow(): array
    {
        $row = ['_uid' => (string)Str::ulid()];
        foreach ($this->fields as $k) $row[$k] = '';
        return $row;
    }





    public function hydrate(): void
    {
        if (!is_array($this->value)) $this->value = [];
        $this->backfillUids();
        if (count($this->value) === 0) {
            $this->value = [$this->makeRow()];
        }
        $this->enforceMaxRows();
    }

    protected function maxRows(): int
    {
        return $this->allowsMultipleRows() ? PHP_INT_MAX : 1;
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

        if (!$this->allowsMultipleRows() && count($this->value) > 0) {
            $this->info(__('Cannot add multiple'), __('This question allows only one row.'));
            return;
        }

        $this->value[] = $this->makeRow();
    }

    public function removeRow($key): void
    {
        if (!$this->allowsMultipleRows()) {
            // اگر این سؤال فقط تک‌ردیفی است، نباید Row حذف شود
            return;
        }

        $rows = $this->value ?? [];
        if (is_numeric($key) && array_key_exists($key, $rows)) {
            unset($rows[$key]);
        } else {
            $rows = array_values(array_filter(
                $rows,
                fn($r) => ($r['_uid'] ?? null) !== $key
            ));
        }

        $this->value = array_values($rows);
    }


    protected function allowsMultipleRows(): bool
    {
        $key = data_get($this->q, 'key');


        if ($key === 'b1.q3') {
            return $this->companyType !== 'individual';
        }


        return true;
    }

    public function render()
    {


        if (!is_array($this->value)) $this->value = [];
        return view('livewire.user-dashboard.wizard.modules.repeatable-group');
    }
}

