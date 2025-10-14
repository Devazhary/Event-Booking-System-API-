<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\RegisterResource;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\LoginResource;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    //register
    public function register(RegisterRequest $request)
    {
        //validation
        $data = $request->validated();

        //create user
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        //return response
        return new RegisterResource($user);
    }

    //login
    public function login(LoginRequest $request)
    {
        //validation
        $data = $request->validated();
        //check user
        $user = User::where('email', $data['email'])->first();
        if (!$user && !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }
        //create token
        $token = $user->createToken('auth_token')->plainTextToken;
        //response
        return (new LoginResource($user))->additional([
            'token' => $token
        ]);
    }

    //logout
    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user && method_exists($user, 'currentAccessToken') && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }

    //user
    public function user(Request $request) {
        $user = $request->user();
        return new UserResource($user);
    }
}
