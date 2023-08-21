<?php

namespace App\Http\Resources\Api\V1\Agent;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'event'      => EventResource::make($this->whenLoaded('event')),
            'tourguides' => TourguideResource::collection($this->tourguides),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
