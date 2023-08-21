<?php

namespace App\Http\Controllers\Api\V1\Tourguide;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Agent\AgentResource;
use App\Http\Resources\Api\V1\ErrorResource;
use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function show($id)
    {
        return ($agent = Agent::find($id))
            ? AgentResource::make($agent->load(['phones', 'country', 'company']))
            : ErrorResource::make(__('messages.no_data', ['attribute' => __('attributes.agent')]));
    }
}
