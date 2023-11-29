<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expiries extends Model
{

    protected $table = 'expiries';

    protected $fillable = [
        'item_name',
        'category',
        'stock',
        'brand',
        'exp_date',

    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'item_name', 'p_name');
    }
}
