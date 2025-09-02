<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Question extends Model
{
    use SoftDeletes, HasTranslations;

    protected $fillable = [
        'disclosure_id', 'key', 'number', 'type',
        'title', 'help_official', 'help_friendly',
        'rules', 'visibility', 'order', 'is_active', 'meta',
    ];

    public array $translatable = ['title', 'help_official', 'help_friendly'];

    protected $casts = [
        'is_active'  => 'boolean',
        'rules'      => 'array',
        'visibility' => 'array',
        'meta'       => 'array',
    ];

    /** @return BelongsTo */
    public function disclosure(): BelongsTo
    {
        return $this->belongsTo(Disclosure::class);
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class)->orderBy('sort');
    }
}
