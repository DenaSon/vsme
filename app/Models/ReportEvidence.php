<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportEvidence extends Model
{

    protected $table = 'report_evidences';

    // Fillable fields for mass assignment
    protected $fillable = [
        'report_id',
        'question_key',
        'path',
        'original_name',
        'mime',
        'size',
    ];

}
