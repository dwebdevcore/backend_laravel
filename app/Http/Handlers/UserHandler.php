<?php

namespace App\Http\Handlers;

use App\Models\User;

class UserHandler
{
    public function create($userId, $request)
    {
        $user = User::uuid($userId);
        $user->update($request->except('id'));

    	return $user;
    }

}
