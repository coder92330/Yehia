<?php

namespace App\Http\Resources\Api\V1\Tourguide;

use App\Http\Resources\Api\V1\Agent\AgentResource;
use App\Http\Resources\Api\V1\Agent\TourguideResource;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'sender'     => $this->getSender(),
            'message'    => $this->body,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function getSender()
    {
        return match ($this->sender->memberable_type) {
            Agent::class => $this->sender ? AgentResource::make($this->sender->memberable) : [],
            User::class  => $this->sender ? UserResource::make($this->sender->memberable) : [],
            default      => $this->sender ? TourguideResource::make($this->sender->memberable) : [],
        };
    }
}
