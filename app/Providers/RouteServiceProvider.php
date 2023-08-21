<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            $apiMiddleware = config('versions.api_middleware', ['api', 'wantsJson']);
            $apiVersion    = strtolower(str_starts_with(strtolower(request()->segment(1)), 'v')
                ? request()->segment(1)
                : config('versions.current_version', 'v1'));

            // API Routes
            foreach (config("api_versions.versions.$apiVersion") as $name => $version) {
                Route::prefix(config("api_versions.prefix") . '/' . ($version['prefix'] ?? "api/$apiVersion"))
                    ->as("api." . ($version['as'] ?? $name) . '.')
                    ->middleware(isset($version['middleware'])
                        ? array_merge($apiMiddleware, (is_array($version['middleware']) ? $version['middleware'] : [$version['middleware']]))
                        : $apiMiddleware)
                    ->group(base_path("routes/api/$apiVersion/$name.php"));
            }

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
