<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use App\Models\User_info;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use App\Http\Requests\ProfileUpdateRequest;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SuperAdminController extends Controller
{
    public function dashboard(){
        
        $user = auth()->user();

        return view('super_admin_dashboard', compact('user'));
    }

    public function edit(Request $request): View
    {
        return view('superadmin.profile', [
            'user' => $request->user(),
        ]);
    }

    public function doctor(){

        $user = auth()->user();

        $users = User::where('role', 'doctor')->get();

        $doctors = Doctor::all();

        return view('superadmin.doctor', compact('users','user','doctors'));
        
    }

    /**
     * Update the user's profile information.
     */
    // public function update(ProfileUpdateRequest $request): RedirectResponse
    // {
    //     $request->user()->fill($request->validated());

    //     if ($request->user()->isDirty('email')) {
    //         $request->user()->email_verified_at = null;
    //     }

    //     $saved = $request->user()->save();

    //     if($saved){
    //         return Redirect::route('superadmin.profile.edit')->with('status', 'Profile Updated');
    //     }else{
    //         return Redirect::route('superadmin.profile.edit')->with('status', 'Profile not Updated');
    //     }
    // }
        public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,',
        ]);

        $updatedData = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
        ];

        // Check if any changes were made to the form data
        if ($this->hasChanges($user, $updatedData)) {

            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->email = $request->input('email');
    
            $user->save();

            return redirect()->back()->with('status1', 'Profile updated successfully.');
        } else {
            return redirect()->back()->with('status1', 'No changes were made.');
        
        }

    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status2', 'Password Updated');

    }

    public function updateDoctorInfo (Request $request) {

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'specialties' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'date' => 'required|date',
            'phone' => 'required',
            'email' => 'required|string|email|max:255',

        ]);

        $user = User::findOrFail($request->input('user_id'));
        $doctor = Doctor::where('account_id', $request->user_id)->first();

        if($user->first_name !== $request->input('first_name') || $user->last_name !== $request->input('last_name')
            || $doctor->specialties !== $request->input('specialties') || $doctor->address !== $request->input('address')
            || $user->email !== $request->input('email') || $doctor->birthdate !== $request->input('date') || $doctor->phone !== $request->input('phone')
        ){
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->email = $request->input('email');
            $doctor->specialties = $request->input('specialties');
            $doctor->address = $request->input('address');
            $doctor->birthdate = $request->input('date');
            $doctor->phone = $request->input('phone');

            $user->save();
            $doctor->save();

            return redirect()->route('superadmin.doctor')->with('status', 'User updated successfully.');
        } else {
            return redirect()->route('superadmin.doctor')->with('status', 'No changes were made to the user.');
        }


    }

    public function createDoctor(Request $request) {
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'specialties' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
            'date' => 'required|date',
            'phone' => 'required',
        ]);

        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->password),
            'role' => 'doctor',
        ]);

        $latestUser = User::latest()->first();

        $doctor = doctor::create([
            'account_id' => $latestUser->id,
            'specialties' => $request->input('specialties'),
            'phone' => $request->input('phone'),
            'birthdate' => $request->input('date'),
            'address' => $request->input('address'),
        ]);

        return back()->with('status', 'User Added');
    }
    
    public function superAdminLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function hasChanges($user, $updatedData)
    {
        foreach ($updatedData as $key => $value) {
            if ($user->{$key} !== $value) {
                return true;
            }
        }

        return false;
    }
}
