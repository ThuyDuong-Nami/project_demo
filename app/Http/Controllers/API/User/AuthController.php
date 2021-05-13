<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index()
    {
        $admin = auth('user')->user();
        return responder()->success($admin)->respond();
    }

    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();

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
}
