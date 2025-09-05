<?php


namespace App\Http\Controllers\Locale;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LanguageController
{
    public function switch(string $locale, Request $request)
    {
        $supported = config('app.supported_locales', ['en', 'fi']);
        $locale = strtolower(trim($locale));

        if (!in_array($locale, $supported, true)) {
            $locale = config('app.fallback_locale', 'en');
        }


        session()->put('locale', $locale);

        Cookie::queue('locale', $locale, 60 * 24 * 365);

        if ($request->user()) {
            $request->user()->forceFill(['locale' => $locale])->save();
        }

        app()->setLocale($locale);

        return back();
    }
}
