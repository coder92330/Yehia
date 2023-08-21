<?php

namespace App\Http\Resources\Api\V1\Tourguide;

use App\Http\Resources\Api\V1\Agent\EventResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EventDaysResource extends JsonResource
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
            'id'          => $this->id,
            'start_at'    => $this->start_at,
            'end_at'      => $this->end_at,
            'description' => $this->description,
            'type'        => $this->type,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
            'event'       => EventResource::make($this->whenLoaded('event')),
        ];
    }
}
