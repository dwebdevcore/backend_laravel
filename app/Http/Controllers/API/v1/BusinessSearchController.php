<?php

namespace App\Http\Controllers\API\v1;

use App\Services\BusinessService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BusinessSearchController extends Controller
{
    /**
     * @param Request $request
     * @param BusinessService $businessService
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function index(Request $request, BusinessService $businessService)
    {
        $this->validate($request, [
            'query' => 'string|required'
        ]);

        return response()->json($businessService->suggest($request->get('query')));
    }
}
