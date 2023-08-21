<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Iotronlab\FilamentMultiGuard\ContextServiceProvider;

class TourGuideServiceProvider extends ContextServiceProvider
{
    public static string $name = 'tour-guide';

    protected function bootLivewireComponents(): void
    {
        Filament::serving(function () {
            Filament::forContext('tour-guide', function () {
                Filament::registerRenderHook('user-menu.start', fn(): View => view('includes.chat', ['route' => 'tour-guide.pages.chat']));
                Filament::registerUserMenuItems([
                    'account' => UserMenuItem::make()->url(route('tour-guide.resources.profile.index')),
                    'settings' => UserMenuItem::make()->url(route('tour-guide.pages.settings'))->label('Settings')->icon('heroicon-o-cog'),
                    'logout' => UserMenuItem::make()->label('Log Out')->url(route('tour-guide.logout')),
                ]);
            });
        });
    }

    protected function componentRoutes(): callable
    {
        return function () {
            Route::name('pages.')->group(function (): void {
                foreach (Filament::getPages() as $page) {
                    Route::group([], $page::getRoutes());
                }
            });

            Route::name('resources.')->group(function (): void {
                foreach (Filament::getResources() as $resource) {
                    Route::group([], $resource::getRoutes());
                }
            });
        };
    }
}
