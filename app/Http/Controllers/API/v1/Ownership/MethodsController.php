<?php

namespace App\Http\Controllers\API\v1\Ownership;

use App\Http\Controllers\Controller;
use App\Http\Decorators\OwnershipMethodsDecorator;
use App\Ownership\OwnershipService;

class MethodsController extends Controller
{
    /**
     * @OA\GET(
     *     path="/api/v1/ownership-methods/{businessId}",
     *     summary="Get business ownership methods.",
     *     @OA\Parameter(
     *         name="businessId",
     *         description="Business ID.",
     *         required=true,
     *         in="query"
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Ownership methods.",
     *     )
     * )
     * @param $businessId
     * @param OwnershipService $service
     * @param OwnershipMethodsDecorator $decorator
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($businessId, OwnershipService $service, OwnershipMethodsDecorator $decorator)
    {
        $methods = $service->getAvailableMethods($businessId);

        return response()->json($decorator->decorate($methods));
    }
}
