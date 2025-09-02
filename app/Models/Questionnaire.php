<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Questionnaire extends Model
{
    use SoftDeletes, HasTranslations;

    protected $fillable = [
        'code', 'version', 'title', 'description',
        'locale_default', 'is_active', 'published_at', 'archived_at', 'meta',
    ];

    public $translatable = ['title', 'description'];

    protected $casts = [
        'is_active' => 'boolean',
        'meta'      => 'array',
    ];

    /** @return HasMany */
    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    /** @return HasMany */
    public function disclosures(): HasMany
    {
        return $this->hasMany(Disclosure::class);
    }
}
