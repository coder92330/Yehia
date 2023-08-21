<?php

namespace App\Http\Resources\Api\V1\Tourguide;

use Illuminate\Http\Resources\Json\JsonResource;

class PhoneResource extends JsonResource
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
            "number"       => $this->number,
            "country_code" => $this->country_code,
            "type"         => $this->type,
            "is_primary"   => $this->is_primary,
            "is_active"    => $this->is_active,
            "created_at"   => $this->created_at,
            "updated_at"   => $this->updated_at,
        ];
    }
}
