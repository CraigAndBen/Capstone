<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $table = 'purchases';

    protected $fillable = [
        'product_id',
        'quantity',
        'price',

    ];
    public function product()
    {
        return $this->hasMany(Product::class, 'product_id', 'id');
    }
}