<?php

namespace App\Transformers\Admin;

use App\Models\ProductImage;
use Flugg\Responder\Transformers\Transformer;

class ProductImageTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [
        'product' => ProductTransformer::class,
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
     * @param  \App\Models\ProductImage $productImage
     * @return array
     */
    public function transform(ProductImage $productImage)
    {
        return [
            'id' => (int) $productImage->id,
            'image' => $productImage->image,
        ];
    }
}
