<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Imports\ProductsImport;
use App\Models\Product;
use App\Transformers\Admin\ProductTransformer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $perPage = request()->input('perPage');
        $products = Product::paginate($perPage);
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

        $product = Product::create($validatedData);
        foreach ($images as $image) {
            $name = '/products/' . $product->id . Str::random(9) . '.' . $image->getClientOriginalExtension();
            Storage::disk('s3')->put($name, file_get_contents($image));
            $imageUrl = 'https://gumistore.s3-ap-southeast-1.amazonaws.com'.$name;
            $product->productImages()->create(['image' => $imageUrl]);
        }
        foreach ($request->input('categories') as $category) {
            $product->categories()->attach($category);
        }
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
        Storage::disk('s3')->delete($product->productImages);
        $product->productImages()->delete();

        $images = $request->file('image');
        $product->update($validatedData);

        foreach ($images as $image) {
            $name = '/products/' . $product->id . Str::random(9) . '.' . $image->getClientOriginalExtension();
            Storage::disk('s3')->put($name, file_get_contents($image));
            $imageUrl = 'https://gumistore.s3-ap-southeast-1.amazonaws.com'.$name;
            $product->productImages()->create(['image' => $imageUrl]);
        }
        foreach ($request->input('categories') as $category) {
            $product->categories()->attach($category);
        }
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
        Storage::disk('s3')->delete($product->productImages);
        $product->productImages()->delete();
        $product->delete();

        return responder()->success(['message' => 'Delete Success!'])->respond();
    }

    public function search()
    {
        $word = request()->input('word');
        $search = Product::where('name', 'like', '%' . $word . '%')
                            ->orWhere('price', 'like', '%' . $word . '%');
        return responder()->success($search, ProductTransformer::class)->respond();
    }

    public function import()
    {
        $file = request()->file('filePath');
        (new ProductsImport())->import($file);
        return responder()->success(['message' => 'Import or Update products success'])->respond();
    }
}
