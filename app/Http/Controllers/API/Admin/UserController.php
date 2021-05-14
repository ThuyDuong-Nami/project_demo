<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Transformers\Admin\UserTransformer;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $perPage = request()->input('perPage');
        $users = $this->userRepo->paginate($perPage);
        return responder()->success($users, UserTransformer::class)->respond();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\UserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRequest $request)
    {
        $validatedData = $request->validated();

        if ($image = $request->file('avatar')){
            $imageName = $image->store('public/avatar');
            $userArr = array_merge($validatedData, ['avatar' => Storage::url($imageName)]);
            $user = $this->userRepo->store($userArr);
        }else{
            $user = $this->userRepo->store($validatedData);
        }
        return responder()->success($user, UserTransformer::class)->respond();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return responder()->success($user, UserTransformer::class)->respond();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\User $user
     * @param  \App\Http\Requests\Admin\UserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request, User $user)
    {
        $validatedData = $request->validated();

        if ($image = $request->file('avatar')){
            $imageName = $image->store('public/avatar');
            $userArr = array_merge($validatedData, ['avatar' => Storage::url($imageName)]);
            $user = $this->userRepo->update($user->id, $userArr);
        }else{
            $user = $this->userRepo->update($user->id, $validatedData);
        }
        return responder()->success($user, UserTransformer::class)->respond();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $this->userRepo->destroy($user->id);
        return responder()->success(['message' => 'Delete Success!'])->respond();
    }

    public function search()
    {
        $search = $this->userRepo->search(request()->input('word'));
        return responder()->success($search, UserTransformer::class)->respond();
    }
}
