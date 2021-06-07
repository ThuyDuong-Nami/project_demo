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
        $login_type = filter_var($request->input('username'),
            FILTER_VALIDATE_EMAIL )
            ? 'email'
            : 'username';

        $request->merge([
            $login_type => $request->input('username')
        ]);

        $validatedData = $request->only([$login_type, 'password']);

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
            'expires_in'   => auth('admin')->factory()->getTTL() * 60 * 24,
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
