<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest;
use App\Models\Admin;
use App\Transformers\Admin\AdminTransformer;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $perPage = request()->input('perPage');
        $admins = Admin::orderBy('updated_at', 'desc')->paginate($perPage);
        return responder()->success($admins, AdminTransformer::class)->respond();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\AdminRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AdminRequest $request)
    {
        $validatedData = $request->validated();

        if ($image = $request->file('avatar')){
            $name = '/avatar/' . $image->getClientOriginalName();
            Storage::disk('s3')->put($name, file_get_contents($image));
            $imageUrl = 'https://gumistore.s3-ap-southeast-1.amazonaws.com'.$name;
            $adminArr = array_merge($validatedData, ['avatar' => $imageUrl]);
            $admin = Admin::create($adminArr);
        }else{
            $admin = Admin::create($validatedData);
        }
        return responder()->success($admin, AdminTransformer::class)->respond();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin $admin
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Admin $admin)
    {
        return responder()->success($admin, AdminTransformer::class)->respond();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Admin $admin
     * @param  \App\Http\Requests\Admin\AdminRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(AdminRequest $request, Admin $admin)
    {
        if ($request->input('password') == null) {
            $validatedData = $request->except('password');
        } else {
            $validatedData = $request->validated();
        }

        if ($image = $request->file('avatar')){
            $name = '/avatar/' . $image->getClientOriginalName();
            Storage::disk('s3')->put($name, file_get_contents($image));
            $imageUrl = 'https://gumistore.s3-ap-southeast-1.amazonaws.com'.$name;
            $adminArr = array_merge($validatedData, ['avatar' => $imageUrl]);
            $admin->update($adminArr);
        }else{
            $admin->update($validatedData);
        }
        return responder()->success($admin, AdminTransformer::class)->respond();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin $admin
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();
        return responder()->success(['message' => 'Delete Success!'])->respond();
    }

    public function search()
    {
        $word = request()->input('word');
        $search = Admin::where('name', 'like', '%'.$word.'%')
                        ->orWhere('username', 'like', '%'.$word.'%')
                        ->orWhere('email', 'like', '%'.$word.'%');
        return responder()->success($search, AdminTransformer::class)->respond();
    }
}
