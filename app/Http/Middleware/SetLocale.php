<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Support\Facades\Cookie;


class SetLocale
{


    public function handle($request, Closure $next)
    {

        $supportedLocales = config('app.supported_locales');

        $locale = null;


        if (Auth::check() && Auth::user()->locale) {
            $locale = Auth::user()->locale;
        }

        $locale = $locale ?? session('locale');

        $locale = $locale ?? Cookie::get('locale');

        $locale = $locale ?? config('app.locale');


        if (!in_array($locale, $supportedLocales, true)) {
            $locale = config('app.locale');
        }

        app()->setLocale($locale);

        return $next($request);
    }


}
