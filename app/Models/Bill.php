<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_code',
        'user_id',
        'total',
        'address',
        'phone',
        'status'
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'bill_details', 'bill_id', 'product_id')
                    ->withPivot('quantity', 'price');

    }
}
