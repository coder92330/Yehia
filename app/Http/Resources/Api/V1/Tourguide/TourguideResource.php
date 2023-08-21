<?php

namespace App\Http\Resources\Api\V1\Tourguide;

use Illuminate\Http\Resources\Json\JsonResource;

class TourguideResource extends JsonResource
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
            "id"                  => $this->id,
            'sender_type'         => $this->sender_type,
            'device_key'          => $this->device_key,
            "first_name"          => $this->first_name,
            "last_name"           => $this->last_name,
            "email"               => $this->email,
            "username"            => $this->username,
            "birthdate"           => $this->birthdate,
            "bio"                 => $this->bio,
            "age"                 => $this->age,
            "years_of_experience" => $this->years_of_experience,
            "is_active"           => $this->is_active,
            "is_online"           => $this->is_online,
            "last_active"         => $this->last_active,
            "facebook"            => $this->facebook,
            "twitter"             => $this->twitter,
            "instagram"           => $this->instagram,
            "linkedin"            => $this->linkedin,
            "avatar"              => $this->avatar,
            "address"             => $this->address,
            "gender"              => $this->gender,
            "education"           => $this->education,
            "phones"              => PhoneResource::collection($this->phones),
            'work_experiences'    => WorkExperienceResource::collection($this->work_experiences),
            "languages"           => LanguageResource::collection($this->languages),
            "certificates"        => CertificateResource::collection($this->certificates),
            "skills"              => SkillResource::collection($this->skills),
            "rates"               => RateResource::collection($this->rates),
            "created_at"          => $this->created_at,
            "updated_at"          => $this->updated_at,
        ];
    }
}
