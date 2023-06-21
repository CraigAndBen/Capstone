<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UsersController extends Controller
{

    public function NurseDashboard(){
        
        return view('nurse.index');
    }

    public function DoctorDashboard(){
        
        return view('admin_dashboard');
    }

    public function AdminDashboard(){
        
        return view('admin.index');
    }

    public function SuperAdminDashboard(){
        
        return view('super_admin_dashboard');
    }

    public function userLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
