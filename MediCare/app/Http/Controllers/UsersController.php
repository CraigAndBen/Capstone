<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UsersController extends Controller
{

    public function home()
    {
        $users = User::where('role', 'doctor')->get();
        $doctor = Doctor::all();
        $limitUser = $users->take(6);
        $limitDoctor = $doctor->take(6);
        return view('index', compact('limitDoctor','limitUser'));
    }

    public function dashboard()
    {
        $users = User::where('role', 'doctor')->get();
        $doctor = Doctor::all();
        $limitUser = $users->take(6);
        $limitDoctor = $doctor->take(6);
        return view('user_dashboard', compact('limitDoctor','limitUser'));
    }
    public function notification(){
        
        $user = Auth::user();
        $notifications = Notification::where('account_id', $user->id)->where('type','user')->orderBy('created_at','desc')->paginate(5);

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

    public function notificationDelete(Request $request)
    {
        $data = Notification::findOrFail($request->input('id'));
        $data->delete();

        // Redirect back to the original page or any other page as needed.
        return redirect()->route('user.notification')->with('success', 'Notification message deleted successfully.');
    }

    public function deleteNotification(Request $request)
    {
        $notification = Notification::where('id', $request->input('id'))->first();
        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted successfully');
    }

    public function deleteNotificationAll(Request $request)
    {
        $profile = auth()->user();
        $notifications = Notification::where('account_id',$profile->id)->get(); // Split the string into an array using a delimiter (e.g., comma)

        if($notifications->isEmpty()){
            return redirect()->back()->with('info', 'No notification to delete.');
            
        } else {

            foreach ($notifications as $notification) {
                $notification->delete();
            }

            return redirect()->back()->with('success', 'User deleted successfully');
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
