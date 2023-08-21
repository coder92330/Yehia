<?php

namespace App\Http\Resources\Api\V1\Tourguide;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
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
            'members'    => MemberResource::collection($this->members),
            'messages'   => MessageResource::make($this->messages()->latest()->first()),
            'event'      => EventResource::make($this->whenNotNull($this->whenLoaded('event'))),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
