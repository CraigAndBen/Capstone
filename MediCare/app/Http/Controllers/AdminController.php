<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use App\Http\Requests\ProfileUpdateRequest;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AdminController extends Controller
{
    public function dashboard()
    {
        $profile = auth()->user();

        return view('admin_dashboard', compact('profile'));
    }

    public function edit(Request $request): View
    {
        return view('admin.profile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $saved = $request->user()->save();

        if ($saved) {
            return Redirect::route('admin.profile.edit')->with('status', 'Profile Updated');
        } else {
            return Redirect::route('admin.profile.edit')->with('status', 'Profile not Updated');
        }
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $saved = $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        if ($saved) {
            return back()->with('status', 'Password Updated');
        } else {
            return back()->with('status', 'Password not Updated');
        }
    }
    
    public function patientList(){

        $profile = auth()->user();
        $doctors = User::where('role','doctor')->get();
        $patients = Patient::all();

        return view('admin.patient.patient', compact('patients','profile','doctors'));

    }
    
    public function patientStore (Request $request)
    {

        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'brgy' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'gender' => 'required|string|max:255',
            'phone' => 'required',
            'admitted_date' => 'required|date',
            'room_number' => 'required',
            'bed_number' => 'required',
            'physician' => 'required|string|max:255',
        ]);

        Patient::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'street' => $request->input('street'),
            'gender' => $request->input('gender'),
            'brgy' => $request->input('brgy'),
            'city' => $request->input('city'),
            'province' => $request->input('province'),
            'birthdate' => $request->input('birthdate'),
            'phone' => $request->input('phone'),
            'admitted_date' => $request->input('admitted_date'),
            'discharged_date' => $request->input('discharged_date'),
            'room_number' => $request->input('room_number'),
            'bed_number' => $request->input('bed_number'),
            'physician' => $request->input('physician'),
            'medical_condition' => $request->input('medical_condition'),
            'diagnosis' => $request->input('diagnosis'),
            'medication' => $request->input('medication'),
        ]);

        return back()->with('success', 'Patient added sucessfully.');

    }
    // Logout
    public function adminLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
