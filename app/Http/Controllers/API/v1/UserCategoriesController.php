<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\User;
use App\Rules\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserCategoriesController extends Controller
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $user = Auth::user();

        return
            CategoryResource::collection($user->categories);
    }

    /**
     * @param Request $request
     * @return CategoryResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id'     => ['required', 'exists:users,uuid', new Uuid],
            'category_id' => ['required', 'exists:categories,uuid', new Uuid],
        ]);

        $user     = User::uuid($request->get('user_id'));
        $category = Category::uuid($request->get('category_id'));
        $user->categories()->attach($category);

        return
            new CategoryResource($category);
    }
}
