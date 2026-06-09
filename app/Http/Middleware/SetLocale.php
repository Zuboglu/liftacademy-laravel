<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowed = ['tr', 'en', 'de', 'zh', 'az', 'ru', 'ar', 'ka'];
        $locale  = session('locale', config('app.locale', 'tr'));

        if (!in_array($locale, $allowed)) {
            $locale = 'tr';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
