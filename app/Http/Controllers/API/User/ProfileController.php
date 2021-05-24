<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangeAddressRequest;
use App\Http\Requests\User\ChangePassRequest;
use App\Http\Requests\User\ProfileRequest;
use App\Transformers\User\UserTransformer;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth('user')->user();
        return responder()->success($user, UserTransformer::class)->respond();
    }

    public function update(ProfileRequest $request)
    {
        $user = auth('user')->user();
        $validatedData = $request->validated();
        $user->update($validatedData);
        return responder()->success($user, UserTransformer::class)->respond();
    }

    public function changePass(ChangePassRequest $request)
    {
        $user = auth('user')->user();
        $validatedData = $request->validated();
        $user->update($validatedData);
        return responder()->success(['message' => 'Update Password Success!'])->respond();
    }

    public function changeAddress(ChangeAddressRequest $request)
    {
        $user = auth('user')->user();
        $validatedData = $request->validated();
        $user->update($validatedData);
        return responder()->success(['message' => 'Update Address Success!'])->respond();
    }
}
