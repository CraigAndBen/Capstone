<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        $exact_role = $request->user()->role;
        if($request->user()->role !== $role){
            if($exact_role === 'user'){
                return redirect('/user/dashboard');
            } elseif ($exact_role === 'doctor'){
                return redirect('/doctor/dashboard');
            } elseif ($exact_role === 'nurse'){
                return redirect('/nurse/dashboard');
            } elseif ($exact_role === 'admin'){
                return redirect('/admin/dashboard');
            } elseif ($exact_role === 'super_admin'){
                return redirect('/super_admin/dashboard');
            } elseif ($exact_role === 'supply_officer'){
                return redirect('/supply_officer/dashboard');
            } elseif ($exact_role === 'staff'){
                return redirect('/staff/dashboard');
            } elseif ($exact_role === 'pharmacist'){
                return redirect('/pharmacist/dashboard');
            } elseif ($exact_role === 'cashier'){
                return redirect('/cashier/dashboard');
            } 
        }
        return $next($request);
    }
}
