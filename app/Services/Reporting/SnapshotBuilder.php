<?php

namespace App\Services\Reporting;

use App\Models\Report;
use App\Models\ReportSnapshot;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SnapshotBuilder
{
    public const SCOPE_BASIC          = 'basic';
    public const SCOPE_COMPREHENSIVE  = 'comprehensive';
    public const SCOPE_BOTH           = 'both';

    public const FORMAT_VERSION = 1; // payload schema version

    protected Report $report;
    protected string $scope = self::SCOPE_BASIC;
    protected string $locale = 'en';
    protected ?int $generatedBy = null;

    private function __construct(Report $report)
    {
        $this->report = $report;
    }

    /** Factory */
    public static function for(Report $report): self
    {
        return new self($report);
    }

    /** Optional convenience if you only have ID */
    public static function forId(int $reportId): self
    {
        /** @var Report $report */
        $report = Report::query()->findOrFail($reportId);
        return new self($report);
    }

    /** Scopes */
    public function basic(): self
    {
        $this->scope = self::SCOPE_BASIC;
        return $this;
    }

    public function comprehensive(): self
    {
        $this->scope = self::SCOPE_COMPREHENSIVE;
        return $this;
    }

    public function both(): self
    {
        $this->scope = self::SCOPE_BOTH;
        return $this;
    }

    /** Rendering locale for resolved labels/titles */
    public function withLocale(string $locale): self
    {
        $this->locale = $locale;
        return $this;
    }

    /** Who triggered the generation (nullable) */
    public function byUser(?int $userId): self
    {
        $this->generatedBy = $userId;
        return $this;
    }

    /**
     * Build payload (array) without storing.
     * @return array payload ready to be JSON-encoded into payload_json
     */
    public function build(): array
    {
        // 0) Report & Questionnaire
        $report = $this->report->loadMissing(['questionnaire']);
        $qn     = $report->questionnaire
            ?? \App\Models\Questionnaire::query()->findOrFail($report->questionnaire_id);

        // 1) Modules in scope (MVP: basic)
        $moduleCodes = match ($this->scope) {
            self::SCOPE_BASIC         => ['basic'],
            self::SCOPE_COMPREHENSIVE => ['comprehensive'],
            default                   => ['basic', 'comprehensive'],
        };

        $moduleIds = \App\Models\Module::query()
            ->where('questionnaire_id', $qn->id)
            ->whereIn('code', $moduleCodes)
            ->pluck('id');

        // 2) Disclosures (+ questions)
        $disclosures = \App\Models\Disclosure::query()
            ->where('questionnaire_id', $qn->id)
            ->whereIn('module_id', $moduleIds)
            ->where('is_active', 1)
            ->orderBy('order')
            ->get();

        $questions = \App\Models\Question::query()
            ->whereIn('disclosure_id', $disclosures->pluck('id'))
            ->where('is_active', 1)
            ->orderBy('order')
            ->get();

        // 3) Options for questions (برای map کردن مقدار به لیبل، اگر لازم شد)
        $optionsByQuestion = \App\Models\QuestionOption::query()
            ->whereIn('question_id', $questions->pluck('id'))
            ->where('is_active', 1)
            ->orderBy('sort')
            ->get()
            ->groupBy('question_id'); // question_id => Collection<QuestionOption>

        // 4) Answers for this report
        $answers = \App\Models\Answer::query()
            ->where('report_id', $report->id)
            ->whereIn('question_key', $questions->pluck('key'))
            ->get()
            ->keyBy('question_key'); // question_key => Answer

        // 5) Build blocks (group questions by disclosure)
        $questionsByDisclosure = $questions->groupBy('disclosure_id');

        $blocks = [];
        foreach ($disclosures as $disc) {
            $items = [];

            foreach ($questionsByDisclosure[$disc->id] ?? [] as $q) {
                $ans  = $answers->get($q->key);
                $opts = $optionsByQuestion->get($q->id) ?? collect();

                $items[] = [
                    'question_key' => $q->key,
                    'label'        => $this->tr($q->title, $q->key),
                    'value'        => $this->presentValue($ans?->value, $opts),
                    'flags'        => [
                        'na'         => (bool)($ans?->not_applicable ?? false),
                        'classified' => (bool)($ans?->classified_omitted ?? false),
                        'skipped'    => (bool)($ans?->skipped_reason),
                    ],
                ];
            }

            if (!empty($items)) {
                $blocks[] = [
                    'code'  => $disc->code,
                    'title' => $this->tr($disc->title, $disc->code),
                    'items' => $items,
                ];
            }
        }


        return [
            'company' => [
                'name'          => data_get($report, 'meta.company_name'),
                'mode'          => $report->mode,                     // individual|consolidated
                'module_choice' => $report->module_choice,            // A|B
                'year'          => $report->year,
                'period'        => [
                    'start' => $report->period_start?->toDateString(),
                    'end'   => $report->period_end?->toDateString(),
                ],
            ],
            'questionnaire' => [
                'code'    => $qn->code ?? 'vsme',
                'version' => $qn->version ?? 'v1',
                'locale'  => $this->locale,
            ],
            'scope'  => $this->scope,
            'blocks' => $blocks,

        ];
    }

    // در SnapshotBuilder جایگزین کن
    protected function tr($json, string $fallback = ''): string
    {
        if (is_null($json)) {
            return $fallback;
        }

        if (is_array($json)) {
            return $json[$this->locale] ?? $json['en'] ?? (string) (reset($json) ?: $fallback);
        }

        if (is_string($json)) {
            // اگر رشته JSON باشد دیکد کن، وگرنه همان رشته را بده
            $decoded = json_decode($json, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded[$this->locale] ?? $decoded['en'] ?? (string) (reset($decoded) ?: $fallback);
            }
            return $json; // plain string already localized or single-language
        }

        return $fallback;
    }



    protected function presentValue($value, \Illuminate\Support\Collection $options)
    {
        if (is_null($value)) return null;

        // helper برای map یک مقدار ساده به لیبل option
        $mapScalar = function ($v) use ($options) {
            if (is_scalar($v)) {
                $opt = $options->firstWhere('value', (string)$v);
                if ($opt) {
                    $label = $this->tr($opt->label, (string)$v);
                    return $label ?? $v;
                }
            }
            return $v;
        };

        // حالت‌های مختلف value
        if (is_scalar($value)) {
            return $mapScalar($value);
        }

        if (is_array($value)) {
            // لیست ساده [1,2,3]
            if (function_exists('array_is_list') && array_is_list($value)) {
                return array_map($mapScalar, $value);
            }

            // آرایه associative یا ساختار پیچیده (مثلاً {field1:..., field2:...})
            // همون رو برگردونیم، ویو تصمیم می‌گیره چطور نمایش بده
            return $value;
        }

        return $value;
    }


    /**
     * Build payload and persist in report_snapshots with sequence and is_latest handling.
     */
    public function buildAndStore(): ReportSnapshot
    {
        $payload = $this->build();
        $checksum = hash('sha256', json_encode($payload, JSON_UNESCAPED_UNICODE));

        return DB::transaction(function () use ($payload, $checksum) {

            // Compute next sequence for (report_id + scope)
            $lastSeq = ReportSnapshot::query()
                ->where('report_id', $this->report->id)
                ->where('scope', $this->scope)
                ->max('sequence') ?? 0;

            // Mark previous latest as false
            ReportSnapshot::query()
                ->where('report_id', $this->report->id)
                ->where('scope', $this->scope)
                ->where('is_latest', true)
                ->update(['is_latest' => false]);

            $snapshot = new ReportSnapshot([
                'report_id'            => $this->report->id,
                'scope'                => $this->scope,
                'questionnaire_code'   => data_get($this->report, 'questionnaire.code', 'vsme'),
                'questionnaire_version'=> data_get($this->report, 'questionnaire.version', 'v1'),
                'locale'               => $this->locale,
                'format_version'       => self::FORMAT_VERSION,
                'sequence'             => $lastSeq + 1,
                'is_latest'            => true,
                'payload_json'         => $payload,
                'checksum'             => $checksum,
                'generated_by'         => $this->generatedBy,
                'generated_at'         => now(),
            ]);

            $snapshot->save();

            return $snapshot;
        });
    }

    /* ====== Helpers you will implement next (private) ======
     * private function collectDisclosureBlocks(): array { ... }
     * private function collectAnswersForScope(): array { ... }
     * private function resolveLabels(array $questions): array { ... }
     * private function assemblePayload(...): array { ... }
     */
}
