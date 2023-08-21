<?php

namespace App\Http\Resources\Api\V1\Tourguide;

use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
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
            'slug'       => $this->slug,
            'full_url'   => url($this->full_mobile_url),
            'title'      => $this->title,
            'body'       => html_entity_decode(strip_tags($this->body)),
            'style'      => $this->style,
            'script'     => $this->script,
            'html'       => $this->when($this->body, fn () => view('pages.dynamic-pages', ['page' => $this])->render()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
