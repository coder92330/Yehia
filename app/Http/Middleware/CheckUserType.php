<?php

namespace App\Http\Middleware;

use App\Http\Resources\Api\V1\Agent\AgentResource;
use App\Http\Resources\Api\V1\Tourguide\TourguideResource;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\Agent;
use App\Models\Tourguide;
use App\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(Request): (Response|RedirectResponse) $next
     * @param string|null $guard
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $guard = null)
    {
        $request->merge(['userType' => match ($guard) {
            'agent' => [
                'type'      => 'agent',
                'guard'     => 'agent_api',
                'table'     => 'agents',
                'model'     => Agent::class,
                'resource'  => AgentResource::class,
                'relations' => ['country', 'company', 'phones'],
            ],
            'tourguide' => [
                'type'      => 'tourguide',
                'guard'     => 'tourguide_api',
                'table'     => 'tourguides',
                'model'     => Tourguide::class,
                'resource'  => TourguideResource::class,
                'relations' => ['country', 'phones'],
            ],
            default => [
                'type'      => 'admin',
                'guard'     => 'admin_api',
                'table'     => 'users',
                'model'     => User::class,
                'resource'  => UserResource::class,
                'relations' => ['country', 'company', 'phones'],
            ],
        }]);
        return $next($request);
    }
}
