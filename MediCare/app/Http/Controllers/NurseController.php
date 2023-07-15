<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Nurse;
use App\Models\Patient;
use Illuminate\View\View;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use App\Http\Requests\ProfileUpdateRequest;

class NurseController extends Controller
{
    public function dashboard()
    {
        $profile = auth()->user();
        $notifications = Notification::where('specialties',$profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();

        return view('nurse_dashboard', compact('profile','limitNotifications','count'));
    }

    /**
     * Update the user's profile information.
     */
    public function profile(Request $request): View
    {

        $profile = $request->user();
        $notifications = Notification::where('specialties',$profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();

        return view('nurse.profile.profile', compact('profile','limitNotifications','count'));
    }

    public function passwordProfile(Request $request): View
    {
        $profile = $request->user();
        $notifications = Notification::where('specialties',$profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();

        return view('nurse.profile.profile_password', compact('profile','limitNotifications','count'));
    }

    /**
     * Update the user's profile information.
     */
    public function profileUpdate(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);
        $user = $request->user();

        $userUpdatedData = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
        ];

        $userChange = $this->hasChanges($user, $userUpdatedData);

        if ($userChange) {

            if ($user->email != $request->input('email')) {

                $request->validate([
                    'email' => 'required|string|email|unique:users|max:255',
                ]);

                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $user->email = $request->input('email');
                $saved = $user->save();

                if ($saved) {
                    return redirect()->back()->with('success', 'Profile updated successfully.');
                } else {
                    return redirect()->back()->with('info', 'Profile not updated successfully.');
                }

            } else {

                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $saved = $user->save();

                if ($saved) {
                    return redirect()->back()->with('success', 'Profile updated successfully.');
                } else {
                    return redirect()->back()->with('info', 'Profile not updated successfully.');
                }
            }

        } else {
            return redirect()->back()->with('info', 'No changes were made.');
        }

    }

    /**
     * Delete the user's account.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->input('current_password'), $user->password)) {

            return redirect()->route('user.profile.password')->with('info', 'Current password is incorrect.');

        } else {

            if (Hash::check($request->input('password'), $user->password)) {

                return redirect()->route('user.profile.password')->with('info', "Password doesn't change.");
            }

            $user->password = Hash::make($request->input('password'));

            $user->save();

            return redirect()->route('user.profile.password')->with('success', 'Password updated successfull.');
        }
    }

    public function patientList ()
    {
        $profile = auth()->user();
        $notifications = Notification::where('specialties',$profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = User::where('role', 'doctor')->get();
        $patients = Patient::all();

        return view('nurse.patient.patient', compact('patients', 'profile', 'doctors','limitNotifications','count'));
    }

    public function patientUpdate(Request $request) 
    {

        $patient = Patient::where('id', $request->id)->first();

            $patientUpdatedData = [
                'medical_condition' => $request->input('medical_condition'),
                'diagnosis' => $request->input('diagnosis'),
                'medication' => $request->input('medication'),
            ];
    
            $patientChange = $this->hasChanges($patient, $patientUpdatedData);
    
            if($patientChange) {
                $patient->medical_condition = $request->input('medical_condition');
                $patient->diagnosis = $request->input('diagnosis');
                $patient->medication = $request->input('medication');
    
                $patient->save();
    
                return redirect()->back()->with('success', 'Patient Information Updated Successfully.');
            } else {
                return redirect()->back()->with('info', 'No changes were made.');
            }
    }

    public function notification(){
        
        $profile = Auth::user();
        $notifications = Notification::where('specialties',$profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();

        return view('nurse.notification.notification', compact('profile','notifications','limitNotifications','count'));

    }

    public function notificationRead(Request $request){

        $notification = Notification::findOrFail($request->input('id'));

        if($notification->is_read == 0){
            $notification->is_read = 1;
            $notification->save();
    
            return redirect()->route('nurse.notification');
        } else {
            return redirect()->route('nurse.notification');
        }

    }

    private function hasChanges($info, $updatedData){
        foreach ($updatedData as $key => $value) {

            if ($info->{$key} != $value) {

                return $value;
            }
        }

        return false;
    }

    public function nurseLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

}
