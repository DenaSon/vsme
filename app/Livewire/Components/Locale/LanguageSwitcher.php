<?php

namespace App\Livewire\Components\Locale;

use Livewire\Component;
use Illuminate\Support\Facades\Cookie;

class LanguageSwitcher extends Component
{
    public string $locale;

    public function mount()
    {

        $this->locale = app()->getLocale();
    }

    public function setLocale(string $locale): void
    {
        $supported = config('app.supported_locales', ['en','fi']);
        $locale = strtolower(trim($locale));

        if (! in_array($locale, $supported, true)) {
            return;
        }

        session()->put('locale', $locale);
        Cookie::queue(cookie('locale', $locale, 60*24*365)); // 1 سال (اختیاری)

        if (auth()->check()) {
            auth()->user()->forceFill(['preferred_locale' => $locale])->save();
        }


        $this->redirect(request()->fullUrl(), navigate: false);
    }

    public function render()
    {
        return view('livewire.components.locale.language-switcher', [
            'current' => app()->getLocale(),
        ]);
    }
}
