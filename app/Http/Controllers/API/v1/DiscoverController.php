<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Rules\Uuid;
use App\Services\BusinessService;
use Illuminate\Http\Request;

class DiscoverController extends Controller
{
    /**
     * @param Request $request
     * @param BusinessService $businessService
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function index(Request $request, BusinessService $businessService)
    {
        $this->validate($request, [
            'business_id' => ['required', new Uuid]
        ]);

        $results = $businessService->similar($request->business_id);

        return
            response()->json($results);
    }
}