<?php

namespace App\Http\Resources\Api\V1\Agent;

use App\Http\Resources\Api\V1\MediaResource;
use App\Http\Resources\Api\V1\Tourguide\CountryResource;
use App\Http\Resources\Api\V1\Tourguide\PhoneResource;
use App\Models\Tourguide;
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
            'id'                  => $this->id,
            'sender_type'         => $this->sender_type,
            'device_key'          => $this->device_key,
            'first_name'          => $this->first_name,
            'last_name'           => $this->last_name,
            'email'               => $this->email,
            'username'            => $this->username,
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
            'avatar'              => $this->avatar,
            'address'             => $this->address,
            'gender'              => $this->gender,
            'education'           => $this->education,
            'avg_rates'           => $this->avg_rates,
            'rates_count'         => $this->rates_count,
            'is_favourite'        => $this->when(auth('agent_api')->check(), function () {
                return auth('agent_api')->user()->favourites()->where(['favouritable_id' => $this->id, 'favouritable_type' => Tourguide::class])->exists();
            }),
            'work_experiences'    => WorkExperienceResource::collection($this->work_experiences),
            'languages'           => LanguageResource::collection($this->languages),
            'skills'              => SkillResource::collection($this->skills),
            'certificates'        => CertificateResource::collection($this->certificates),
            'phones'              => PhoneResource::collection($this->phones),
            'agent_status'        => $this->whenPivotLoaded('order_tourguide', fn () => $this->pivot->agent_status),
            'status'              => $this->whenPivotLoaded('order_tourguide', fn () => $this->pivot->status),
            'created_at'          => $this->created_at,
            'updated_at'          => $this->updated_at,
        ];
    }
}
