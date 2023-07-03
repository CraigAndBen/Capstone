<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nurse extends Model
{
    protected $table = 'nurse';

    protected $guarded = [];

    use HasFactory;
}
