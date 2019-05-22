<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BusinessResource;
use App\Models\Business;
use App\Models\User;
use App\Rules\Uuid;
use Illuminate\Http\Request;

class UserBusinessesController extends Controller
{
    /**
     * @param Request $request
     * @return BusinessResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id'     => ['required', 'exists:users,uuid', new Uuid],
            'business_id' => ['required', 'exists:businesses,uuid', new Uuid],
        ]);

        $user     = User::uuid($request->get('user_id'));
        $business = Business::uuid($request->get('business_id'));
        $user->businesses()->attach($business);

        return new BusinessResource($business);
    }

    /**
     * @param Request $request
     * @return BusinessResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delete(Request $request)
    {
        $this->validate($request, [
            'user_id'     => ['required', 'exists:users,uuid', new Uuid],
            'business_id' => ['required', 'exists:businesses,uuid', new Uuid],
        ]);

        $user     = User::uuid($request->get('user_id'));
        $business = Business::uuid($request->get('business_id'));
        $user->businesses()->detach($business);

        return new BusinessResource($business);
    }
}
