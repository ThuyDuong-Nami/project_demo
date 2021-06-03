<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class ProductsImport implements ToModel, WithHeadingRow, WithUpserts
{
    use Importable;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if ($product = Product::where('name', $row['name'])->first())
        {
            $product->categories()->detach();
            $product->update($row);
            foreach (explode(',', str_replace(' ', '', $row['categories'])) as $category){
                if ($categoryId = Category::where('name', $category)->first()){
                    $product->categories()->attach($categoryId->id);
                }else{
                    $image = 'https://gumistore.s3-ap-southeast-1.amazonaws.com/categories/poster.jpg';
                    $categoryArr = array_merge(['image' => $image], ['name' => $category]);
                    $category = Category::create($categoryArr);
                    $product->categories()->attach($category);
                }
            }
            return $product;
        }else{
            $product = Product::create($row);
            foreach (explode(',', str_replace(' ', '', $row['categories'])) as $category){
                if ($categoryId = Category::where('name', $category)->first()){
                    $product->categories()->attach($categoryId->id);
                }else{
                    $image = 'https://gumistore.s3-ap-southeast-1.amazonaws.com/categories/poster.jpg';
                    $categoryArr = array_merge(['image' => $image], ['name' => $category]);
                    $category = Category::create($categoryArr);
                    $product->categories()->attach($category);
                }
            }
            return $product;
        }
    }

    public function uniqueBy()
    {
        return 'name';
    }
}
