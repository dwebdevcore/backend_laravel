<?php

namespace App\Http\Handlers;

use App\Models\Business;
use App\Models\BusinessReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BusinessReviewHandler
{
    public function create($businessId, Request $request)
    {
        /** @var Business $business */
        $business = Business::uuid($businessId);

        $data = $request->except('photo');
        $data['user_id'] = auth()->id();

        /** @var BusinessReview $review */
        $review = $business->createReview($data);

        $photo = $request->photo;  // your base64 encoded

        if ($photo) {
            $parts = explode(',', $photo, 2);

            $photo = $parts[1] ?? $parts[0];

            $path = $businessId . '/' . str_random(10) . '.png';

            Storage::disk('s3:images')->put($path, base64_decode($photo));

            $review->createImage(['path' => $path]);
        }

        return $review;
    }
}
