<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Lcobucci\JWT\Parser;


class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User email",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="email"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User password",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Login successfully"),
     *     @OA\Response(
     *         response="422",
     *         description="Password missmatch"
     *     )
     *
     * )
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'email' => 'required|email',
            'password'   => 'required'
        ]);

        $user = User::byEmail($request->email);

        if (!$user->hasPassword($request->password)) {
            return response('Password mismatch', 422);
        }

        $response = ['token' => $user->generateToken()];

        return response($response, 200);
    }

    public function destroy(Request $request) {
        $value = $request->bearerToken();
        $id = (new Parser())->parse($value)->getHeader('jti');

        $request->user()->removeToken($id);

        return response('You have been successfully logged out', 200);
    }
}
