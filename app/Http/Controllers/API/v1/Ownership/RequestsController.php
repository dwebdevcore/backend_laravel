<?php

namespace App\Http\Controllers\API\v1\Ownership;

use App\Http\Controllers\Controller;
use App\Http\Resources\OwnershipRequestResource;
use App\Models\OwnershipRequest;
use App\Ownership\OwnershipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestsController extends Controller
{
    /**
     * @param $businessId
     * @param Request $request
     * @param OwnershipService $service
     * @return OwnershipRequestResource
     */
    public function index($businessId, Request $request, OwnershipService $service) {
        $requests = $service->getUserOwnershipRequest(Auth::id(), $businessId);

        return new OwnershipRequestResource($requests);
    }

    /**
     * @param $businessId
     * @param Request $request
     * @param OwnershipService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store($businessId, Request $request, OwnershipService $service)
    {
        $response = $service->requestOwnership(
            Auth::id(),
            $businessId,
            $request->input('method'),
            $request->input('address'),
            $request->input('userInfo')
        );

        return response()->json($response);
    }
}
