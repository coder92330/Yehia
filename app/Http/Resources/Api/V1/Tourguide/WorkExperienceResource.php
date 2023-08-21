<?php

namespace App\Http\Resources\Api\V1\Tourguide;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkExperienceResource extends JsonResource
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
            'id'          => $this->id,
            'title'       => $this->title,
            'company'     => $this->company,
            'location'    => $this->location,
            'start_date'  => $this->start_date,
            'end_date'    => $this->end_date,
            'is_current'  => $this->is_current,
            'description' => $this->description,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
