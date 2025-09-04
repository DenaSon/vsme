<?php
// app/Http/Middleware/SetLocale.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;


class SetLocale
{


    public function handle($request, Closure $next)
    {

       // Log::info('Session:'. session()->get('locale'));

        app()->setLocale(session()->get('locale'));



        return $next($request);
    }


}
