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


    public function getTranslatedChoicesAttribute(): array
    {
        $locale = app()->getLocale();
        $choices = data_get($this->extra, 'choices', []);
        $choices = is_array($choices) ? $choices : [];

        return array_map(function ($c) use ($locale) {
            $label = $c['label'] ?? '';
            if (is_array($label)) {
                $label = $label[$locale] ?? $label['en'] ?? (array_values($label)[0] ?? '');
            }
            $c['label'] = (string) $label;
            return $c;
        }, $choices);
    }


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
