<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Nurse;
use App\Models\Doctor;
use App\Models\User_info;
use Illuminate\View\View;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use App\Http\Requests\ProfileUpdateRequest;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SuperAdminController extends Controller
{

    // Super Admin
    public function dashboard()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type',$profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();

        return view('super_admin_dashboard', compact('profile','limitNotifications','count'));
    }

    public function profile(Request $request): View
    {

        $profile = $request->user();
        $notifications = Notification::where('type',$profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();

        return view('superadmin.profile.profile', compact('profile','limitNotifications','count'));
    }

    public function passwordProfile(Request $request): View
    {
        $profile = $request->user();
        $notifications = Notification::where('type',$profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();

        return view('superadmin.profile.profile_password', compact('profile','limitNotifications','count'));
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

            return redirect()->route('superadmin.profile.password')->with('info', 'Current password is incorrect.');

        } else {

            if (Hash::check($request->input('password'), $user->password)) {

                return redirect()->route('superadmin.profile.password')->with('info', "Password doesn't change.");
            }

            $user->password = Hash::make($request->input('password'));

            $user->save();

            return redirect()->route('superadmin.profile.password')->with('success', 'Password updated successfull.');
        }
    }

    public function superAdminLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
    // End SUper Admin

    // Doctor 
    public function doctor()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type',$profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $users = User::where('role', 'doctor')->get();
        $doctors = Doctor::all();

        return view('superadmin.account.doctor', compact('users', 'profile', 'doctors','limitNotifications','count'));
    }

    public function createDoctor(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'required|numeric|gt:0',
            'gender' => 'required|string|max:255',
            'employment_date' => 'required|date',
            'specialties' => 'required|string|max:255',
            'qualification' => 'required|string|max:255',
            'years_of_experience' => 'required|numeric|gt:0',
            'street' => 'required|string|max:255',
            'brgy' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
            'birthdate' => 'required|date',
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

        doctor::create([
            'account_id' => $latestUser->id,
            'age' => $request->input('age'),
            'gender' => $request->input('gender'),
            'specialties' => $request->input('specialties'),
            'employment_date' => $request->input('employment_date'),
            'qualification' => $request->input('qualification'),
            'years_of_experience' => $request->input('years_of_experience'),
            'phone' => $request->input('phone'),
            'birthdate' => $request->input('birthdate'),
            'street' => $request->input('street'),
            'brgy' => $request->input('brgy'),
            'city' => $request->input('city'),
            'province' => $request->input('province'),
            'facebook_link' => $request->input('facebook'),
            'twitter_link' => $request->input('twitter'),
            'instagram' => $request->input('instagram'),
            'linkedin' => $request->input('linkedin'),
        ]);

        return back()->with('success', 'User added sucessfully.');
    }

    public function updateDoctorPassword(Request $request)
    {

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::findOrFail($request->input('user_id'));

        if (!Hash::check($request->input('current_password'), $user->password)) {

            return redirect()->route('superadmin.doctor')->with('info', 'Current password is incorrect.');

        } else {

            if (Hash::check($request->input('password'), $user->password)) {

                return redirect()->route('superadmin.doctor')->with('info', "Password doesn't change.");
            }

            $user->password = Hash::make($request->input('password'));

            $user->save();

            return redirect()->route('superadmin.doctor')->with('success', 'Password updated successfull.');
        }

    }

    public function updateDoctorInfo(Request $request)
    {

        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'employment_date' => 'required|date',
            'age' => 'required|numeric|gt:0',
            'gender' => 'required|string|max:255',
            'specialties' => 'required|string|max:255',
            'qualification' => 'required|string|max:255',
            'years_of_experience' => 'required|numeric|gt:0',
            'street' => 'required|string|max:255',
            'brgy' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
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
            'employment_date' => $request->input('employment_date'),
            'specialties' => $request->input('specialties'),
            'qualification' => $request->input('qualification'),
            'years_of_experience' => $request->input('years_of_experience'),
            'phone' => $request->input('phone'),
            'street' => $request->input('street'),
            'brgy' => $request->input('brgy'),
            'city' => $request->input('city'),
            'province' => $request->input('province'),
            'facebook_link' => $request->input('facebook'),
            'twitter_link' => $request->input('twitter'),
            'instagram_link' => $request->input('instagram'),
            'linked_link' => $request->input('linkedin'),
        ];

        $userChange = $this->hasChanges($user, $userUpdatedData);
        $infoChange = $this->hasChanges($info, $infoUpdatedData);


        // Check if any changes were made to the form data
        if ($userChange == true || $infoChange == true) {

            if ($request->input('email') !== $user->email) {

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
                $info->employment_date = $request->input('employment_date');
                $info->specialties = $request->input('specialties');
                $info->years_of_experience = $request->input('years_of_experience');
                $info->street = $request->input('street');
                $info->brgy = $request->input('brgy');
                $info->city = $request->input('city');
                $info->province = $request->input('province');
                $info->birthdate = $request->input('birthdate');
                $info->phone = $request->input('phone');
                $info->facebook_link = $request->input('facebook');
                $info->twitter_link = $request->input('twitter');
                $info->instagram_link = $request->input('instagram');
                $info->linkedin_link = $request->input('linked_link');


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
                $info->employment_date = $request->input('employment_date');
                $info->qualification = $request->input('qualification');
                $info->specialties = $request->input('specialties');
                $info->years_of_experience = $request->input('years_of_experience');
                $info->street = $request->input('street');
                $info->brgy = $request->input('brgy');
                $info->city = $request->input('city');
                $info->province = $request->input('province');
                $info->birthdate = $request->input('birthdate');
                $info->phone = $request->input('phone');
                $info->facebook_link = $request->input('facebook');
                $info->twitter_link = $request->input('twitter');
                $info->instagram_link = $request->input('instagram');
                $info->linkedin_link = $request->input('linked_link');

                $user->save();
                $info->save();

                return redirect()->back()->with('success', 'Profile updated successfully.');
            }

        } else {
            return redirect()->back()->with('info', 'No changes were made.');

        }
    }
    // End Doctor 

    // Nurse
    public function nurse()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type',$profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $users = User::where('role', 'nurse')->get();
        $nurses = Nurse::all();

        return view('superadmin.account.nurse', compact('users', 'profile', 'nurses','limitNotifications','count'));
    }

    public function createNurse(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'required|numeric|gt:0',
            'gender' => 'required|string|max:255',
            'employment_date' => 'required|date',
            'shift' => 'required|string|max:255',
            'qualification' => 'required|string|max:255',
            'years_of_experience' => 'required|numeric|gt:0',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
            'birthdate' => 'required|date',
            'phone' => 'required',
        ]);

        $user = User::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->password),
            'role' => 'nurse',
        ]);

        $latestUser = User::latest()->first();

        nurse::create([
            'account_id' => $latestUser->id,
            'age' => $request->input('age'),
            'shift' => $request->input('shift'),
            'gender' => $request->input('gender'),
            'employment_date' => $request->input('employment_date'),
            'qualification' => $request->input('qualification'),
            'years_of_experience' => $request->input('years_of_experience'),
            'phone' => $request->input('phone'),
            'birthdate' => $request->input('birthdate'),
            'address' => $request->input('address'),
        ]);

        return back()->with('success', 'User added sucessfully.');
    }

    public function updateNurseInfo(Request $request)
    {

        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'employment_date' => 'required|date',
            'age' => 'required|numeric|gt:0',
            'gender' => 'required|string|max:255',
            'shift' => 'required|string|max:255',
            'qualification' => 'required|string|max:255',
            'years_of_experience' => 'required|numeric|gt:0',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'birthdate' => 'required|date',
            'phone' => 'required',
        ]);

        $user = User::findOrFail($request->input('user_id'));
        $info = Nurse::where('account_id', $request->user_id)->first();

        $userUpdatedData = [
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
        ];

        $infoUpdatedData = [
            'age' => $request->input('age'),
            'gender' => $request->input('gender'),
            'birthdate' => $request->input('birthdate'),
            'employment_date' => $request->input('employment_date'),
            'shift' => $request->input('shift'),
            'qualification' => $request->input('qualification'),
            'years_of_experience' => $request->input('years_of_experience'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
        ];

        $userChange = $this->hasChanges($user, $userUpdatedData);
        $infoChange = $this->hasChanges($info, $infoUpdatedData);


        // Check if any changes were made to the form data
        if ($userChange == true || $infoChange == true) {

            if ($request->input('email') !== $user->email) {

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
                $info->employment_date = $request->input('employment_date');
                $info->shift = $request->input('shift');
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
                $info->employment_date = $request->input('employment_date');
                $info->qualification = $request->input('qualification');
                $info->shift = $request->input('shift');
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

    public function updateNurseStatus(Request $request)
    {

        $user = User::findOrFail($request->input('user_id'));

        if ($request->input('status') === 'active') {

            $user->status = 'inactive';
            $user->save();

            return redirect()->route('superadmin.nurse')->with('info', 'User status updated to inactive.');
        } else {

            $user->status = 'active';
            $user->save();

            return redirect()->route('superadmin.nurse')->with('info', 'User status updated to active.');
        }
    }

    public function updateNursePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::findOrFail($request->input('user_id'));

        if (!Hash::check($request->input('current_password'), $user->password)) {

            return redirect()->route('superadmin.nurse')->with('info', 'Current password is incorrect.');

        } else {

            if (Hash::check($request->input('password'), $user->password)) {

                return redirect()->route('superadmin.nurse')->with('info', "Password doesn't change.");
            }

            $user->password = Hash::make($request->input('password'));

            $user->save();

            return redirect()->route('superadmin.nurse')->with('success', 'Password updated successfull.');
        }

    }
    // End Nurse

    // User
    public function user()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type',$profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $users = User::where('role', 'user')->get();
        $users_info = User_info::all();

        return view('superadmin.account.user', compact('users', 'profile', 'users_info','limitNotifications','count'));
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'required|numeric|gt:0',
            'gender' => 'required|string|max:255',
            'occupation' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
            'birthdate' => 'required|date',
            'phone' => 'required',
        ]);

        $user = User::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        $latestUser = User::latest()->first();

        User_info::create([
            'account_id' => $latestUser->id,
            'age' => $request->input('age'),
            'gender' => $request->input('gender'),
            'occupation' => $request->input('occupation'),
            'phone' => $request->input('phone'),
            'birthdate' => $request->input('birthdate'),
            'address' => $request->input('address'),
        ]);

        return back()->with('success', 'User added sucessfully.');
    }

    public function updateUserInfo(Request $request)
    {

        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'required|numeric|gt:0',
            'gender' => 'required|string|max:255',
            'occupation' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'birthdate' => 'required|date',
            'phone' => 'required',
        ]);

        $user = User::findOrFail($request->input('user_id'));
        $info = User_info::where('account_id', $request->user_id)->first();

        $userUpdatedData = [
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
        ];

        $infoUpdatedData = [
            'age' => $request->input('age'),
            'gender' => $request->input('gender'),
            'birthdate' => $request->input('birthdate'),
            'occupation' => $request->input('occupation'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
        ];

        $userChange = $this->hasChanges($user, $userUpdatedData);
        $infoChange = $this->hasChanges($info, $infoUpdatedData);


        // Check if any changes were made to the form data
        if ($userChange == true || $infoChange == true) {

            if ($request->input('email') !== $user->email) {

                $request->validate([
                    'email' => 'required|string|email|max:255|unique:users,email,',
                ]);

                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $user->middle_name = $request->input('middle_name');
                $user->email = $request->input('email');
                $info->age = $request->input('age');
                $info->gender = $request->input('gender');
                $info->occupation = $request->input('occupation');
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
                $info->occupation = $request->input('occupation');
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

    public function updateUserStatus(Request $request)
    {

        $user = User::findOrFail($request->input('user_id'));

        if ($request->input('status') === 'active') {

            $user->status = 'inactive';
            $user->save();

            return redirect()->route('superadmin.user')->with('info', 'User status updated to inactive.');
        } else {

            $user->status = 'active';
            $user->save();

            return redirect()->route('superadmin.user')->with('info', 'User status updated to active.');
        }
    }

    public function updateUserPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::findOrFail($request->input('user_id'));

        if (!Hash::check($request->input('current_password'), $user->password)) {

            return redirect()->route('superadmin.user')->with('info', 'Current password is incorrect.');

        } else {

            if (Hash::check($request->input('password'), $user->password)) {

                return redirect()->route('superadmin.user')->with('info', "Password doesn't change.");
            }

            $user->password = Hash::make($request->input('password'));

            $user->save();

            return redirect()->route('superadmin.nurse')->with('success', 'Password updated successfull.');
        }

    }
    // End User 

    // Admin
    public function admin()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type',$profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $users = User::where('role', 'admin')->get();
        $admins = Admin::all();

        return view('superadmin.account.admin', compact('users', 'profile', 'admins','limitNotifications','count'));
    }

    public function createAdmin(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'access_level' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        $latestUser = User::latest()->first();

        Admin::create([
            'account_id' => $latestUser->id,
            'access_level' => $request->input('access_level'),
        ]);

        return back()->with('success', 'User added sucessfully.');
    }

    public function updateAdminInfo(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'access_level' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        $user = User::findOrFail($request->input('user_id'));
        $info = admin::where('account_id', $request->user_id)->first();

        $userUpdatedData = [
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
        ];

        $infoUpdatedData = [
            'access_level' => $request->input('access_level')
        ];

        $userChange = $this->hasChanges($user, $userUpdatedData);
        $infoChange = $this->hasChanges($info, $infoUpdatedData);


        // Check if any changes were made to the form data
        if ($userChange == true || $infoChange == true) {

            if ($request->input('email') !== $user->email) {

                $request->validate([
                    'email' => 'required|string|email|max:255|unique:users,email,',
                ]);

                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $user->middle_name = $request->input('middle_name');
                $user->email = $request->input('email');
                $info->access_level = $request->input('access_level');

                $user->save();
                $info->save();

                return redirect()->back()->with('success', 'Profile updated successfully.');

            } else {

                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $user->middle_name = $request->input('middle_name');
                $user->email = $request->input('email');
                $info->access_level = $request->input('access_level');

                $user->save();
                $info->save();

                return redirect()->back()->with('success', 'Profile updated successfully.');
            }

        } else {
            return redirect()->back()->with('info', 'No changes were made.');

        }
    }

    public function updateAdminStatus(Request $request)
    {

        $user = User::findOrFail($request->input('user_id'));

        if ($request->input('status') === 'active') {

            $user->status = 'inactive';
            $user->save();

            return redirect()->route('superadmin.admin')->with('info', 'User status updated to inactive.');
        } else {

            $user->status = 'active';
            $user->save();

            return redirect()->route('superadmin.admin')->with('info', 'User status updated to active.');
        }
    }

    public function updateAdminPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::findOrFail($request->input('user_id'));

        if (!Hash::check($request->input('current_password'), $user->password)) {

            return redirect()->route('superadmin.admin')->with('info', 'Current password is incorrect.');

        } else {

            if (Hash::check($request->input('password'), $user->password)) {

                return redirect()->route('superadmin.admin')->with('info', "Password doesn't change.");
            }

            $user->password = Hash::make($request->input('password'));

            $user->save();

            return redirect()->route('superadmin.admin')->with('success', 'Password updated successfull.');
        }

    }

    private function hasChanges($info, $updatedData)
    {
        foreach ($updatedData as $key => $value) {

            if ($info->{$key} != $value) {

                return true;
            }
        }

        return false;

    }
}