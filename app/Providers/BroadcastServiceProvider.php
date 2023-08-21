<?php

namespace App\Providers;

use App\Http\Middleware\PusherLogMiddleware;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        request()->hasHeader('X-Auth-Pusher-Api')
            ? Broadcast::routes(['middleware' => ['auth:sanctum', PusherLogMiddleware::class]])
            : Broadcast::routes();

        require base_path('routes/channels.php');
    }
}
