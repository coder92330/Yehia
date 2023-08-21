<?php

namespace App\Http\Resources\Api\V1\Agent;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
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
            "id"           => $this->id,
            "name"         => $this->name,
            "is_active"    => $this->is_active,
            "country_code" => $this->country_code,
            "created_at"   => $this->created_at,
            "updated_at"   => $this->updated_at,
        ];
    }
}
