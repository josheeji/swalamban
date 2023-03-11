<?php

namespace App\Http\Middleware;

use App\Helper\SettingHelper;
use App\Models\SiteSetting;
use Closure;
use Illuminate\Support\Facades\Schema;

class LocaleSetterMiddleware
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
        if (session()->has('locale_code')) {
            app()->setLocale(session('locale_code'));
        } else {
            app()->setLocale('en');
        }
        return $next($request);
    }
}
