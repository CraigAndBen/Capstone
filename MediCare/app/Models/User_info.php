<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_info extends Model
{
    protected $table = 'user_info';
    
    protected $guarded = [];
    
    use HasFactory;
}
