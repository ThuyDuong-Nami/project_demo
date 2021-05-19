<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\CategoryRepository;
use App\Transformers\Admin\ProductTransformer;
use App\Transformers\User\CategoryTransformer;
use App\Transformers\Admin\CategoryTransformer as SideBar;

class HomeController extends Controller
{
    protected $categoryRepo;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function index()
    {
//        $perPage = request()->input('perPage');
        $categories = $this->categoryRepo->countProducts();
        return responder()->success($categories, CategoryTransformer::class)->respond();
    }

    public function sidebar()
    {
        $categories = $this->categoryRepo->getSubCategory();
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
