<?php

namespace App\Livewire\UserDashboard\Wizard;

use App\Models\Answer;
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

    public $answer = 'both';

    public $currentStep = 2;
    public ?Report $report = null;


    public array $questions = [];
    public string $currentKey = 'b1.q1';
    public array $answers = [];

    public int $total = 80;

    public ?string $moduleChoice = null;   // 'A' | 'B'
    public ?string $companyType  = null;


    public function mount(): void
    {
        $q = Questionnaire::where([
            'code' => 'vsme', 'version' => 'v1', 'is_active' => true
        ])->firstOrFail();


        $disclosures = $q->disclosures()
            ->where('is_active', true)
            ->with(['questions' => function ($qq) {
                $qq->where('is_active', true)
                    ->with(['options' => fn($qo) => $qo->where('is_active', true)->orderBy('sort')])
                    ->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        $this->questions = [];
        foreach ($disclosures as $d) {
            foreach ($d->questions as $question) {
                $arr = $question->toArray();
                $arr['options'] = $question->options->map(fn($opt) => [
                    'key'   => $opt->key,
                    'value' => $opt->value,
                    'label' => $opt->getTranslation('label', app()->getLocale()) ?? $opt->label,
                    'kind'  => $opt->kind,
                    'extra' => $opt->extra,
                ])->values()->all();
                $arr['title'] = $question->getTranslation('title', app()->getLocale()) ?? $question->title;
                $this->questions[$question->key] = $arr;
            }
        }


        $firstKey = array_key_first($this->questions) ?? 'b1.q1';


        $companyId   = 1;
        $this->report = Report::firstOrCreate(
            ['company_id' => $companyId, 'questionnaire_id' => $q->id, 'year' => now()->year],
            ['status' => 'draft', 'module_choice' => 'A', 'current_key' => $firstKey]
        );


        //دسترسی به سوالات ساختاری  اول و دوم <_______________________________________

        $this->moduleChoice = Answer::where('report_id', $this->report->id)
            ->where('question_key', 'b1.q1')
            ->first()?->value;

        $this->companyType = Answer::where('report_id', $this->report->id)
            ->where('question_key', 'b1.q2')
            ->first()?->value;




        $existing = \App\Models\Answer::where('report_id', $this->report->id)
            ->pluck('value', 'question_key');
        $this->answers = [];
        foreach ($existing as $k => $v) {
            data_set($this->answers, $k, $v);
        }


        $savedKey = $this->report->current_key;
        if (!$savedKey || !array_key_exists($savedKey, $this->questions)) {
            $savedKey = $this->firstUnansweredKey() ?? $firstKey;
            $this->report->update(['current_key' => $savedKey]);
        }
        $this->currentKey = $savedKey;
    }


    protected function firstUnansweredKey(): ?string
    {
        foreach (array_keys($this->questions) as $key) {
            $val = data_get($this->answers, $key);
            if ($val === null || $val === '') {
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
        //dd(data_get($this->answers, $this->currentKey));

        try {
            $this->validate(
                $this->rulesForCurrent(),
                [],
                ["answers.$this->currentKey" => $this->currentQuestion['title'] ?? 'This question']
            );

            DB::transaction(function () {
                $this->persistCurrentAnswer();

                if ($this->currentKey === 'b1.q1') {
                    $this->report->module_choice = data_get($this->answers, 'b1.q1');
                }

                if ($this->currentKey === 'b1.q2') {
                    $this->report->mode = data_get($this->answers, 'b1.q2');
                }

                $this->currentKey = $this->nextKey($this->currentKey);
                $this->report->current_key = $this->currentKey;
                $this->report->save();
            });





        } catch (ValidationException $e) {
            $this->warning(__('Please select an option.'));
            throw $e;
        } catch (Throwable $e) {
            $this->warning('System Error','Try again later.');
            Log::error($e);
        }
    }




    /**
     * @throws Throwable
     */
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


    public function changeStep(): void
    {
        $this->currentStep = 1;
    }

    public function getCurrentProperty(): int
    {

        return $this->questions[$this->currentKey]['number'] ?? 1;
    }

    public function getVisibleProperty(): int
    {

        return 12;
    }

    public function getTotalProperty(): int
    {
        return count($this->questions);
    }

    public function getIndexProperty(): int
    {
        return $this->questions[$this->currentKey]['number'] ?? 1;
    }

    public function getProgressProperty(): int
    {

        return $this->total > 0 ? intval(($this->index / $this->total) * 100) : 0;
    }


    #[On('wizard.answer-updated')]
    public function onAnswerUpdated(string $key, mixed $value): void
    {

        if ($key === 'b1.q1') {
            $this->moduleChoice = $value;
        }
        if ($key === 'b1.q2') {
            $this->companyType = $value;
        }
    }




    public function render()
    {
        return view('livewire.user-dashboard.wizard.wizard', [
            'moduleChoice' => $this->moduleChoice,
            'companyType' => $this->companyType,
        ]);
    }


    protected function rulesForCurrent(): array
    {
        $key  = $this->currentKey;
        $q    = $this->currentQuestion;
        $r    = $q['rules'] ?? [];
        $type = $q['type'] ?? null;


        if ($type === 'repeatable-group') {
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
            return $rules;
        }

        // --- Q4: radio-with-other ---
        if ($type === 'radio-with-other') {
            // مهاجرت نرم: اگر مقدار قدیمی string بود، به آبجکت تبدیلش کن
            $val = data_get($this->answers, $key);
            if (is_string($val)) {
                $this->answers[$key] = ['choice' => $val, 'other_text' => null];
            }

            // rules از DB، مثال:
            // { "choice":["required","in:private_ltd,sole,partnership,cooperative,other"],
            //   "other":["nullable","string","min:3","max:200","required_if:choice,other"] }
            $choiceRules = $this->normalizeRuleSpec($r['choice'] ?? ['required','string']);
            $otherRules  = $this->normalizeRuleSpec($r['other']  ?? ['nullable','string','min:3','max:200']);

            // بازنویسی Ruleهای شرطی تا مسیر کامل فیلد مرجع را داشته باشند
            $otherRules  = $this->rewriteConditionalRules($otherRules, base: "answers.$key.");

            return [
                "answers.$key.choice"     => $choiceRules,
                "answers.$key.other_text" => $otherRules,
            ];
        }

        // --- بقیه‌ی انواع جنریک ---
        $line = ['bail'];
        $rr   = $r;

        if (!empty($rr['required'])) $line[] = 'required';
        if (!empty($rr['in']) && is_array($rr['in'])) $line[] = Rule::in($rr['in']);

        $t = $type;
        if ($t === 'number') {
            $line[] = 'numeric';
            if (isset($rr['min'])) $line[] = 'min:'.$rr['min'];
            if (isset($rr['max'])) $line[] = 'max:'.$rr['max'];
        } else {
            $line[] = 'string';
            if (isset($rr['min'])) $line[] = 'min:'.$rr['min'];
            if (isset($rr['max'])) $line[] = 'max:'.$rr['max'];
        }

        if (count($line) === 1) $line[] = 'nullable';
        return ["answers.$key" => $line];
    }


    /**
     * spec: "required|string|max:120" یا ["required","string","max:120"] یا {"required":true,"max":120,"in":[...]}
     */
    protected function normalizeRuleSpec(mixed $spec, ?string $baseAttr = null): array
    {
        if (is_string($spec)) {
            return array_values(array_filter(explode('|', $spec)));
        }
        if (!is_array($spec)) return ['nullable'];

        // لیست → همان را بده
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
                    if ($pattern !== '') {
                        $out[] = str_starts_with($pattern, 'regex:') ? $pattern : 'regex:'.$pattern;
                    }
                    break;

                case 'required_if':
                case 'required_unless':
                case 'prohibited_if':
                case 'prohibited_unless':
                case 'same':
                case 'different':
                    // پشتیبانی از هر دو فرم:
                    // 1) رشته مستقیم: "required_if:answers.b1.q4.choice,other"
                    // 2) آبجکت: {"field":"choice","values":["other"]}
                    if (is_string($v)) {
                        $out[] = str_contains($v, ':') ? $v : ($k.':'.$v);
                    } elseif (is_array($v)) {
                        $field = $v['field'] ?? null;
                        $values = $v['values'] ?? null;
                        if ($field) {
                            // اگر baseAttr داده شده بود، فیلد را کامل کن
                            $fullField = $baseAttr ? ($baseAttr.'.'.$field) : $field;
                            $vals = is_array($values) ? implode(',', $values) : (string)$values;
                            $out[] = $k . ':' . $fullField . ($vals !== '' ? ','.$vals : '');
                        }
                    }
                    break;

                default:
                    if (is_array($v)) $out[] = "$k:".implode(',', $v);
                    else $out[] = "$k:$v";
            }
        }
        return $out ?: ['nullable'];
    }



    /**
     * قواعد شرطی مانند required_if:choice,other را به
     * required_if:answers.{key}.choice,other بازنویسی می‌کند.
     */
    protected function rewriteConditionalRules(array $rules, string $base): array
    {
        // لیست کلیدواژه‌های شرطی که پارامتر اولشان «نام فیلد» است
        $condKeys = [
            'required_if','required_unless','prohibited_if','prohibited_unless',
            'present_if','present_unless','exclude_if','exclude_unless'
        ];

        return array_map(function ($rule) use ($condKeys, $base) {
            if (!is_string($rule)) return $rule;

            foreach ($condKeys as $kw) {
                $prefix = $kw.':';
                if (str_starts_with($rule, $prefix)) {
                    // نمونه: required_if:choice,other
                    $payload = substr($rule, strlen($prefix));
                    // پارامتر اول اسم فیلد است؛ باید base به ابتدای آن اضافه شود
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



    protected function persistCurrentAnswer(): void
    {
        $key   = $this->currentKey;
        $value = data_get($this->answers, $key);

        \App\Models\Answer::updateOrCreate(
            ['report_id' => $this->report->id, 'question_key' => $key],
            ['value' => $value]
        );


        if ($key === 'b1.q1') {
            $this->moduleChoice = $value;
        }
        if ($key === 'b1.q2') {
            $this->companyType = $value;
        }

    }

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
        $keys = $this->keys();
        $i = array_search($key, $keys, true);
        return $keys[min($i + 1, count($keys) - 1)] ?? $key;
    }

    protected function prevKey(string $key): string
    {
        $keys = $this->keys();
        $i = array_search($key, $keys, true);
        return $keys[max($i - 1, 0)] ?? $key;
    }




}
