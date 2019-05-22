<?php

namespace App\Http\Controllers\API\v1;

use App\Elastic\Rules\AggregationRule;
use App\Elastic\Rules\AttributesCountRule;
use App\Models\Business;
use App\Http\Controllers\Controller;
use App\Http\Resources\BusinessResource;
use App\Rules\Lat;
use App\Rules\LatLng;
use App\Rules\Lng;
use App\Rules\Uuid;
use App\Services\BusinessService;
use Elasticsearch\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BusinessesController extends Controller
{
    /**
     * @return mixed
     */
    public function geoJson() {
        $fileToDownload = last(explode("/", config('filesystems.geojson_path')));
        return Storage::download($fileToDownload);
    }

    /**
     * @param Request $request
     * @param Client $elasticClient
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
    public function stats(Request $request, Client $elasticClient) {
        $this->validate($request, [
            'top_left'     => ['required', new LatLng],
            'bottom_right' => ['required', new LatLng]
        ]);

        $topLeft     = $request->get('top_left');
        $bottomRight = $request->get('bottom_right');

        if ($topLeft['lat'] <= $bottomRight['lat']) {
            return response()->json([
                'message' => 'The given data is invalid'
            ], 422);
        }

        $response = $elasticClient->search(AggregationRule::buildRule($topLeft, $bottomRight));
        $response = $response['aggregations'];

        $attributes = $elasticClient->search(AttributesCountRule::buildRule($topLeft, $bottomRight));

        return response()->json([
            'totalBusinesses' => $response['total_businesses']['value'],
            'totalImages'     => $response['total_images']['value'],
            'totalReviews'    => $response['total_reviews']['value'],
            'attributes'      => view('partials.attributes', ['attributes' => $attributes['aggregations']])->render()
        ]);
    }

    /**
     * @param Request $request
     * @param BusinessService $businessService
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function index(Request $request, BusinessService $businessService)
    {
        $this->validate($request, [
            'map_preset_id' => ['sometimes', new Uuid],
            'category_id'   => [new Uuid],
            'lat'           => ['numeric', 'required_with:lng', new Lat],
            'lng'           => ['numeric', 'required_with:lat', new Lng]
        ]);

        $businesses = $businessService->get(
            $request->get('lat'),
            $request->get('lng'),
            $request->get('query'),
            $request->get('category_id'),
            $request->get('map_preset_id')
        );

        return BusinessResource::collection($businesses);
    }

    /**
     * @param $id
     * @return BusinessResource
     */
    public function show($id)
    {
        $business = Business::uuid($id);

        return new BusinessResource($business);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request) {
        $this->validate($request, [
            'name' => ['required'],
            'lat'  => ['required', new Lat],
            'lng'  => ['required', new Lng]
        ]);

        $data            = $request->all();
        $data['user_id'] = Auth::user()->id;
        $business        = Business::create($data);

        return response()->json(new BusinessResource($business), 201);
    }
}
