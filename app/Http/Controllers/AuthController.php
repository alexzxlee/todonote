<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Create/Signup a new user and generate a new API token.
     *
     * @param Request $request
     * @return void
     */
    public function register(Request $request)
    {
        $user = User::where('username', $request->username)->first();

        if ($user) {
            return response()->json(['status' => 'error', 'message' => 'That username is taken. Try another.'], 404);
        } else {
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'api_token' => Str::random(60)
            ]);

            return response()->json(['status' => 'success','user' => $user], 201);
        }
    }

    /**
     * User's login function. It generates an API token when user logs in.
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'That username is not found. Try another.'], 404);
        }

        if (Hash::check($request->password, $user->password)) {
            $user->update(['api_token' => Str::random(60)]);

            return response()->json(['status' => 'success', 'user' => $user], 202);
        }

        return response()->json(['status' => 'error', 'message' => 'Failed to login: Invalid credentials'], 498);
    }

    /**
     * User's logout function. It removes the API token when user logs out.
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        $user = User::where('api_token', $request->api_token)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Failed to logout: The user is not found or has already logged out.'
            ], 406);
        }

        $user->api_token = null;
        $user->save();

        return response()->json(['status' => 'success', 'message' => "You logged out $user->username."], 401);
    }
}
