<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Module extends Model
{
    use SoftDeletes, HasTranslations;

    protected $fillable = [
        'questionnaire_id', 'code', 'title', 'description', 'order', 'is_active', 'meta',
    ];

    public array $translatable = ['title', 'description'];

    protected $casts = [
        'is_active' => 'boolean',
        'meta'      => 'array',
    ];

    /** @return BelongsTo */
    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /** @return HasMany */
    public function disclosures(): HasMany
    {
        return $this->hasMany(Disclosure::class);
    }

}
