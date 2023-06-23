<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor_specialties extends Model
{
    protected $table = 'doctor_specialties';

    protected $guarded = [];
    
    use HasFactory;
}
