<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use Illuminate\View\View;
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

        return view('nurse_dashboard', compact('profile'));
    }

    public function edit(Request $request): View
    {
        return view('nurse.profile', [
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
            return Redirect::route('doctor.profile.edit')->with('status', 'Profile Updated');
        } else {
            return Redirect::route('doctor.profile.edit')->with('status', 'Profile not Updated');
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

    public function patientList ()
    {
        $profile = auth()->user();
        $doctors = User::where('role', 'doctor')->get();
        $patients = Patient::all();

        return view('nurse.patient.patient', compact('patients', 'profile', 'doctors'));
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
