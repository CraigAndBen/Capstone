<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\RedirectResponse;

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

    public function userAppointment(){
        
        return view('user.appointment');
    }

    public function userLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
