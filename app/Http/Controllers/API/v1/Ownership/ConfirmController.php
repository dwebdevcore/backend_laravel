<?php

namespace App\Http\Controllers\API\v1\Ownership;

use App\Http\Controllers\Controller;
use App\Ownership\OwnershipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfirmController extends Controller
{
    /**
     * @param $businessId
     * @param Request $request
     * @param OwnershipService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function index($businessId, Request $request, OwnershipService $service)
    {
        $response = $service->confirmOwnership(
            Auth::id(),
            $businessId,
            $request->input('token')
        );

        return response()->json($response);
    }
}
