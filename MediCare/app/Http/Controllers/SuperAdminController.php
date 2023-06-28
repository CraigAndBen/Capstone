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

    public function admin(){

        $user = auth()->user();

        $users = User::where('role', 'admin')->get();

        $admins = Admin::all();

        return view('superadmin.doctor', compact('users','user','admins'));
        
    }

    public function nurse(){

        $user = auth()->user();

        $users = User::where('role', 'nurse')->get();

        $nurse = Nurse::all();

        return view('superadmin.doctor', compact('users','user','admins'));
    }

    public function user(){

        $user = auth()->user();

        $users = User::where('role', 'admin')->get();

        $users_info = User_info::all();

        return view('superadmin.doctor', compact('users','user','users_info'));
        
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
        if ($this->userHasChanges($user, $updatedData)) {

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

    public function updateDoctorPassword (Request $request) {

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::findOrFail($request->input('user_id'));

        if (Hash::check($request->input('password'), $user->password)) {
            return redirect()->route('superadmin.doctor')->with('status', "Password doesn't change.");
        }

        if (Hash::check($request->input('current_password'), $user->password)) {

            $user->password = Hash::make($request->input('password'));

            $user->save();

            return redirect()->route('superadmin.doctor')->with('status', 'Password updated successfull.');
        }

        return redirect()->route('superadmin.doctor')->with('status', 'Current password is incorrect.');

    }

    public function updateDoctorStatus (Request $request) {
        
        $user = User::findOrFail($request->input('user_id'));

        if ($request->input('status') === 'active') {

            $user->status = 'inactive';
            $user->save();
            
            return redirect()->route('superadmin.doctor')->with('status', 'User status updated to inactive.');
        } else {

            $user->status = 'active';
            $user->save();
            
            return redirect()->route('superadmin.doctor')->with('status', 'User status updated to active.');
        }
    }

    public function updateDoctorInfo (Request $request) {

        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'required|numeric|gt:0',
            'gender' => 'required|string|max:255',
            'specialties' => 'required|string|max:255',
            'qualification' => 'required|string|max:255',
            'years_of_experience' => 'required|numeric|gt:0',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'birthdate' => 'required|date',
            'phone' => 'required',
        ]);

        $user = User::findOrFail($request->input('user_id'));
        $info = Doctor::where('account_id', $request->user_id)->first();

        $userUpdatedData = [
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
        ];

        $infoUpdatedData = [
            'age' => $request->input('age'),
            'gender' => $request->input('gender'),
            'birthdate' => $request->input('birthdate'),
            'specialties' => $request->input('specialties'),
            'qualification' => $request->input('qualification'),
            'years_of_experience' => $request->input('years_of_experience'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
        ];

        $userChange = $this->hasChanges($user, $userUpdatedData);
        $infoChange = $this->hasChanges($info, $infoUpdatedData);


        // Check if any changes were made to the form data
        if ($userChange == true || $infoChange == true)  {

            if ($request->input('email') !== $user->email){

                $request->validate([
                    'email' => 'required|string|email|max:255|unique:users,email,',
                ]);

                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $user->middle_name = $request->input('middle_name');
                $user->email = $request->input('email');
                $info->age = $request->input('age');
                $info->gender = $request->input('gender');
                $info->qualification = $request->input('qualification');
                $info->specialties = $request->input('specialties');
                $info->years_of_experience = $request->input('years_of_experience');
                $info->address = $request->input('address');
                $info->birthdate = $request->input('birthdate');
                $info->phone = $request->input('phone');
    
                $user->save();
                $info->save();
    
                return redirect()->back()->with('success', 'Profile updated successfully.');

            } else {

                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $user->middle_name = $request->input('middle_name');
                $user->email = $request->input('email');
                $info->age = $request->input('age');
                $info->gender = $request->input('gender');
                $info->qualification = $request->input('qualification');
                $info->specialties = $request->input('specialties');
                $info->years_of_experience = $request->input('years_of_experience');
                $info->address = $request->input('address');
                $info->birthdate = $request->input('birthdate');
                $info->phone = $request->input('phone');
    
                $user->save();
                $info->save();
    
                return redirect()->back()->with('success', 'Profile updated successfully.');
            }

        } else {
            return redirect()->back()->with('info', 'No changes were made.');
        
        }
   }

    public function createDoctor(Request $request) {
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'required|numeric|gt:0',
            'gender' => 'required|string|max:255',
            'specialties' => 'required|string|max:255',
            'qualification' => 'required|string|max:255',
            'years_of_experience' => 'required|numeric|gt:0',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
            'date' => 'required|date',
            'phone' => 'required',
        ]);

        $user = User::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->password),
            'role' => 'doctor',
        ]);

        $latestUser = User::latest()->first();

        $doctor = doctor::create([
            'account_id' => $latestUser->id,
            'age' => $request->input('age'),
            'gender' => $request->input('gender'),
            'specialties' => $request->input('specialties'),
            'qualification' => $request->input('qualification'),
            'years_of_experience' => $request->input('years_of_experience'),
            'phone' => $request->input('phone'),
            'birthdate' => $request->input('date'),
            'address' => $request->input('address'),
        ]);

        return back()->with('status', 'User added sucessfully.');
    }
    
    public function superAdminLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function hasChanges($info, $updatedData)
    {
        foreach ($updatedData as $key => $value) {

            if ($info->{$key} != $value) {

                $sample = $value;

                return $sample;

            }
        }

        return false;

    }
}
