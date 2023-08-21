<?php

namespace App\Providers;

use App\Http\Middleware\AgentStyleMiddleware;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Filament\Navigation\UserMenuItem;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Yepsua\Filament\Themes\Facades\FilamentThemes;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('firebase', function () {
            return new \App\Services\FirebaseNotification();
        });
        $loader = AliasLoader::getInstance();
        $loader->alias(FilamentThemes::class, \App\Services\FilamentThemes::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Filament::registerRenderHook('user-menu.start', fn(): View => view('includes.chat', ['route' => 'filament.pages.chat']));
        Filament::serving(function () {
            Filament::registerUserMenuItems([
                'account' => UserMenuItem::make()->url(route('filament.pages.profile')),
            ]);
            Filament::registerNavigationGroups([
                NavigationGroup::make()->label('Companies'),
                NavigationGroup::make()->label('TourGuides'),
                NavigationGroup::make()->label('Bookings'),
                NavigationGroup::make()->label('Settings'),
                NavigationGroup::make()->label('Website Pages'),
            ]);
        });
    }
}
