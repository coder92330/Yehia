<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Yepsua\Filament\Themes\Facades\FilamentThemes;

class TourguideStyleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        Filament::forContext('tour-guide', function () {
            if (auth('tourguide')->check() && $style = auth('tourguide')->user()->style) {
                config(['filament-themes.color_public_path' => "vendor/yepsua-filament-themes/css/$style->name.css"]);
            }
        });
        return $next($request);
    }
}
