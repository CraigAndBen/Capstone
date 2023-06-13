<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class DoctorController extends Controller
{
    public function dashboard(){
        
        $user = auth()->user();

        return view('doctor_dashboard', compact('user'));
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
            return Redirect::route('profile.edit')->with('status', 'Profile Updated');
        }else{
            return Redirect::route('profile.edit')->with('status', 'Profile not Updated');
        }
    }

    public function doctorLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
