<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nurse extends Model
{
    protected $table = 'nurses';

    protected $guarded = [];

    use HasFactory;
}
