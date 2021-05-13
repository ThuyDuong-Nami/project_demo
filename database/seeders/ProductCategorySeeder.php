<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::factory()->count(10)->create();

        $products = Product::factory()->count(20)
                    ->has(ProductImage::factory()->count(3))
                    ->create();

        foreach ($products as $product){
            $product->categories()->attach($categories->random());
        }
        foreach ($categories as $category){
            $category->subCategory()->save($categories->random());
            $category->products()->attach($products->random());
        }
    }
}
