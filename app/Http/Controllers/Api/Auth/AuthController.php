<?php

namespace App\Http\Controllers\Api\Auth;


use App\Models\User;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\Auth\LoginResource;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    protected $auth;

    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'data' => $user,
            'message' => 'Registered Successfully'
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        try {
            if (!$token = $this->auth->attempt($request->only('email', 'password'))) {
                return response()->json([
                    'message' => 'Invalid Email and Password'
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Failed'
            ], 500);
        }
        $user = $request->user();
        $user->access_token = $token;

        return response()->json([
            'message' => 'Logged in successfully',
            'data' => [
                'auth_token' => $token
            ],
        ], 200);
    }

    public function logout()
    {
        $this->auth->invalidate($this->auth->getToken());
        return response()->json([
            'message' => 'Logged out successfully',
            'data' => []
        ], 200);
    }

    public function user(Request $request)
    {
        return response()->json([
            'message' => 'User Details',
            'data' => $request->user()
        ]);
    }
}