<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use App\Transformers\Admin\UserTransformer;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $perPage = request()->input('perPage');
        $users = User::orderBy('updated_at', 'desc')->paginate($perPage);
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
            $name = '/avatar/' . $image->getClientOriginalName();
            Storage::disk('s3')->put($name, file_get_contents($image));
            $imageUrl = 'https://gumistore.s3-ap-southeast-1.amazonaws.com'.$name;
            $userArr = array_merge($validatedData, ['avatar' => $imageUrl]);
            $user = User::create($userArr);
        }else{
            $user = User::create($validatedData);
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
            $name = '/avatar/' . $image->getClientOriginalName();
            Storage::disk('s3')->put($name, file_get_contents($image));
            $imageUrl = 'https://gumistore.s3-ap-southeast-1.amazonaws.com'.$name;
            $userArr = array_merge($validatedData, ['avatar' => $imageUrl]);
            $user->update($userArr);
        }else{
            $user->update($validatedData);
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
        $user->delete();
        return responder()->success(['message' => 'Delete Success!'])->respond();
    }

    public function search()
    {
        $word = request()->input('word');
        $search = User::where('username', 'like', '%'.$word.'%')
                        ->orWhere('email', 'like', '%'.$word.'%');
        return responder()->success($search, UserTransformer::class)->respond();
    }
}
