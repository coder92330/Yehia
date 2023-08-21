<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\V1\Agent\CountryResource;
use App\Http\Resources\Api\V1\Agent\PhoneResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'sender_type' => $this->sender_type,
            'name'        => $this->name,
            'email'       => $this->email,
            'avatar'      => $this->avatar,
            'phone'       => PhoneResource::make($this->phone),
            'country'     => CountryResource::make($this->country),
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
