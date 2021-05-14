<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Transformers\Admin\CategoryTransformer;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    protected $categoryRepo;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $perPage = request()->input('perPage');
        $categories = $this->categoryRepo->paginate($perPage);
        return responder()->success($categories, CategoryTransformer::class)->respond();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\CategoryRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CategoryRequest $request)
    {
        $validatedData = $request->validated();

        if ($image = $request->file('image')){
            $imageName = $image->store('public/categories');
            $categoryArr = array_merge($validatedData, ['image' => Storage::url($imageName)]);
            $category = $this->categoryRepo->store($categoryArr);
        }else {
            $category = $this->categoryRepo->store($validatedData);
        }
        return responder()->success($category, CategoryTransformer::class)->respond();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Category $category)
    {
        return responder()->success($category, CategoryTransformer::class)->respond();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Admin\CategoryRequest $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $validatedData = $request->validated();

        if ($image = $request->file('image')){
            $imageName = $image->store('public/categories');
            $categoryArr = array_merge($validatedData, ['image' => Storage::url($imageName)]);
            $category = $this->categoryRepo->update($category->id, $categoryArr);
        }else{
            $category = $this->categoryRepo->update($category->id, $validatedData);
        }
        return responder()->success($category, CategoryTransformer::class)->respond();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Category $category)
    {
        $this->categoryRepo->destroy($category->id);
        return responder()->success(['message' => 'Delete Success!'])->respond();
    }

    public function search()
    {
        $search = $this->categoryRepo->search(request()->input('word'));
        return responder()->success($search, CategoryTransformer::class)->respond();
    }
}
