<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        return view('superadmin.doctor', compact('users','user'));
        
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

    public function createDoctor(Request $request) {
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'specialties' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
            'date' => 'required|date',
            'phone' => 'required|regex:/^\+[1-9]\d{1,14}$/',
        ]);
        
        // $user = User::create([
        //     'name' => $request->input('name'),
        //     'email' => $request->input('email'),
        //     'password' => bcrypt($request->input('password')),
        // ]);

        // Perform any additional actions if needed


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
