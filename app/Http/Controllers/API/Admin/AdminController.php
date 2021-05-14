<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest;
use App\Models\Admin;
use App\Repositories\AdminRepository;
use App\Transformers\Admin\AdminTransformer;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    protected $adminRepo;

    public function __construct(AdminRepository $adminRepo)
    {
        $this->adminRepo = $adminRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $perPage = request()->input('perPage');
        $admins = $this->adminRepo->paginate($perPage);
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
            $imageName = $image->store('public/avatar');
            $adminArr = array_merge($validatedData, ['avatar' => Storage::url($imageName)]);
            $admin = $this->adminRepo->store($adminArr);
        }else{
            $admin = $this->adminRepo->store($validatedData);
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
        $validatedData = $request->validated();

        if ($image = $request->file('avatar')){
            $imageName = $image->store('public/avatar');
            $adminArr = array_merge($validatedData, ['avatar' => Storage::url($imageName)]);
            $admin = $this->adminRepo->update($admin->id, $adminArr);
        }else{
            $admin = $this->adminRepo->update($admin->id, $validatedData);
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
        $this->adminRepo->destroy($admin->id);
        return responder()->success(['message' => 'Delete Success!'])->respond();
    }

    public function search()
    {
        $search = $this->adminRepo->search(request()->input('word'));
        return responder()->success($search, AdminTransformer::class)->respond();
    }
}
