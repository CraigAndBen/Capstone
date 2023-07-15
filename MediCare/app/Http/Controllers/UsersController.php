<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UsersController extends Controller
{
    public function notification(){
        
        $user = Auth::user();
        $notifications = Notification::where('account_id', $user->id)->get();

        return view('user.notification.notification', compact('notifications'));

    }

    public function notificationRead(Request $request){

        $notification = Notification::findOrFail($request->input('id'));

        if($notification->is_read == 0){
            $notification->is_read = 1;
            $notification->save();
    
            return redirect()->route('user.notification');
        } else {
            return redirect()->route('user.notification');
        }

    }
    public function userLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
