<?php

namespace App\Services;

use Closure;
use Filament\Facades\Filament;

class FilamentThemes
{

    /**
     * Register the theme using the Filament facade
     *
     * @param Closure|null $closure
     * @return void
     */
    public static function register(Closure $closure = null): void
    {
        Filament::serving(function () use ($closure) {

            // For Filament Admin
            $assetColorPath = config('filament-themes.color_public_path', 'vendor/yepsua-filament-themes/css/amber.css');
            if (auth()->check() && $style = auth()->user()->style) {
                $assetColorPath = "vendor/yepsua-filament-themes/css/$style->name.css";
            }
            Filament::registerStyles([asset($assetColorPath)]);
            Filament::registerTheme(self::generateAsset(config('filament-themes.theme_public_path', 'css/app.css'), $closure));

            // For Filament Agent
            Filament::forContext('agent', function () use (&$closure) {
                if (auth('agent')->check() && $style = auth('agent')->user()->style) {
                    $assetColorPath = "vendor/yepsua-filament-themes/css/$style->name.css";
                    Filament::registerStyles([asset($assetColorPath)]);
                    Filament::registerTheme(self::generateAsset(config('filament-themes.theme_public_path', 'css/app.css'), $closure));
                }
            });

            // For Filament Tourguide
            Filament::forContext('tour-guide', function () use (&$closure) {
                if (auth('tourguide')->check() && $style = auth('tourguide')->user()->style) {
                    $assetColorPath = "vendor/yepsua-filament-themes/css/$style->name.css";
                    Filament::registerStyles([asset($assetColorPath)]);
                    Filament::registerTheme(self::generateAsset(config('filament-themes.theme_public_path', 'css/app.css'), $closure));
                }
            });
        });
    }

    /**
     * Get the asset using the asset or any other callable function
     *
     * @param string $path
     * @param Closure|null $closure
     * @return string
     */
    protected static function generateAsset(string $path, Closure $closure = null): string
    {
        if (!$closure) {
            if (config('filament-themes.enable_vite', false)) {
                return app(\Illuminate\Foundation\Vite::class)('resources/' . $path);
            }

            return app(\Illuminate\Foundation\Mix::class)($path);
        }

        return $closure($path);
    }
}
