<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase_detail extends Model
{
    use HasFactory;

    protected $table = 'purchase_details';

    protected $fillable = [
        'reference',
        'total_quantity',
        'total_price',
        'amount',
        'change'
    ];

    public function purchase()
{
    return $this->belongsTo(Purchase::class, 'reference', 'reference');
}
public function product()
{
    return $this->belongsTo(Product::class, 'product_id', 'id');
}
}
