<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get locale from session or URL parameter, default to 'bn'
        $locale = $request->get('lang') ?? Session::get('locale', 'bn');
        
        // Validate locale
        $supportedLocales = ['en', 'bn'];
        if (!in_array($locale, $supportedLocales)) {
            $locale = 'bn'; // Default to Bengali
        }
        
        // Set the application locale
        App::setLocale($locale);
        
        // Store in session for future requests
        Session::put('locale', $locale);
        
        return $next($request);
    }
}
