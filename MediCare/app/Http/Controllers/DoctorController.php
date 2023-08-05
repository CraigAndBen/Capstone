<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\View\View;
use App\Models\Appointment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use App\Http\Requests\ProfileUpdateRequest;

class DoctorController extends Controller
{
    public function dashboard()
    {

        $profile = auth()->user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('specialties', $info->specialties)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $appointments = Appointment::all();
        $currentDate = date('Y-m-d');

        // Get the current year
        $currentYear = Carbon::now()->year;

        // Retrieve the patients handled by the specific doctor for the current year
        $patientsByMonth = DB::table('patient')
            ->select(DB::raw('DATE_FORMAT(admitted_date, "%M") as month'), DB::raw('COUNT(*) as count'))
            ->where('physician', $profile->id)
            ->whereYear('admitted_date', $currentYear)
            ->groupBy('month')
            ->get();

        $patientCount = $patientsByMonth->count();

        $currentMonth = Carbon::now()->month;

        // Retrieve appointments for the specific doctor in the current month
        $currentMonthAppointments = DB::table('appointment')
            ->where('specialties', $info->specialties)
            ->whereMonth('appointment_date', $currentMonth)
            ->get();

        $limitCurrentMonthAppointments = $currentMonthAppointments->take(5);

        // Retrieve the monthly appointments for the specific doctor for the current year
        $monthlyAppointments = DB::table('appointment')
            ->select(DB::raw('MONTH(appointment_date) as month'), DB::raw('COUNT(*) as count'))
            ->where('specialties', $info->specialties)
            ->whereYear('appointment_date', $currentYear)
            ->groupBy('month')
            ->get();

        // Format the data for the line graph
        $months = [];
        $appointmentCounts = [];
        for ($month = 1; $month <= 12; $month++) {
            $months[] = Carbon::createFromFormat('m', $month)->format('F');
            $appointmentCounts[] = $monthlyAppointments->where('month', $month)->pluck('count')->first() ?? 0;
        }

        foreach ($appointments as $appointment) {

            if (strtotime($appointment->appointment_date) < strtotime($currentDate)) {

                $appoint = Appointment::findOrFail($appointment->id);

                $appoint->status = 'unavailable';
                $appoint->save();

                $currentTime = Carbon::now()->toTimeString();
                $currentDate = Carbon::now()->toDateString();
                $message = ' Your appointment that has a type of ' . $appointment->appointment_type . ' that dated ' . $appointment->appointment_date . ' and timed ' . $appointment->appointment_time . ' is unavailable.';

                Notification::create([
                    'account_id' => $appointment->account_id,
                    'title' => 'Appointment Unavailable',
                    'message' => $message,
                    'date' => $currentDate,
                    'time' => $currentTime,
                ]);
            }
        }

        return view('doctor_dashboard', compact('profile', 'limitNotifications', 'count', 'info', 'patientsByMonth','patientCount','limitCurrentMonthAppointments','months','appointmentCounts'));
    }

    public function profile(Request $request): View
    {
        $genders = [
            'male' => 'Male',
            'female' => 'Female',
            'other' => 'Other',
        ];

        $profile = $request->user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('specialties', $info->specialties)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();

        return view('doctor.profile.profile', compact('profile', 'info', 'genders', 'limitNotifications', 'count'));
    }

    public function socialProfile(Request $request): View
    {

        $profile = $request->user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('specialties', $info->specialties)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();

        return view('doctor.profile.profile_social', compact('profile', 'info', 'limitNotifications', 'count'));
    }

    public function passwordProfile(Request $request): View
    {
        $profile = $request->user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('specialties', $info->specialties)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();

        return view('doctor.profile.profile_password', compact('profile', 'limitNotifications', 'count','info'));
    }

    /**
     * Update the user's profile information.
     */
    public function profileUpdate(Request $request)
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

        $user = $request->user();
        $info = Doctor::where('account_id', $user->id)->first();

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

                $user->save();
                $info->save();

                return redirect()->back()->with('success', 'Profile updated successfully.');
            }

        } else {
            return redirect()->back()->with('info', 'No changes were made.');

        }

    }

    public function updateSocialProfile(Request $request)
    {
        $user = $request->user();
        $info = Doctor::where('account_id', $user->id)->first();

        $infoUpdatedData = [
            'facebook_link' => $request->input('age'),
            'twitter_link' => $request->input('gender'),
            'instagram_link' => $request->input('birthdate'),
            'linkedin' => $request->input('employment_date'),
        ];

        if ($request->hasFile('image')) {
            $imageName = $request->image->getClientOriginalName();

            $imageUpdatedData = [
                'image_name' => $imageName,
                'image_data' => 'images/' . $imageName,
            ];

            $imageChange = $this->hasChanges($info, $imageUpdatedData);
        } else {
            $imageChange = false;
        }

        $infoChange = $this->hasChanges($info, $infoUpdatedData);

        if ($infoChange) {

            $info->facebook_link = $request->input('facebook');
            $info->twitter_link = $request->input('twitter');
            $info->instagram_link = $request->input('instagram');
            $info->linkedin_link = $request->input('linkedin');

            if ($request->hasFile('image')) {
                if ($imageChange) {
                    $imageName = $request->image->getClientOriginalName();
    
                    // Save the image to the public folder
                    $request->image->move(public_path('images'), $imageName);
    
                    // Save the image path to the database
                    $info->image_name = $imageName;
                    $info->image_data = 'images/' . $imageName;
                    $info->save();
    
                    return redirect()->back()->with('success', 'Social Profile updated successfully.');
    
                } else {
                    return redirect()->back()->with('info', 'The image is already uploaded.');
                }
            } else {
                $info->save();
                return redirect()->back()->with('success', 'Social Profile updated successfully.');
            }
            
        } else {
            if ($request->hasFile('image')) {
                if ($imageChange) {
                    $imageName = $request->image->getClientOriginalName();
    
                    // Save the image to the public folder
                    $request->image->move(public_path('images'), $imageName);
    
                    // Save the image path to the database
                    $info->image_name = $imageName;
                    $info->image_data = 'images/' . $imageName;
                    $info->save();
    
                    return redirect()->back()->with('success', 'Social Profile updated successfully.');
    
                } else {
                    return redirect()->back()->with('info', 'The image is already uploaded.');
                }
            } else {
                return redirect()->back()->with('info', 'No changes were made.');
            }

        }



    }

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

    //Appointment
    public function appointment()
    {

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
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('specialties', $info->specialties)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = Doctor::all();
        $appointments = Appointment::where('specialties', $info->specialties)->orderBy('appointment_date', 'desc')->get();

        return view('doctor.appointment.appointment', compact('appointments', 'profile', 'doctors', 'amTime', 'pmTime', 'limitNotifications', 'count','info'));
    }
    public function confirmedAppointmentList()
    {
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
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('specialties', $info->specialties)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = Doctor::all();
        $appointments = Appointment::where('specialties', $info->specialties, 'status')->where('status', 'confirmed')->get();

        return view('doctor.appointment.confirmed_appointment', compact('appointments', 'profile', 'doctors', 'amTime', 'pmTime', 'limitNotifications', 'count','info'));

    }

    public function doneAppointmentList()
    {
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
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('specialties', $info->specialties)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = Doctor::all();
        $appointments = Appointment::where('specialties', $info->specialties, 'status')->where('status', 'done')->get();

        return view('doctor.appointment.done_appointment', compact('appointments', 'profile', 'doctors', 'amTime', 'pmTime', 'limitNotifications', 'count','info'));
    }

    public function confirmedAppointment(Request $request)
    {

        $profile = auth()->user();
        $doctor = Doctor::where('account_id', $profile->id)->first();
        $appointment = Appointment::findOrFail($request->input('appointment_id'));

        $appointment->status = 'confirmed';
        $appointment->doctor_id = $doctor->account_id;
        $appointment->save();

        $currentTime = Carbon::now()->toTimeString();
        $currentDate = Carbon::now()->toDateString();
        $message = ' Your appointment that has ' . $appointment->appointment_type . ' that dated ' . $appointment->appointment_date . ' and timed ' . $appointment->appointment_time . ' is confirmed.';

        Notification::create([
            'account_id' => $appointment->account_id,
            'title' => 'appointment confirmation',
            'message' => $message,
            'date' => $currentDate,
            'time' => $currentTime,
        ]);

        return redirect()->back()->with('success', 'Appointment Confirmed successfully.');
    }

    public function doneAppointment(Request $request)
    {

        $appointment = Appointment::findOrFail($request->input('appointment_id'));

        $appointment->status = 'done';
        $appointment->save();

        $currentTime = Carbon::now()->toTimeString();
        $currentDate = Carbon::now()->toDateString();
        $message = ' Your appointment that has ' . $appointment->appointment_type . ' that dated ' . $appointment->appointment_date . ' and timed ' . $appointment->appointment_time . ' is done.';

        Notification::create([
            'account_id' => $appointment->account_id,
            'title' => 'appointment done',
            'message' => $message,
            'date' => $currentDate,
            'time' => $currentTime,
        ]);


        return redirect()->back()->with('success', 'Appointment Done successfully.');
    }

    public function cancelAppointment(Request $request)
    {
        $appointment = Appointment::findOrFail($request->input('appointment_id'));

        $appointment->status = 'cancelled';
        $appointment->save();

        return redirect()->back()->with('success', 'Appointment Cancelled successfully.');
    }

    public function patientList()
    {
        $profile = auth()->user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('specialties', $info->specialties)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = User::where('role', 'doctor')->get();
        $patients = Patient::where('physician', $profile->id)->where('discharged_date', '')->get();

        return view('doctor.patient.patient', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count','info'));
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

        if ($patientChange) {
            $patient->medical_condition = $request->input('medical_condition');
            $patient->diagnosis = $request->input('diagnosis');
            $patient->medication = $request->input('medication');

            $patient->save();

            return redirect()->back()->with('success', 'Patient Information Updated Successfully.');
        } else {
            return redirect()->back()->with('info', 'No changes were made.');
        }
    }

    public function notification()
    {

        $profile = Auth::user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('specialties', $info->specialties)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();

        return view('doctor.notification.notification', compact('profile', 'notifications', 'limitNotifications', 'count', 'info'));

    }

    public function notificationRead(Request $request)
    {

        $notification = Notification::findOrFail($request->input('id'));

        if ($notification->is_read == 0) {
            $notification->is_read = 1;
            $notification->save();

            return redirect()->route('doctor.notification');
        } else {
            return redirect()->route('doctor.notification');
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

    public function doctorLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}