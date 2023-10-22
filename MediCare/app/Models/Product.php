<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'p_name',
        'category_id',
        'stock',
        'brand',
        'expiration',
        'description',
        'status',

    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function requests()
    {
        return $this->hasMany(Request_Form::class, 'product_id');
    }
    public function productPrice()
    {
        return $this->hasOne(Product_price::class);
    }
}