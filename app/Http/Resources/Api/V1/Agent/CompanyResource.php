<?php

namespace App\Http\Resources\Api\V1\Agent;

use App\Http\Resources\Api\V1\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            "name"        => $this->name,
            "email"       => $this->email,
            "website"     => $this->website,
            "address"     => $this->address,
            "specialties" => $this->specialties,
            "description" => $this->description,
            "facebook"    => $this->facebook,
            "twitter"     => $this->twitter,
            "instagram"   => $this->instagram,
            "linkedin"    => $this->linkedin,
            "created_at"  => $this->created_at,
            "updated_at"  => $this->updated_at,
            "logo"        => $this->logo,
            "cover"       => $this->cover,
            "phones"      => PhoneResource::collection($this->phones),
//            "country"     => CountryResource::make($this->whenLoaded('country')),
        ];
    }
}
