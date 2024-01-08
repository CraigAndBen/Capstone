<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    
    protected $table = 'holidays';

    protected $guarded = [];

    use HasFactory;
}
