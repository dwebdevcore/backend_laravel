<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessPost;
use App\Models\BusinessPostImage;
use Illuminate\Http\Request;

class BusinessCoverController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request) {
        $this->validate($request, [
            'id'          => 'required|integer',
        ]);

        $image        = BusinessPostImage::findOrFail($request->id);
        $businessPost = BusinessPost::findOrFail($image->business_post_id);
        $business     = Business::find($businessPost->business_id);

        $business->posts->each(function ($post) {
            $post->images->each(function ($image) {
                $image->cover = false;
                $image->save();
            });
        });

        $image->cover = true;
        $image->save();

        return response()->json(['status' => 'ok']);
    }
}
