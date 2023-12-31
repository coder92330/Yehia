<?php

namespace App\Http\Resources\Api\V1\Tourguide;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'id'                      => $this->id,
            'type'                    => $this->type,
            'notification'            => $this->data,
            'read_at'                 => $this->read_at,
            'un_readed_notifications' => auth('tourguide_api')->user()->unreadNotifications()->count(),
            'created_at'              => $this->created_at,
            'updated_at'              => $this->updated_at,
        ];
    }
}
