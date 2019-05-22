<?php

namespace App\Http\Resources;

class BusinessReviewResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(
            parent::toArray($request),
            ['images' => ReviewImageResource::collection($this->images)]
        );
    }
}
