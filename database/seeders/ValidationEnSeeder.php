<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\TranslationLoader\LanguageLine;

class ValidationEnSeeder extends Seeder
{

    public function run(): void
    {
        $lines = [
            // Core rules you’re using now
            ['group' => 'validation', 'key' => 'required',   'text' => ['en' => 'The :attribute field is required.']],
            ['group' => 'validation', 'key' => 'string',     'text' => ['en' => 'The :attribute must be a string.']],
            ['group' => 'validation', 'key' => 'min.string', 'text' => ['en' => 'The :attribute must be at least :min characters.']],
            ['group' => 'validation', 'key' => 'max.string', 'text' => ['en' => 'The :attribute may not be greater than :max characters.']],

            // Optional but recommended: pretty attribute names
            ['group' => 'validation', 'key' => 'attributes.note', 'text' => ['en' => 'note']],
        ];

        foreach ($lines as $line) {
            LanguageLine::updateOrCreate(
                ['group' => $line['group'], 'key' => $line['key']],
                ['text' => $line['text']]
            );
        }
    }




}
