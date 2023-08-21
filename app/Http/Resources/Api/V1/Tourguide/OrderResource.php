<?php

namespace App\Http\Resources\Api\V1\Tourguide;

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
            'id'           => $this->id,
            'event'        => EventResource::make($this->whenLoaded('event')),
            'status'       => $this->tourguides->where('id', auth('tourguide_api')->user()->id)->first()?->pivot->status,
            'agent_status' => $this->tourguides->where('id', auth('tourguide_api')->user()->id)->first()?->pivot->agent_status,
            'country'      => CountryResource::make($this->event?->country),
            'city'         => CityResource::make($this->event?->city),
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
