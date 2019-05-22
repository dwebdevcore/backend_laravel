<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\MapPresetResource;
use App\Services\MapPresetService;

class MapPresetsController extends Controller
{
    /**
     * @param MapPresetService $mapPresetService
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(MapPresetService $mapPresetService)
    {
        $results = $mapPresetService->getActive();
        return MapPresetResource::collection($results);
    }
}
