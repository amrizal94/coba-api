<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function userLogout()
    {
        if (!Auth::guard('api')->check()) {
            return response()->json(['error'=> 'Unauthorized'], 401);
        }

        $accessToken = Auth::guard('api')->user()->token();

            \DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update(['revoked'=> true]);
        $accessToken->revoke();

        $data = array(
            'message' => 'User logout successfully'
        );
        return response()->json($data, 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function loginUser(Request $request)
    {
        $input = $request->all();

        Auth::attempt($input);

        $user = Auth::user();

        $token = $user->createToken('example')->accessToken;
        $data = array(
            'status' => 200,
            'token' => $token
        );
        return response()->json($data, 200);

    }

    /**
     * Display the specified resource.
     */
    public function getUserDetail(User $user)
    {
        $guard = Auth::guard('api');

        if (!$guard->check()) {
            return response()->json(['error'=> 'Unauthorized'], 401);
        }

        $data = array(
            'data' => $guard->user()
        );
        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
