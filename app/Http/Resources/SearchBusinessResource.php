<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SearchBusinessResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name'        => $this->name,
            'id'          => $this->uuid,
            'cover_photo' => $this->cover_photo_url,
            'lat'         => $this->lat,
            'lng'         => $this->lng
        ];
    }
}
