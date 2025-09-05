<?php

namespace App\Livewire\Components\Locale;

use Illuminate\Support\Facades\Cookie;
use Livewire\Component;

class LanguageSwitcher extends Component
{
    public string $locale;

    public function mount(): void
    {
        // ترتیب: user DB > session > cookie > config
        $this->locale =
            auth()->user()->locale
            ?? session('locale')
            ?? Cookie::get('locale')
            ?? config('app.locale');

        // همین لحظه به اپلیکیشن هم ست کنیم
        app()->setLocale($this->locale);
    }

    public function setLocale(string $locale): void
    {
        $supported = config('app.supported_locales', ['en', 'fi']);
        $locale = strtolower(trim($locale));

        if (!in_array($locale, $supported, true)) {
            return;
        }


        session()->put('locale', $locale);


        Cookie::queue(cookie('locale', $locale, 60 * 24 * 365));

        if (auth()->check()) {
            auth()->user()->forceFill(['locale' => $locale])->save();
        }


        app()->setLocale($locale);

        $this->redirect(request()->fullUrl(), navigate: false);
    }


    public function render()
    {
        return view('livewire.components.locale.language-switcher', [
            'current' => app()->getLocale(),
        ]);
    }
}
