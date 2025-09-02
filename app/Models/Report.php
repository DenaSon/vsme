<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}

