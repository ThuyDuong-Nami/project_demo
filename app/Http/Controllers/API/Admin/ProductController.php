<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Transformers\Admin\ProductTransformer;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected $productRepo;

    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $perPage = request()->input('perPage');
        $products = $this->productRepo->paginate($perPage);
        return responder()->success($products, ProductTransformer::class)->respond();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Admin\ProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductRequest $request)
    {
        $validatedData = $request->except('image', 'categories');
        $images = $request->file('image');
        $product = $this->productRepo->store($validatedData);
        foreach ($images as $image) {
            $imageName = $image->store('public/products');
            $product->productImages()->create(['image' => Storage::url($imageName)]);
        }
        $product->categories()->attach($request->input('categories'));
        return responder()->success($product, ProductTransformer::class)->respond();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Product $product)
    {
        return responder()->success($product, ProductTransformer::class)->respond();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Admin\ProductRequest $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Product $product, ProductRequest $request)
    {
        $validatedData = $request->except(['image', 'categories']);

        $product->categories()->detach();
        Storage::delete('public/'.$product->productImages);
        $product->productImages()->delete();

        $images = $request->file('image');
        $product = $this->productRepo->update($product->id, $validatedData);

        foreach ($images as $image) {
            $imageName = $image->store('public/products');
            $product->productImages()->create(['image' => Storage::url($imageName)]);
        }
        $product->categories()->attach($request->input('categories'));

        return responder()->success($product, ProductTransformer::class)->respond();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product)
    {
        $product->categories()->detach();
        Storage::delete('public/'.$product->productImages);
        $product->productImages()->delete();
        $this->productRepo->destroy($product->id);

        return responder()->success(['message' => 'Delete Success!'])->respond();
    }

    public function search()
    {
        $search = $this->productRepo->search(request()->input('word'));
        return responder()->success($search, ProductTransformer::class)->respond();
    }
}
