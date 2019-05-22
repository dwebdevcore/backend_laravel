<?php

namespace App\Http\Controllers\API\v1;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Twilio\Exceptions\ConfigurationException
     */
    public function store(Request $request) {
        $this->validate($request, [
            'email'     => ['required_with:password', 'email', 'unique:users'],
            'password'  => ['required_with:email'],
            'phone'     => ['required_without:email,password', 'unique:users']
        ]);

        $email    = $request->get('email');
        $password = $request->get('password');
        $phone    = $request->get('phone');
        $user     = new User();

        if ($email && $password) {
            $user->email = $email;
            $user->password = bcrypt($password);
            $user->save();
        }

        if ($phone) {
            $user->phone             = $phone;
            $user->verification_code = mt_rand(1000, 9999);
            $user->save();
            $user->sendVerificationCode();
        }

        $response = ['token' => $user->generateToken()];

        return response($response, 200);
    }
}
