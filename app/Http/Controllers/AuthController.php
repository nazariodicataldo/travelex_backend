<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        event(new Registered($user));

        return $this->apiResponse(true, 'Registration successful', 201);
    }

    //return $this->apiResponse(false, 'Invalid credentials', 422);
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return $this->apiResponse(false, 'Invalid credentials', 422);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->apiResponse(true, [
            'user' => new UserResource($user),
            'token' => $token,
        ]);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();

        return $this->apiResponse(true, null, 204, 'Logout successful');
    }
}
