<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function dashboard(){
        
        $user = auth()->user();

        return view('admin_dashboard', compact('user'));
    }
}
