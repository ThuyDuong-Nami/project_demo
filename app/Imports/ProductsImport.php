<?php

namespace App\Imports;

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
            foreach (explode(',', $row['categories']) as $category){
                $product->categories()->attach($category);
            }
            return $product;
        }else{
            $product = Product::create($row);
            foreach (explode(',', $row['categories']) as $category){
                $product->categories()->attach($category);
            }
            return $product;
        }
    }

    public function uniqueBy()
    {
        return 'name';
    }
}
