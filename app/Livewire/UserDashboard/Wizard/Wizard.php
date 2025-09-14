<?php

namespace App\Livewire\UserDashboard\Wizard;

use App\Models\Answer;
use App\Models\Disclosure;
use App\Models\Questionnaire;
use App\Models\Report;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;
use Throwable;

#[Layout('components.layouts.user-dashboard')]
#[Title('Questionnaire Wizard')]
class Wizard extends Component
{
    use Toast;

    public $currentStep = 2;
    public ?Report $report = null;

    public array $questions = [];
    public string $currentKey = 'b1.q1';
    public array $answers = [];



    public ?string $moduleChoice = null;   // 'A' | 'B'
    public ?string $companyType  = null;   // 'individual' | 'consolidated'

    public ?int $reportId = null;


    //Convert Question to Disclosure
    public array $disclosureMap = [];  // [id => ['code'=>..., 'title'=>...]]
    public array $q2d = [];

    //flag to identify wizard end (modules).
    public bool $isCompleted = false;



    public function mount(): void
    {
        $q = Questionnaire::where([
            'code' => 'vsme', 'version' => 'v1', 'is_active' => true
        ])->firstOrFail();

        $disclosures = $q->disclosures()
            ->where('is_active', true)
            ->with([
                'module',
                'questions' => function ($qq) {
                    $qq->where('is_active', true)
                        ->with(['options' => fn($qo) => $qo->where('is_active', true)->orderBy('sort')])
                        ->orderBy('order');
                }
            ])
            ->orderBy('order')
            ->get();

        $loc      = app()->getLocale();
        $fallback = config('app.fallback_locale', 'en');

        foreach ($disclosures as $d) {

            //prepare for map disclosures
            $this->disclosureMap[$d->id] = [
                'code'  => $d->code,
                'title' => $d->getTranslation('title', $loc)
                    ?? $d->getTranslation('title', $fallback)
                        ?? $d->title,
                'module' => $d->module?->code ?? 'basic',
            ];

            foreach ($d->questions as $question) {
                $this->q2d[$question->key] = $d->id;
                $arr = [
                    'key'     => $question->key,
                    'number'  => $question->number,
                    'type'    => $question->type,
                    'rules'   => $question->rules, //Hardcoded for MVP
                    'options' => $question->options->map(fn($opt) => [
                        'key'   => $opt->key,
                        'value' => $opt->value,
                        'label' => $opt->getTranslation('label', $loc)
                            ?? $opt->getTranslation('label', $fallback)
                                ?? $opt->label,
                        'kind'  => $opt->kind,
                        'extra' => $opt->extra,
                    ])->values()->all(),
                    'title'         => $question->getTranslation('title', $loc, true),
                    'help_official' => $question->getTranslation('help_official', $loc, true),
                    'help_friendly' => $question->getTranslation('help_friendly', $loc, true),
                    'meta'          => $question->meta ?? [],
                ];

                $this->questions[$question->key] = $arr;


            }


        }

        $firstKey = array_key_first($this->questions) ?? 'b1.q1';


       $this->loadReport($q->id,$firstKey);


        // Load saved answers
        $existing = Answer::where('report_id', $this->report->id)
            ->pluck('value', 'question_key');

        foreach ($existing as $k => $v) {
            data_set($this->answers, $k, $v);
        }

        // Normalize/rip values we depend on
        $this->normalizeRadioCards('b1.q1');
        $this->normalizeRadioCards('b1.q2');
        $this->normalizeRadioCards('b1.q5');

        // derive moduleChoice & companyType
        $this->moduleChoice = data_get($this->answers, 'b1.q1.choice')    // A/B
            ?? $this->report->module_choice
            ?? 'A';

        $this->companyType  = data_get($this->answers, 'b1.q2.choice')    // individual/consolidated
            ?? null;



        // Current key
        $savedKey = $this->report->current_key;
        if (!$savedKey || !array_key_exists($savedKey, $this->questions)) {
            $savedKey = $this->firstUnansweredKey() ?? $firstKey;
            $this->report->update(['current_key' => $savedKey]);
        }
        $this->currentKey = $savedKey;

        $this->reportId = $this->report->id;

        if (!$this->isQuestionVisible($this->currentKey)) {
            $this->currentKey = $this->nextKey($this->currentKey);
            $this->report->update(['current_key' => $this->currentKey]);
        }





    }

    private function loadReport(int|string $questionnaireId, string $firstKey): void
    {
        $companyId = 1; // یا Auth::user()->company_id

        try {
            $this->report = Report::firstOrCreate(
                [
                    'company_id'       => $companyId,
                    'questionnaire_id' => $questionnaireId,
                    'year'             => now()->year,
                ],
                [
                    'status'        => 'draft',
                    'module_choice' => 'A',
                    'current_key'   => $firstKey,
                ]
            );

            if (!$this->report instanceof Report) {
                throw new \RuntimeException("Failed to load or create report.");
            }

        } catch (\Throwable $e) {
            Log::error("Failed to load report: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->report = null;  // Optionally keep null and handle later
            $this->warning('Could not load report. Please try again later.');
        }
    }



    protected function firstUnansweredKey(): ?string
    {
        foreach (array_keys($this->questions) as $key) {
            $val = data_get($this->answers, $key);
            if ($val === null || $val === '' || (is_array($val) && empty(array_filter($val, fn($x) => $x !== null && $x !== '')))) {
                return $key;
            }
        }

        return null;
    }

    public function getCurrentQuestionProperty(): array
    {
        return $this->questions[$this->currentKey] ?? [];


    }

    public function next(): void
    {
        try {
            $this->validate(
                $this->rulesForCurrent(),
                [],
                ["answers.$this->currentKey" => $this->currentQuestion['title'] ?? 'This question']
            );

            DB::transaction(function () {
                $this->persistCurrentAnswer();

                //Control of module complete
                $next = $this->nextKey($this->currentKey);
                if ($next === $this->currentKey) {

                    $this->isCompleted = true;
                    $this->success(
                        'The Basic Module was successfully completed.',
                        'All requirements for this module have been fully met.'
                    );




                    return;
                }


                $this->currentKey = $this->nextKey($this->currentKey);
                $this->report->update(['current_key' => $this->currentKey]);
            });

        } catch (ValidationException $e) {
            $this->warning(__('Please select an option.'));
            throw $e;
        } catch (Throwable $e) {
            $this->warning('System Error','Try again later.');
            Log::error($e);
        }
    }

    public function back(): void
    {
        DB::transaction(function () {
            $this->persistCurrentAnswer();
            $this->currentKey = $this->prevKey($this->currentKey);
            $this->report->update(['current_key' => $this->currentKey]);
        });
    }

    public function skip(string $reason): void
    {
        $this->answers[$this->currentKey] = null;
        $this->next();
    }


    protected function visibleKeys(): array
    {
        // انتخاب ماژول‌های مجاز بر اساس انتخاب کاربر (A = فقط basic، B = basic + comprehensive)
        $choice = strtolower((string)($this->moduleChoice ?? $this->report?->module_choice ?? 'a'));
        $allowedModules = in_array($choice, ['a','basic','module_a','basic_module'], true)
            ? ['basic']
            : ['basic','comprehensive'];

        $out = [];
        foreach ($this->questions as $key => $q) {
            $did = $this->q2d[$key] ?? null;

            // فال‌بک مطمئن: اگر module ست نبود، 'basic' فرض کن
            $moduleCode = $did ? ($this->disclosureMap[$did]['module'] ?? 'basic') : 'basic';

            // اگر ماژول این سؤال در مجموعه مجاز نیست، ردش کن
            if (!in_array($moduleCode, $allowedModules, true)) {
                continue;
            }

            // اگر شرط‌های visible_if برقرار نیست، ردش کن
            if (!$this->isQuestionVisible($key)) {
                continue;
            }

            $out[] = $key;
        }

        return $out;
    }



    public function getCurrentProperty(): int
    {
         return $this->index ?? 1;
    }

    public function getVisibleProperty(): int
    {
        return 80;
    }

    public function getTotalProperty(): int
    {
        return count($this->visibleKeys());
    }

    public function getIndexProperty(): int
    {
        $vkeys = $this->visibleKeys();
        $i = array_search($this->currentKey, $vkeys, true);

        if ($i === false) return 1;
        return $i + 1; // 1-based
    }

    public function getProgressProperty(): int
    {
        if ($this->isCompleted) return 100;
        $total = max(1, $this->total);
        return intval(($this->index / $total) * 100);
    }


    #[On('wizard.answer-updated')]
    public function onAnswerUpdated(string $key, mixed $value): void
    {
        if ($key === 'b1.q1') {
            $this->moduleChoice = is_array($value) ? ($value['choice'] ?? null) : (string)$value;

            // اگر currentKey از مسیر جدید خارج شد، بپر روی اولین کلید مجاز
            $vkeys = $this->visibleKeys();
            if (!in_array($this->currentKey, $vkeys, true)) {
                $this->currentKey = $vkeys[0] ?? $this->currentKey;
                $this->report?->update(['current_key' => $this->currentKey]);
            }

            // اگر قبلاً کامل شده بودیم ولی مسیر تغییر کرد، دوباره اجازه حرکت بده
            $this->isCompleted = false;
        }

        if ($key === 'b1.q2') {
            $this->companyType = is_array($value) ? ($value['choice'] ?? null) : (string)$value;
        }
    }




    /**
     * MVP-hardcoded validation + normalization
     */
    protected function rulesForCurrent(): array
    {
        $key  = $this->currentKey;
        $q    = $this->currentQuestion;
        $type = $q['type'] ?? null;

        if (!$this->isQuestionVisible($this->currentKey)) {
            return [];
        }

        if ($type === 'repeatable-group') {
            $r = $q['rules'] ?? [];
            $rules = [];
            $container = [];
            if (!empty($r['array']))    $container[] = 'array';
            if (!empty($r['required'])) $container[] = 'required';
            if (isset($r['min']))       $container[] = 'min:'.$r['min'];
            if (isset($r['max']))       $container[] = 'max:'.$r['max'];
            if (!$container) $container = ['array'];
            $rules["answers.$key"] = $container;

            foreach (($r['item_rules'] ?? []) as $field => $spec) {
                $rules["answers.$key.*.$field"] = $this->normalizeRuleSpec($spec);
            }

            // اگر فیلد site_uid دارد → یکتا باشد
            $hasSiteUid = collect($q['options'] ?? [])
                ->where('kind', 'field')
                ->pluck('key')
                ->contains('site_uid');

            if ($hasSiteUid) {
                $rules["answers.$key.*.site_uid"][] = 'distinct';
            }

            // برای B8.Q4: کشورها نباید تکراری باشند
            $hasCountry = collect($q['options'] ?? [])
                ->where('kind', 'field')
                ->pluck('key')
                ->contains('country');

            if ($key === 'b8.q4' && $hasCountry) {
                $rules["answers.$key.*.country"][] = 'distinct';
            }

            return $rules;
        }

        if ($type === 'multi-input') {
            $r = $q['rules'] ?? [];

            $fields = collect($q['options'] ?? [])
                ->where('kind', 'field')
                ->pluck('key')
                ->values()
                ->all();

            $current = (array) (data_get($this->answers, $key) ?? []);
            $normalized = [];
            foreach ($fields as $f) {
                $normalized[$f] = array_key_exists($f, $current) ? $current[$f] : null;
            }
            data_set($this->answers, $key, $normalized);

            $container = [];
            if (!empty($r['array']))    $container[] = 'array';
            if (!empty($r['required'])) $container[] = 'required';
            if (isset($r['min']))       $container[] = 'min:'.$r['min'];
            if (isset($r['max']))       $container[] = 'max:'.$r['max'];
            if (!$container) $container = ['array','required'];

            $rules = ["answers.$key" => $container];

            foreach (($r['item_rules'] ?? []) as $field => $spec) {
                $rules["answers.$key.$field"] = $this->normalizeRuleSpec($spec);
            }

            if (empty($r['item_rules'])) {
                foreach ($fields as $f) {
                    $rules["answers.$key.$f"] = ['nullable','numeric','min:0'];
                }
            }

            return $rules;
        }

        // radio-with-other
        if ($type === 'radio-with-other') {
            $this->normalizeRadioWithOther($key);
            $r = $q['rules'] ?? [];
            $choiceRules = $this->normalizeRuleSpec($r['choice'] ?? ['required','string']);
            $otherRules  = $this->normalizeRuleSpec($r['other']  ?? ['nullable','string','min:3','max:200']);
            $otherRules  = $this->rewriteConditionalRules($otherRules, base: "answers.$key.");

            return [
                "answers.$key.choice"     => $choiceRules,
                "answers.$key.other_text" => $otherRules,
            ];
        }

        // radio-cards (MVP نمونه‌ها)
        if ($type === 'radio-cards') {
            $this->normalizeRadioCards($key);

            if ($key === 'b1.q1') {
                return [
                    "answers.$key.choice" => ['required', Rule::in(['A','B'])],
                    "answers.$key.desc"   => ['nullable','string','max:500'],
                ];
            }

            if ($key === 'b1.q2') {
                return [
                    "answers.$key.choice" => ['required', Rule::in(['individual','consolidated'])],
                    "answers.$key.desc"   => ['nullable','string','max:500'],
                ];
            }

            if ($key === 'b1.q5') {
                return [
                    "answers.$key.choice" => ['required', Rule::in(['yes','no'])],
                    "answers.$key.desc"   => ['nullable','string','max:500','required_if:answers.'.$key.'.choice,yes'],
                ];
            }

            return [
                "answers.$key.choice" => ['required'],
                "answers.$key.desc"   => ['nullable','string','max:500'],
            ];
        }

        // fallback تک‌مقداری
        $r = $q['rules'] ?? [];
        $line = ['bail'];
        if (!empty($r['required'])) $line[] = 'required';
        if (!empty($r['in']) && is_array($r['in'])) $line[] = Rule::in($r['in']);

        if (($type ?? null) === 'number') {
            $line[] = 'numeric';
            if (isset($r['min'])) $line[] = 'min:'.$r['min'];
            if (isset($r['max'])) $line[] = 'max:'.$r['max'];
        } else {
            $line[] = 'string';
            if (isset($r['min'])) $line[] = 'min:'.$r['min'];
            if (isset($r['max'])) $line[] = 'max:'.$r['max'];
        }

        if (count($line) === 1) $line[] = 'nullable';
        return ["answers.$key" => $line];
    }


    /** نرمال‌سازی برای radio-with-other */
    protected function normalizeRadioWithOther(string $key): void
    {
        $val = data_get($this->answers, $key);
        if (is_string($val)) {
            data_set($this->answers, $key, ['choice' => $val, 'other_text' => null]);
        } elseif (is_array($val)) {
            data_set($this->answers, $key, array_merge(['choice' => null, 'other_text' => null], $val));
        } else {
            data_set($this->answers, $key, ['choice' => null, 'other_text' => null]);
        }
    }

    /** نرمال‌سازی برای radio-cards */
    protected function normalizeRadioCards(string $key): void
    {
        $val = data_get($this->answers, $key);
        if (is_string($val)) {
            data_set($this->answers, $key, ['choice' => $val, 'desc' => null]);
        } elseif (is_array($val)) {
            data_set($this->answers, $key, array_merge(['choice' => null, 'desc' => null], $val));
        } else {
            data_set($this->answers, $key, ['choice' => null, 'desc' => null]);
        }
    }


    /** spec normalizer (بدون تغییر اساسی نسبت به قبل) */
    protected function normalizeRuleSpec(mixed $spec, ?string $baseAttr = null): array
    {
        if (is_string($spec)) return array_values(array_filter(explode('|', $spec)));
        if (!is_array($spec)) return ['nullable'];
        if (array_is_list($spec)) return $spec ?: ['nullable'];

        $out = [];
        foreach ($spec as $k => $v) {
            if ($v === true) { $out[] = $k; continue; }
            if ($v === false || $v === null || $v === '') continue;

            switch ($k) {
                case 'in':
                    $out[] = Rule::in(is_array($v) ? $v : explode(',', (string)$v));
                    break;
                case 'not_in':
                    $out[] = Rule::notIn(is_array($v) ? $v : explode(',', (string)$v));
                    break;
                case 'regex':
                    $pattern = is_string($v) ? $v : (is_array($v) ? reset($v) : '');
                    if ($pattern !== '') $out[] = str_starts_with($pattern, 'regex:') ? $pattern : 'regex:'.$pattern;
                    break;
                default:
                    if (is_array($v)) $out[] = "$k:".implode(',', $v);
                    else $out[] = "$k:$v";
            }
        }
        return $out ?: ['nullable'];
    }

    /** بازنویسی مسیرهای شرطی (required_if و …) به مسیر کامل */
    protected function rewriteConditionalRules(array $rules, string $base): array
    {
        $condKeys = [
            'required_if','required_unless','prohibited_if','prohibited_unless',
            'present_if','present_unless','exclude_if','exclude_unless'
        ];

        return array_map(function ($rule) use ($condKeys, $base) {
            if (!is_string($rule)) return $rule;

            foreach ($condKeys as $kw) {
                $prefix = $kw.':';
                if (str_starts_with($rule, $prefix)) {
                    $payload = substr($rule, strlen($prefix));
                    $parts = explode(',', $payload, 2);
                    $field = $parts[0] ?? '';
                    $rest  = $parts[1] ?? '';
                    if ($field !== '' && !str_starts_with($field, $base)) {
                        $field = $base.$field;
                    }
                    return $kw.':'.$field.($rest !== '' ? ','.$rest : '');
                }
            }
            return $rule;
        }, $rules);
    }


    protected function dependentKeysOf(string $controllerKey): array
    {
        return collect($this->questions)
            ->filter(function ($q) use ($controllerKey) {
                $conds = (array) data_get($q, 'meta.visible_if', []);
                return collect($conds)->contains(fn($c) => data_get($c, 'when.key') === $controllerKey);
            })
            ->keys()
            ->values()
            ->all();
    }

    protected function nullifyAnswers(array $keys): void
    {
        foreach ($keys as $k) {
            unset($this->answers[$k]);
            \App\Models\Answer::updateOrCreate(
                ['report_id' => $this->report->id, 'question_key' => $k],
                ['value' => null]
            );
        }
    }


    protected function handleGateNo(string $gateKey, mixed $gateValue, string $noValue = 'no'): void
    {
        $choice = is_array($gateValue) ? ($gateValue['choice'] ?? null) : $gateValue;
        if ($choice !== $noValue) {
            return;
        }

        // قبلاً: $this->findDependentKeys($gateKey)
        $dependents = $this->dependentKeysOf($gateKey); // ← نام درست متد

        foreach ($dependents as $dep) {
            unset($this->answers[$dep]);

            \App\Models\Answer::updateOrCreate(
                ['report_id' => $this->report->id, 'question_key' => $dep],
                [
                    'value'              => null,
                    //'skipped_reason'     => "auto_skipped_by_gate:{$gateKey}={$noValue}",
                   // 'not_applicable'     => true,
                   // 'classified_omitted' => false,
                ]
            );
        }


        while (!$this->isQuestionVisible($this->currentKey)) {
            $this->currentKey = $this->nextKey($this->currentKey);
            if ($this->currentKey === null) break;
        }
        $this->report->update(['current_key' => $this->currentKey]);
    }







    protected function persistCurrentAnswer(): void
    {
        $key   = $this->currentKey;
        $value = data_get($this->answers, $key);

        Answer::updateOrCreate(
            ['report_id' => $this->report->id, 'question_key' => $key],
            ['value' => $value]
        );

        if ($key === 'b5.q1') {
            $this->handleGateNo('b5.q1', $value, 'no');
        }

        if ($key === 'b7.q4') {
            $this->handleGateNo('b7.q4', $value, 'no');
        }

        if ($key === 'b8.q3') {
            $this->handleGateNo('b8.q3', $value, 'no');
        }

        // Sync derived fields
        if ($key === 'b1.q1') {
            $this->moduleChoice = is_array($value) ? ($value['choice'] ?? null) : (string) $value;
            if ($this->moduleChoice) {
                $this->report->update(['module_choice' => $this->moduleChoice]);
            }
        }

        if ($key === 'b1.q2') {
            $this->companyType = is_array($value) ? ($value['choice'] ?? null) : (string) $value;
        }
    }

    protected function getHiddenDependents(string $gateKey): array
    {
        return match ($gateKey) {
            'b5.q1' => ['b5.q2','b5.q3'],
            'b7.q4' => ['b7.q5'],
            'b8.q3' => ['b8.q4'],
            default => [],
        };
    }



    /**
     * @throws Throwable
     */
    #[On('wizard.skip')]
    public function handleSkip(string $key, string $type, ?string $note = null): void
    {
        if ($key !== $this->currentKey) return;

        DB::transaction(function () use ($type, $note) {
            Answer::updateOrCreate(
                ['report_id' => $this->report->id, 'question_key' => $this->currentKey],
                [
                    'value'              => null,
                    'skipped_reason'     => $note,
                    'not_applicable'     => $type === 'na',
                    'classified_omitted' => $type === 'classified',
                ]
            );

            $this->currentKey = $this->nextKey($this->currentKey);
            $this->report->update(['current_key' => $this->currentKey]);
        });

        $this->success(__('Question skipped.'));
    }

    protected function keys(): array
    {
        return array_keys($this->questions);
    }

    protected function nextKey(string $key): string
    {
        $keys = $this->visibleKeys();
        $i = array_search($key, $keys, true);
        if ($i === false) return $keys[0] ?? $key;

        return $keys[$i + 1] ?? $keys[$i]; // اگر بعدی نیست، همان فعلی برگردد = پایان
    }

    protected function prevKey(string $key): string
    {
        $keys = $this->visibleKeys();
        $i = array_search($key, $keys, true);
        if ($i === false) return $keys[0] ?? $key;

        return $keys[max(0, $i - 1)] ?? $keys[0] ?? $key;
    }



    protected function isQuestionVisible(string $key): bool
    {
        $q = $this->questions[$key] ?? null;
        if (!$q) return false;

        $conds = data_get($q, 'meta.visible_if', []);
        if (!$conds) return true; // اگر شرطی نبود، پیش‌فرض: قابل نمایش

        foreach ($conds as $cond) {
            $when = $cond['when'] ?? [];
            $depKey = $when['key'] ?? null;
            $val    = data_get($this->answers, "$depKey.choice")
                ?? data_get($this->answers, $depKey);

            if (array_key_exists('eq',  $when) && $val !== $when['eq'])  return false;
            if (array_key_exists('neq', $when) && $val === $when['neq']) return false;

        }
        return true;
    }



    //accessor for get disclosure title
    public function getCurrentDisclosureTitleProperty(): ?string
    {
        $did = $this->q2d[$this->currentKey] ?? null;
        return $did ? ($this->disclosureMap[$did]['title'] ?? null) : null;
    }

    public function getCurrentDisclosureCodeProperty(): ?string
    {
        $did = $this->q2d[$this->currentKey] ?? null;
        return $did ? ($this->disclosureMap[$did]['code'] ?? null) : null;
    }




    public function render()
    {
        return view('livewire.user-dashboard.wizard.wizard', [
            'moduleChoice' => $this->moduleChoice,
            'companyType'  => $this->companyType,
        ]);
    }
}
