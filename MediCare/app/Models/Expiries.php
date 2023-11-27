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
    
    use HasFactory;
}
