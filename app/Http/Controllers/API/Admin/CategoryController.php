<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Transformers\Admin\CategoryTransformer;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $perPage = request()->input('perPage');
        $categories = Category::paginate($perPage);
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
            $category = Category::create($categoryArr);
        }else {
            $category = Category::create($validatedData);
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
            $category->update($categoryArr);
        }else{
            $category->update($validatedData);
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
        $category->delete();
        return responder()->success(['message' => 'Delete Success!'])->respond();
    }

    public function search()
    {
        $word = request()->input('word');
        $search = Category::where('name', 'like', '%'.$word.'%');
        return responder()->success($search, CategoryTransformer::class)->respond();
    }
}
