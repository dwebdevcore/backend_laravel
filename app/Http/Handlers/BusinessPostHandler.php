<?php

namespace App\Http\Handlers;

use App\Models\Business;
use Illuminate\Support\Facades\Storage;

class BusinessPostHandler
{
    public function create($businessId, $request)
    {
        $business        = Business::uuid($businessId);
        $data            = $request->except('photo');
        $data['user_id'] = auth()->id();

        $review          = $business->createPost($data);
        $photo           = $request->photo;

        if ($photo) {
            $photo = str_replace('data:image/png;base64,', '', $photo);
            $photo = str_replace(' ', '+', $photo);
            $path  = $businessId . '/' . str_random(10) . '.png';
            Storage::disk('local')->put($path, base64_decode($photo));

            $review->createImage(['path' => $path]);
        }

    	return $review;
    }

}
