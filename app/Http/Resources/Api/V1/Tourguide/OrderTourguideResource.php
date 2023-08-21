<?php

namespace App\Http\Resources\Api\V1\Tourguide;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderTourguideResource extends JsonResource
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
            'tourguide'    => TourguideResource::make($this->resource),
            'status'       => $this->pivot->status,
            'agent_status' => $this->pivot->agent_status,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
