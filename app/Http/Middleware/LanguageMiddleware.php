<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = session('locale', 'ru');
        
        if ($request->has('lang')) {
            $lang = $request->get('lang');
            if (in_array($lang, ['ru', 'en', 'az'])) {
                $locale = $lang;
                session(['locale' => $locale]);
            }
        }
        
        App::setLocale($locale);
        
        return $next($request);
    }
}
