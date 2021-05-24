<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Models\User;

class AuthController extends Controller
{
    public function index()
    {
        $admin = auth('user')->user();
        return responder()->success($admin)->respond();
    }

    public function login(LoginRequest $request)
    {
        $login_type = filter_var($request->input('username'),
            FILTER_VALIDATE_EMAIL )
            ? 'email'
            : 'username';

        $request->merge([
            $login_type => $request->input('username')
        ]);

        $validatedData = $request->only([$login_type, 'password']);

        if (! $token = auth('user')->attempt($validatedData)){
            return response()->json([
                'error' => 'Unauthorized',
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_at'   => auth('user')->factory()->getTTL() * 60,
            'user'        => auth('user')->user(),
        ]);
    }

    public function logout()
    {
        auth('user')->logout();
        return response()->json([
            'message' => 'Logout success!',
        ], 200);
    }

    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();
        $user = User::create($validatedData);
        return response()->json([
            'data'    => $user,
            'message' => 'User successfully registered!',
        ],201);
    }
}
