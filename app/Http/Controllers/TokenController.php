<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Models\User;

use Illuminate\Support\Str;

class TokenController extends Controller
{

    function createToken(){
        return Str::random(25);
    }

    function create(Request $request) {
        $user = User::updateOrCreate('user_id', $request->user["id"])->firstOrFail();
        $newKey = $this->createToken();
        $user->api_token = $newKey;
        $user->save();
        return ['token' => $newKey];
    }

    function refresh(Request $request) {
        $user = User::where('user_id', $request->user["id"])->firstOrFail();
        $newKey = $this->createToken();
        $user->api_token = $newKey;
        $user->save();
        return ['token' => $newKey];
    }
}
