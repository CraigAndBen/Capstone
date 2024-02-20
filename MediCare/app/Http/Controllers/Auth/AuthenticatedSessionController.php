<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use PhpParser\Node\Stmt\ElseIf_;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        $request->authenticate();

        $request->session()->regenerate();

        $url = '';

        if($request->user()->role === 'nurse'){
            $url = '/nurse/dashboard';
        } elseif ($request->user()->role === 'doctor'){
            $url = '/doctor/dashboard';
        } elseif ($request->user()->role === 'admin'){
            $url = '/doctor/dashboard';
        } elseif ($request->user()->role === 'super_admin'){
            $url = '/super_admin/dashboard';
        } elseif ($request->user()->role === 'user'){
            $url = '/user/dashboard';
        } elseif ($request->user()->role === 'supply_officer'){
            $url = '/supply_officer/dashboard';
        } elseif ($request->user()->role === 'pharmacist'){
            $url = '/pharmacist/dashboard';
        } elseif ($request->user()->role === 'staff'){
            $url = '/staff/dashboard';
        } elseif ($request->user()->role === 'cashier'){
            $url = '/cashier/dashboard';
        }
        
        return redirect()->intended($url);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
