<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\StickerCategoryResource;
use App\Models\StickerCategory;
use Illuminate\Http\Request;

class StickerCategoriesController extends Controller
{
    public function index(Request $request) {
        $categories = StickerCategory::paginate();

        return response()->json(new StickerCategoryResource($categories));
    }
}
