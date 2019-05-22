<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeedResource;
use App\Services\FeedService;

class BusinessFeedController extends Controller
{
    /**
     * @param FeedService $feedService
     * @param $businessId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Exception
     */
    public function index(FeedService $feedService, $businessId) {
        $feed = $feedService->forBusiness(
            $businessId
        );

        return FeedResource::collection($feed);
    }
}
