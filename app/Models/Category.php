<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'name',
        'parent_id',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->perPage = config('perPage.perPage');
    }

    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories', 'category_id', 'product_id');
    }

    public function subCategory()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id')->withCount('products');
    }
}
