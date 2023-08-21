<?php

namespace App\Http\Resources\Api\V1\Agent;

use App\Http\Resources\Api\V1\MediaResource;
use App\Models\Event;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'id'            => $this->id,
            'name'          => $this->name,
            'description'   => $this->description,
            'meeting_point' => ['lat' => $this->lat, 'lng' => $this->lng],
            'start_at'      => $this->start_at,
            'end_at'        => $this->end_at,
            'cover'         => $this->cover,
            'days_type'     => $this->days_type,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'country'       => CountryResource::make($this->country),
            'city'          => CityResource::make($this->city),
            'agent'         => AgentResource::make($this->whenLoaded('agent')),
            'days'          => EventDaysResource::collection($this->whenLoaded('days')),
        ];
    }
}
