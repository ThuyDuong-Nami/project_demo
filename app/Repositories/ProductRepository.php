<?php


namespace App\Repositories;

use App\Models\Product;

class ProductRepository extends BaseRepository
{
    public function getModel()
    {
        return Product::class;
    }

    public function search($word)
    {
        return $this->model
            ->where('name', 'like', '%'.$word.'%')
            ->orWhere('price', 'like', '%'.$word.'%');
    }
}
