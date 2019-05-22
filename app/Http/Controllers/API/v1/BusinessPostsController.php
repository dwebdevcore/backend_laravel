<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Handlers\BusinessPostHandler;
use App\Http\Resources\BusinessPostResource;
use App\Models\Business;
use App\Models\BusinessPost;
use App\Rules\Uuid;
use Illuminate\Http\Request;

class BusinessPostsController extends Controller
{
    /**
     * @param Request $request
     * @param BusinessPostHandler $handler
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, BusinessPostHandler $handler) {
        $this->validate($request, [
            'business_id' => ['required', new Uuid],
            'photo'       => 'required|string',
            'text'        => 'sometimes|string',
            'expire_date' => 'sometimes|string'
        ]);

        $resource = new BusinessPostResource($handler->create($request->business_id, $request));

        return response()->json($resource, 201);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index(Request $request) {
        $this->validate($request, [
            'business_id' => ['required', new Uuid]
        ]);

        $businessPosts = BusinessPost::where('business_id', Business::uuid($request->business_id)->id)->paginate();
        return response()->json($businessPosts);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id) {
        $post = BusinessPost::uuid($id);
        return response()->json(new BusinessPostResource($post));
    }
}
