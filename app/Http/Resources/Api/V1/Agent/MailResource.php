<?php

namespace App\Http\Resources\Api\V1\Agent;

use App\Http\Resources\Api\V1\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MailResource extends JsonResource
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
            'subject'      => $this->subject,
            'body'         => $this->body,
            'from'         => $this->from,
            'to'           => $this->to,
            'cc'           => $this->cc,
            'bcc'          => $this->bcc,
            'reply_to'     => $this->reply_to,
            'status'       => $this->status,
            'attachements' => MediaResource::collection($this->getMedia('mails')),
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
