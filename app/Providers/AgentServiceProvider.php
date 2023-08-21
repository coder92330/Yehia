<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Iotronlab\FilamentMultiGuard\ContextServiceProvider;

class AgentServiceProvider extends ContextServiceProvider
{
    public static string $name = 'agent';

    protected function bootLivewireComponents(): void
    {
        Filament::serving(function () {
            Filament::forContext('agent', function () {
                Filament::registerRenderHook('user-menu.start', fn(): View => view('includes.chat', ['route' => 'agent.pages.chat']));
                Filament::registerUserMenuItems([
                    'logout' => UserMenuItem::make()->label('Log Out')->url(route('agent.logout')),
                    'settings' => UserMenuItem::make()->url(route('agent.pages.settings'))->label('Settings')->icon('heroicon-o-cog'),
                    'account' => UserMenuItem::make()->url(route('agent.pages.profile')),
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
