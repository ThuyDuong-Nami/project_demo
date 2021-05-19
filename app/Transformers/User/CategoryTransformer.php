<?php

namespace App\Transformers\User;

use App\Models\Category;
use App\Transformers\Admin\ProductTransformer;
use Flugg\Responder\Transformers\Transformer;

class CategoryTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [
        'subCategory' => CategoryTransformer::class,
        'products' => ProductTransformer::class
    ];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [];

    /**
     * Transform the model.
     *
     * @param  \App\Models\Category $category
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'id' => $category->id,
            'image' => $category->image,
            'name' => $category->name,
            'parent_id' => $category->parent_id,
            'products_count' => $category->products_count,
        ];
    }
}
