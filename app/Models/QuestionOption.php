<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class QuestionOption extends Model
{
    use SoftDeletes, HasTranslations;

    protected $fillable = [
        'question_id', 'kind', 'key', 'value',
        'label', 'description', 'extra',
        'sort', 'is_active',
    ];

    public $translatable = ['label', 'description'];

    protected $casts = [
        'is_active'   => 'boolean',
        'label'       => 'array',
        'description' => 'array',
        'extra'       => 'array',
    ];

    /** @return BelongsTo */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
