<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Disclosure extends Model
{
    use SoftDeletes, HasTranslations;

    protected $fillable = [
        'questionnaire_id', 'module_id', 'code',
        'title', 'description', 'order', 'is_active',
        'is_applicable_by_default', 'meta',
    ];

    public $translatable = ['title', 'description'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_applicable_by_default' => 'boolean',
        'meta' => 'array',
    ];

    /** @return BelongsTo */
    public function questionnaire(): BelongsTo
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /** @return BelongsTo */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /** @return HasMany */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
