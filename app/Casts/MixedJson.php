<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class MixedJson implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return is_null($value) ? null : json_decode($value, true);
    }

    public function set($model, $key, $value, $attributes)
    {

        return json_encode($value);
    }
}
