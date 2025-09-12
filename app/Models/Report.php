<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Report extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'questionnaire_id',
        'year',
        'period_start',
        'period_end',
        'mode',
        'module_choice',
        'status',
        'current_key',
        'progress',
        'submitted_at',
        'snapshot_id',
        'created_by',
        'updated_by',
        'meta',
    ];


    protected $casts = [
        'progress'     => 'integer',
        'submitted_at' => 'datetime',
        'meta'         => 'array',
        'period_start' => 'date',
        'period_end'   => 'date',
    ];



    /** Company owner of this report. */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /** Questionnaire version this report is based on. */
    public function questionnaire(): BelongsTo
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /** Current answers for this report. */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

//    /** Optional final immutable snapshot (if you implement snapshots table). */
//    public function snapshot()
//    {
//        return $this->belongsTo(Snapshot::class, 'snapshot_id');
//    }

    /** Creator user (nullable). */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** Last updater user (nullable). */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /** Scope: only drafts. */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /** Scope: for a given year. */
    public function scopeForYear($query, int $year)
    {
        return $query->where('year', $year);
    }



    public function answerFor(string $questionKey): ?Answer
    {
        return $this->relationLoaded('answers')
            ? $this->answers->firstWhere('question_key', $questionKey)
            : $this->answers()->where('question_key', $questionKey)->first();
    }

    public function answerValue(string $questionKey, $default = null)
    {
        return $this->answerFor($questionKey)?->value ?? $default;
    }

    public function answerByRole(string $role, $default = null)
    {
        $q = \App\Models\Question::query()
            ->where('meta->role', $role)
            ->first();

        return $q
            ? $this->answerValue($q->key, $default)
            : $default;
    }

    public static function normalizeRowsWithUid($rows): array
    {
        if (!is_array($rows)) return [];
        return array_values(array_map(function ($row) {
            $row = is_array($row) ? $row : [];
            if (empty($row['_uid'])) $row['_uid'] = (string) Str::ulid();
            return $row;
        }, $rows));
    }

    public function rowsByRole(string $role): array
    {
        return self::normalizeRowsWithUid($this->answerByRole($role, []));
    }



}

