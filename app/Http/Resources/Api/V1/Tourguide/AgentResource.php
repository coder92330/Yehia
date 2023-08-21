<?php

namespace App\Http\Resources\Api\V1\Tourguide;

use App\Http\Resources\Api\V1\Agent\CompanyResource;
use App\Http\Resources\Api\V1\Agent\CountryResource;
use App\Http\Resources\Api\V1\Agent\PhoneResource;
use App\Http\Resources\Api\V1\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AgentResource extends JsonResource
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
            'id'                  => $this->id,
            'sender_type'         => $this->sender_type,
            'device_key'          => $this->device_key,
            'first_name'          => $this->first_name,
            'last_name'           => $this->last_name,
            'email'               => $this->email,
            'username'            => $this->username,
            "birthdate"           => $this->birthdate,
            "education"           => $this->education,
            "age"                 => $this->age,
            "years_of_experience" => $this->years_of_experience,
            "is_active"           => $this->is_active,
            "is_online"           => $this->is_online,
            "last_active"         => $this->last_active,
            "facebook"            => $this->facebook,
            "twitter"             => $this->twitter,
            "instagram"           => $this->instagram,
            "linkedin"            => $this->linkedin,
            'avatar'              => $this->avatar,
            'roles'               => !empty($this->roles) ? $this->roles->pluck('name')->toArray() : [],
            'country'             => CountryResource::make($this->whenLoaded('country')),
            'company'             => CompanyResource::make($this->whenLoaded('company')),
            'phones'              => PhoneResource::collection($this->whenLoaded('phones')),
            'created_at'          => $this->created_at,
            'updated_at'          => $this->updated_at,
        ];
    }
}
