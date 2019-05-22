<?php

namespace App\Http\Controllers\API\v1;

use App\Services\BusinessService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BusinessResource;
use App\Rules\Lat;
use App\Rules\Lng;

class ExploreController extends Controller
{
    /**
     * @param Request $request
     * @param BusinessService $businessService
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function index(Request $request, BusinessService $businessService)
    {
        $this->validate($request, [
            'lat' => ['required', new Lat],
            'lng' => ['required', new Lng]
        ]);

        $businesses = $businessService->get(
            $request->get('lat'),
            $request->get('lng'),
            $request->get('query')
        );

        return BusinessResource::collection($businesses);
    }
}
