<?php


namespace App\Repositories;

use App\Models\Category;

class CategoryRepository extends BaseRepository
{
    public function getModel()
    {
        return Category::class;
    }

    public function search($word)
    {
        return $this->model->where('name', 'like', '%' . $word . '%');
    }

//    User
    public function countProducts()
    {
        $categories = $this->model->whereNull('parent_id')->withCount('products')->get();
        foreach($categories as $category) {
            if ( $category->subCategory ) {
                foreach( $category->subCategory as $sub) {
                    $category->products_count += $sub->products_count;
                }
            }
        }
        return $categories;
    }

    public function getSubCategory()
    {
        return $this->model->whereNull('parent_id');
    }
}
