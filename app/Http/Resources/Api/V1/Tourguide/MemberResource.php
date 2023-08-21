<?php

namespace App\Http\Resources\Api\V1\Tourguide;

use App\Http\Resources\Api\V1\Agent\AgentResource;
use App\Http\Resources\Api\V1\Agent\TourguideResource;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\Agent;
use App\Models\Tourguide;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = match ($this->memberable->getMorphClass()) {
            User::class      => UserResource::make($this->memberable ?? $this->user),
            Tourguide::class => TourguideResource::make($this->memberable ?? $this->user),
            default          => AgentResource::make($this->memberable ?? $this->user),
        };

        return [
            'id'         => $this->id,
            'user'       => $user,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
