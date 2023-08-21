<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PusherLogMiddleware
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
        Log::channel('pusher')->info('Pusher Request', [
            'headers'    => $request->headers->all(),
            'content'    => $request->getContent(),
            'method'     => $request->method(),
            'url'        => $request->fullUrl(),
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user'       => $request->user() ? $request->user()->toArray() : 'guest',
            'all'        => $request->all(),
        ]);
        return $next($request);
    }
}
