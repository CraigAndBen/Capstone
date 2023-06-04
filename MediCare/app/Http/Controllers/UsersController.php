<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{

    public function NurseDashboard(){
        
        return view('nurse.index');
    }

    public function DoctorDashboard(){
        
        return view('doctor.index');
    }

    public function AdminDashboard(){
        
        return view('admin.index');
    }

    public function SuperAdminDashboard(){
        
        return view('superadmin.index');
    }
}
