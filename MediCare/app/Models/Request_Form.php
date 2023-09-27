<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request_Form extends Model
{
    use HasFactory;

    protected $table = 'requests';
    protected $fillable = [
        'name_requester',
        'department',
        'date',
        'product_id',
        'brand',
        'quantity',

    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}