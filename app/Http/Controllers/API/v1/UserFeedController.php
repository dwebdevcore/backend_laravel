<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserFeedResource;
use App\Services\FeedService;

class UserFeedController extends Controller
{
    /**
     * @param FeedService $feedService
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Exception
     */
    public function index(FeedService $feedService) {
        $feed = $feedService->forUser();

        return UserFeedResource::collection($feed);
    }
}
