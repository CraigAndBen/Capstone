<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\View\View;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use App\Http\Requests\ProfileUpdateRequest;

class DoctorController extends Controller
{
    public function dashboard(){
        
        $profile = auth()->user();

        return view('doctor_dashboard', compact('profile'));
    }

    public function edit(Request $request): View
    {
        return view('doctor.profile', [
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

        if($saved){
            return Redirect::route('doctor.profile.edit')->with('status', 'Profile Updated');
        }else{
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

        if($saved){
            return back()->with('status', 'Password Updated');
        }else{
            return back()->with('status', 'Password not Updated');
        }
    }

    public function appointment(){

        $amTime = [
            '8:30',
            '9:00',
            '9:30',
            '10:30',
            '11:00',
            '11:30',
        ];

        $pmTime = [
            '1:30',
            '2:00',
            '2:30',
            '3:00',
            '3:30',
            '4:00',
        ];

        $profile = auth()->user();
        $infos = Doctor::all();
        $doctor = Doctor::where('account_id', $profile->id)->first();
        $appointments = Appointment::where('specialties', $doctor->specialties,'status')->where('status','pending')->get();

        return view('doctor.appointment.appointment', compact('appointments','profile','infos','amTime','pmTime'));
    }
    public function confirmedAppointmentList(){
        $amTime = [
            '8:30',
            '9:00',
            '9:30',
            '10:30',
            '11:00',
            '11:30',
        ];

        $pmTime = [
            '1:30',
            '2:00',
            '2:30',
            '3:00',
            '3:30',
            '4:00',
        ];

        $profile = auth()->user();
        $infos = Doctor::all();
        $doctor = Doctor::where('account_id', $profile->id)->first();
        $appointments = Appointment::where('specialties', $doctor->specialties,'status')->where('status','confirmed')->get();

        return view('doctor.appointment.confirmed_appointment', compact('appointments','profile','infos','amTime','pmTime'));
    }

    public function doneAppointmentList(){

    }

    public function confirmedAppointment(Request $request){

        $profile = auth()->user();
        $doctor = Doctor::where('account_id', $profile->id)->first();
        $appointment = Appointment::findOrFail($request->input('appointment_id'));

            $appointment->status = 'confirmed';
            $appointment->doctor_id = $doctor->id;
            $appointment->save();

            return redirect()->back()->with('success', 'Appointment Confirmed successfully.');
    }

    public function doneAppointment(){

    }

    public function cancelAppointment(Request $request){

        $appointment = Appointment::findOrFail($request->input('appointment_id'));

            $appointment->status = 'cancelled';
            $appointment->save();

            return redirect()->back()->with('success', 'Appointment Cancelled successfully.');
    }
    public function doctorLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
