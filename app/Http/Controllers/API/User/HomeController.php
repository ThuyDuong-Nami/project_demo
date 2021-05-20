<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Transformers\Admin\ProductTransformer;
use App\Transformers\User\CategoryTransformer;
use App\Transformers\Admin\CategoryTransformer as SideBar;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class HomeController extends Controller
{
    public function index()
    {
        $perPage = request()->input('perPage');
        $categories = Category::whereNull('parent_id')->withCount('products')->paginate($perPage);
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
        if (!$perPage){
            $perPage = config('perPage.perPage');
        }
        $product = $category->products;
        if ($category->subCategory()){
            foreach ($category->subCategory as $sub){
                foreach ($sub->products as $subProduct){
                    $product->push($subProduct);
                }
            }
        }
        $product = $this->paginate($product, $perPage);
        return responder()->success($product, ProductTransformer::class)->respond();
    }

    public function productDetail(Product $product)
    {
        return responder()->success($product, ProductTransformer::class)->respond();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => 'page',
            ]
        );
    }

}
