<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Transformers\Admin\ProductTransformer;
use App\Transformers\User\CategoryTransformer;
use App\Transformers\Admin\CategoryTransformer as SideBar;

class HomeController extends Controller
{
    public function index()
    {
//        $perPage = request()->input('perPage');
        $categories = Category::whereNull('parent_id')->withCount('products')->get();
        foreach($categories as $category) {
            if ( $category->subCategory ) {
                foreach( $category->subCategory as $sub) {
                    $category->products_count += $sub->products_count;
                }
            }
        }
        return responder()->success($categories, CategoryTransformer::class)->respond();
    }

    public function sidebar()
    {
        $categories = Category::whereNull('parent_id');
        return responder()->success($categories, SideBar::class)->respond();
    }

    public function productsCategory(Category $category)
    {
        $perPage = request()->input('perPage');
        $product = $category->products()->paginate($perPage);
        return responder()->success($product, ProductTransformer::class)->respond();
    }

    public function productDetail(Product $product)
    {
        return responder()->success($product, ProductTransformer::class)->respond();
    }
}
