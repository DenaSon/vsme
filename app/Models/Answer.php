<?php

namespace App\Models;

use App\Casts\MixedJson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{

    protected $fillable = [
        'report_id',
        'question_key',
        'value',
        'skipped_reason',
        'not_applicable',
        'classified_omitted',
        'updated_by',

    ];

    protected $casts = [
        'value'              => MixedJson::class,
        'not_applicable'     => 'boolean',
        'classified_omitted' => 'boolean',

    ];

    /** Report that this answer belongs to. */
    public function report(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    /** User who last updated this answer (nullable). */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /** Scope: answers for a specific question key. */
    public function scopeForKey($query, string $key)
    {
        return $query->where('question_key', $key);
    }





}
