<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangeAddressRequest;
use App\Http\Requests\User\ChangePassRequest;
use App\Http\Requests\User\ProfileRequest;
use App\Repositories\UserRepository;
use App\Transformers\User\UserTransformer;

class ProfileController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index()
    {
        $user = auth('user')->user();
        return responder()->success($user, UserTransformer::class)->respond();
    }

    public function update(ProfileRequest $request)
    {
        $user = auth('user')->user();
        $validatedData = $request->validated();
        $user = $this->userRepo->update($user->id, $validatedData);
        return responder()->success([
            'message' => 'Update Profile Success!',
            $user
        ], UserTransformer::class)->respond();
    }

    public function changePass(ChangePassRequest $request)
    {
        $user = auth('user')->user();
        $validatedData = $request->validated();
        $this->userRepo->update($user->id, $validatedData);
        return responder()->success(['message' => 'Update Password Success!'])->respond();
    }

    public function changeAddress(ChangeAddressRequest $request)
    {
        $user = auth('user')->user();
        $validatedData = $request->validated();
        $this->userRepo->update($user->id, $validatedData);
        return responder()->success(['message' => 'Update Address Success!'])->respond();
    }
}
