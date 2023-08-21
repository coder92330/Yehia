<?php

namespace App\Http\Resources\Api\V1\Tourguide;

use App\Http\Resources\Api\V1\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsNotificationResource extends JsonResource
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
            'id'         => $this->id,
            'sender'     => UserResource::make($this->user),
            'title'      => $this->title,
            'body'       => $this->body,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
