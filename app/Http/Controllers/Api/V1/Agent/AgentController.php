<?php

namespace App\Http\Controllers\Api\V1\Agent;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Agent\AgentResource;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function staffAgents()
    {
        $agents = auth('agent_api')->user()->company->agents()
            ->whereHas('roles', fn($q) => $q->where([['name', 'user'], ['guard_name', 'agent']]))
            ->when(request()->has('per_page'), fn($q) => $q->paginate(config('app.pagination')), fn($q) => $q->get());
        return $agents->isNotEmpty()
            ? AgentResource::collection($agents)
            : AgentResource::collection($agents)->additional(['message' => __('messages.no_data', ['attribute' => __('attributes.agents')])]);
    }
}
