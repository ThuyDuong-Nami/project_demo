<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index()
    {
        $admin = auth('admin')->user();
        return responder()->success($admin)->respond();
    }

    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();

        if (! $token = auth('admin')->attempt($validatedData)){
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
            'expires_at'   => auth('admin')->factory()->getTTL() * 60,
            'admin'        => auth('admin')->user(),
        ]);
    }

    public function logout()
    {
        auth('admin')->logout();
        return response()->json([
            'message' => 'Logout success!',
        ], 200);
    }
}
