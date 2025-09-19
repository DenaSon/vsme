<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportSnapshot extends Model
{
    protected $fillable = [
        'report_id', 'scope', 'questionnaire_code', 'questionnaire_version',
        'locale', 'format_version', 'sequence', 'is_latest',
        'payload_json', 'checksum', 'generated_by', 'generated_at',
    ];


    protected $casts = [
        'is_latest'     => 'boolean',
        'format_version'=> 'integer',
        'sequence'      => 'integer',
        'payload_json'  => 'array',
        'generated_at'  => 'datetime',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }


}
