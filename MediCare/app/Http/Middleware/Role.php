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
        $role1 = $request->user()->role;

        if($request->user()->role === 'nurse' && $request->user()->role === $role){
            return redirect('/nurse/dashboard');
        } elseif ($request->user()->role === 'doctor' && $request->user()->role === $role){
            return redirect('/doctor/dashboard');
        } elseif ($request->user()->role === 'admin' && $request->user()->role === $role){
            return redirect('/admin/dashboard');
        } elseif ($request->user()->role === 'super_admin' && $request->user()->role === $role){
            return redirect('/super_admin/dashboard');
        } elseif ($request->user()->role === 'user' && $request->user()->role === $role){
            return redirect('/user/dashboard');
        }

        return $next($request);
    }
}
