<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Resources\BusinessPostResource;
use App\Models\Business;
use App\Models\BusinessPost;
use App\Http\Controllers\Controller;
use App\Rules\Uuid;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActiveBusinessPostsController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index(Request $request) {
        $this->validate($request, [
            'business_id' => ['required', new Uuid]
        ]);

        $businessPosts = BusinessPost::where('business_id', Business::uuid($request->business_id)->id)
                            ->where(function ($query) {
                                $query->where('expire_date', '>=', Carbon::now())
                                    ->orWhereNull('expire_date');
                            })
                            ->paginate();

        return BusinessPostResource::collection($businessPosts);
    }
}
