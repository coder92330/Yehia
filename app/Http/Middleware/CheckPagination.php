<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPagination
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  \Closure(Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        config(['app.pagination' => $request->query('per_page') ?? config('app.pagination')]);
        return $next($request);
    }
}
