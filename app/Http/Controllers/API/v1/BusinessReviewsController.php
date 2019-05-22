<?php

namespace App\Http\Controllers\API\v1;

use App\Rules\Uuid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Handlers\BusinessReviewHandler;
use App\Http\Resources\BusinessReviewResource;

class BusinessReviewsController extends Controller
{
    public function store(Request $request, BusinessReviewHandler $reviewHandler) {
        $this->validate($request, [
            'business_id' => ['required', new Uuid],
            'code'        => 'required|integer',
            'comment'     => 'sometimes|string',
            'photo'       => 'sometimes|string'
        ]);

        $businessId = $request->business_id;
        $transformedReview = new BusinessReviewResource($reviewHandler->create($businessId, $request));

        return response($transformedReview, 201);
    }

}
