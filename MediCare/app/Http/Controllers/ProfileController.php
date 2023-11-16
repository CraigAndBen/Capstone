<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\User_info;
use Illuminate\View\View;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function profile(Request $request): View
    {
        $user = $request->user();
        $user_info = User_info::where('account_id',$user->id)->first();
        $notificationsAlert = Notification::where('account_id', $user->id)->where('is_read',0)->get();

        return view('user.profile.profile', compact('user','user_info','notificationsAlert'));
    }

    public function passwordProfile(Request $request): View
    {
        $user = $request->user();
        $notificationsAlert = Notification::where('account_id', $user->id)->where('is_read',0)->get();


        return view('user.profile.profile_password', compact('user','notificationsAlert'));
    }

    /**
     * Update the user's profile information.
     */
    public function profileUpdate(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
        ]);

        $user = $request->user();
        $user_info = User_info::where('account_id',$user->id)->first();

        $userUpdatedData = [
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
        ];

        $userInfoUpdatedData = [
            'gender' => $request->input('gender'),
            'age' => $request->input('age'),
            'phone' => $request->input('phone'),
            'birthdate' => $request->input('birthdate'),
            'street' => $request->input('street'),
            'brgy' => $request->input('brgy'),
            'city' => $request->input('city'),
            'province' => $request->input('province'),
            'occupation' => $request->input('occupation'),
        ];

        $userChange = $this->hasChanges($user, $userUpdatedData);
        $userInfoChange = $this->hasChanges($user_info, $userInfoUpdatedData);

        if ($userChange || $userInfoChange) {

            if ($user->email != $request->input('email')) {

                $request->validate([
                    'email' => 'required|string|email|unique:users|max:255',
                ]);

                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $user->email = $request->input('email');
                $user_info->gender = $request->input('gender');
                $user_info->age = $request->input('age');
                $user_info->phone = $request->input('phone');
                $user_info->birthdate = $request->input('birthdate');
                $user_info->street = $request->input('street');
                $user_info->brgy = $request->input('brgy');
                $user_info->city = $request->input('city');
                $user_info->province = $request->input('province');
                $user_info->occupation = $request->input('occupation');
                $userSaved = $user->save();
                $userInfosSaved = $user_info->save();

                if ($userSaved && $userInfosSaved) {
                    return redirect()->back()->with('success', 'Profile updated successfully.');
                } else {
                    return redirect()->back()->with('info', 'Profile not updated successfully.');
                }

            } else {

                $user->first_name = $request->input('first_name');
                $user->middle_name = $request->input('middle_name');
                $user->last_name = $request->input('last_name');
                $user_info->gender = $request->input('gender');
                $user_info->age = $request->input('age');
                $user_info->phone = $request->input('phone');
                $user_info->birthdate = $request->input('birthdate');
                $user_info->street = $request->input('street');
                $user_info->brgy = $request->input('brgy');
                $user_info->city = $request->input('city');
                $user_info->province = $request->input('province');
                $user_info->occupation = $request->input('occupation');
                $userSaved = $user->save();
                $userInfosSaved = $user_info->save();


                if ($userSaved && $userInfosSaved) {
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

            return redirect()->route('user.profile.password')->with('success', 'Password updated successfully.');
        }
    }

    private function hasChanges($info, $updatedData)
    {

        foreach ($updatedData as $key => $value) {

            if ($info->{$key} != $value) {

                return $value;
            }
        }

        return false;

    }
}