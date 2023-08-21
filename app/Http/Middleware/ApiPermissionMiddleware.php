<?php

namespace App\Http\Middleware;

use App\Http\Resources\Api\V1\ErrorResource;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class ApiPermissionMiddleware
{
    public function handle(Request $request, Closure $next, $apiPermission, $guard = null)
    {
        $permissions = is_array($apiPermission) ? $apiPermission : explode('|', $apiPermission);

        $guard = Str::contains($guard, '_api') ? explode('_', $guard)[0] : $guard;

        foreach ($permissions as $permission) {
            if (Permission::where(['name' => $permission , 'guard_name' => $guard])->exists()) {
                return $next($request);
            }
        }

        return ErrorResource::make(__('messages.unauthorized'), 403);
    }
}
