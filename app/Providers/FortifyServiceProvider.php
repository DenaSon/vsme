<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Fortify::registerView(function () {
            return view('components.ui.auth.register', [
                'title' => 'Sign Up | ' . config('app.name'),
            ]);
        });

        Fortify::loginView(function () {
            return view('components.ui.auth.login', [
                'title' => 'Sign In | ' . config('app.name'),
            ]);
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('components.ui.auth.forgot-password', [
                'title' => 'Forgot Password | ' . config('app.name'),
            ]);
        });

        Fortify::resetPasswordView(function () {
            return view('components.ui.auth.reset-password', [
                'title' => 'Reset Password | ' . config('app.name'),
            ]);
        });

        Fortify::verifyEmailView(function () {
            return view('components.ui.auth.verify-email', [
                'title' => 'Verify Email | ' . config('app.name'),
            ]);
        });



        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });


        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinute(2)->by($request->ip());
        });



    }
}
