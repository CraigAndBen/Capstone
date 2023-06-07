<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function show(){
        
        $doctors = User::where('role', 'doctor')->get();

        return view('user.appointment', compact('doctors'));
    }
}
