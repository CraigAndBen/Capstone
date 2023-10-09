<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_price extends Model
{
    use HasFactory;

    protected $table = 'product_price';
    protected $guarded = [
        'product_id',
        'price'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'id');
    }
}
