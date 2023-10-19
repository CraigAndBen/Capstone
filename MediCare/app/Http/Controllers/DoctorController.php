<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\View\View;
use App\Models\Appointment;
use App\Models\Notification;
use App\Models\Report;
use App\Models\Doctor_availabilities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class DoctorController extends Controller
{
    public function dashboard()
    {

        $profile = auth()->user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('specialties', $info->specialties)
            ->where('type', 'doctor')
            ->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $appointments = Appointment::all();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        // Get the current year
        $currentYear = Carbon::now()->year;

        $patientsByMonth = DB::table('patients')
            ->select(DB::raw('DATE_FORMAT(admitted_date, "%M") as month'), DB::raw('COUNT(*) as count'))
            ->where('physician', $profile->id)
            ->whereYear('admitted_date', $currentYear)
            ->groupBy('month')
            ->get();

        $patientsByYear = DB::table('patients')
            ->where('physician', $profile->id)
            ->whereYear('admitted_date', $currentYear)
            ->get();

        $patientCount = $patientsByYear->count();

        $currentMonth = Carbon::now()->month;

        // Retrieve appointments for the specific doctor in the current month
        $currentMonthAppointments = DB::table('appointments')
            ->where('specialties', $info->specialties)
            ->whereMonth('appointment_date', $currentMonth)
            ->orderBy('appointment_date', 'desc')
            ->get();

        $appointmentCount = $currentMonthAppointments->count();
        $limitCurrentMonthAppointments = $currentMonthAppointments->take(5);

        // Retrieve the monthly appointments for the specific doctor for the current year
        $monthlyAppointments = DB::table('appointments')
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



        return view('doctor_dashboard', compact('profile', 'limitNotifications', 'count', 'info', 'patientsByMonth', 'patientCount', 'limitCurrentMonthAppointments', 'months', 'appointmentCounts', 'appointmentCount', 'currentDate', 'currentTime'));
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
        $notifications = Notification::where('specialties', $info->specialties)
            ->where('type', 'doctor')
            ->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        return view('doctor.profile.profile', compact('profile', 'info', 'genders', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
    }

    public function socialProfile(Request $request): View
    {

        $profile = $request->user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('specialties', $info->specialties)
            ->where('type', 'doctor')
            ->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        return view('doctor.profile.profile_social', compact('profile', 'info', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
    }

    public function passwordProfile(Request $request): View
    {
        $profile = $request->user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('specialties', $info->specialties)
            ->where('type', 'doctor')
            ->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        return view('doctor.profile.profile_password', compact('profile', 'limitNotifications', 'count', 'info', 'currentTime', 'currentDate'));
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
        $notifications = Notification::where('specialties', $info->specialties)
            ->where('type', 'doctor')
            ->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = Doctor::all();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $appointments = Appointment::where('doctor_id', $profile->id)
            ->orWhere('specialties', $info->specialties)
            ->orWhereNull('doctor_id')
            ->orderByRaw("TIME_FORMAT(appointment_time, '%h:%i %p') DESC, appointment_date DESC")
            ->paginate(5);

        return view('doctor.appointment.appointment', compact('appointments', 'profile', 'doctors', 'amTime', 'pmTime', 'limitNotifications', 'count', 'info', 'currentTime', 'currentDate'));
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
        $notifications = Notification::where('specialties', $info->specialties)
            ->where('type', 'doctor')
            ->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = Doctor::all();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $appointments = Appointment::where('doctor_id', $profile->id)
            ->Where('specialties', $info->specialties)
            ->where('status', 'confirmed')
            ->orderBy('appointment_date', 'desc')->paginate(5);

        return view('doctor.appointment.confirmed_appointment', compact('appointments', 'profile', 'doctors', 'amTime', 'pmTime', 'limitNotifications', 'count', 'info', 'currentTime', 'currentDate'));

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
        $notifications = Notification::where('specialties', $info->specialties)
            ->where('type', 'doctor')
            ->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = Doctor::all();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $appointments = Appointment::where('doctor_id', $profile->id)
            ->where('status', 'done')->paginate(10);

        return view('doctor.appointment.done_appointment', compact('appointments', 'profile', 'doctors', 'amTime', 'pmTime', 'limitNotifications', 'count', 'info', 'currentTime', 'currentDate'));
    }

    public function confirmedAppointment(Request $request)
    {
        $profile = auth()->user();
        $doctor = Doctor::where('account_id', $profile->id)->first();
        $appointment = Appointment::where('id', $request->input('appointment_id'))->first();

        $appointment->status = 'confirmed';
        $appointment->doctor_id = $doctor->account_id;
        $appointment->save();

        $currentTime = Carbon::now()->toTimeString();
        $currentDate = Carbon::now()->toDateString();
        $message = ' Your appointment that has ' . $appointment->appointment_type . ' that dated ' . $appointment->appointment_date . ' and timed ' . $appointment->appointment_time . ' is confirmed.';

        Notification::create([
            'account_id' => $appointment->account_id,
            'title' => 'Appointment confirmed',
            'message' => $message,
            'date' => $currentDate,
            'time' => $currentTime,
            'type' => 'user',
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
            'title' => 'Appointment done',
            'message' => $message,
            'date' => $currentDate,
            'time' => $currentTime,
            'type' => 'user',
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

    public function appointmentSearch(Request $request)
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
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $searchTerm = $request->input('search');

        $appointments = Appointment::where('specialties', $info->specialties)
            ->where(function ($query) use ($searchTerm) {
                $query->orWhere('first_name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%');
            })
            ->where(function ($query) {
                $query->where('status', 'pending')
                    ->orWhere('status', 'confirmed')
                    ->orWhere('status', 'done');
            })
            ->where(function ($query) use ($profile) {
                $query->where('doctor_id', $profile->id)
                    ->orWhereNull('doctor_id');
            })
            ->orderBy('appointment_date', 'desc')
            ->paginate(5);


        return view('doctor.appointment.appointment_search', compact('appointments', 'profile', 'doctors', 'amTime', 'pmTime', 'limitNotifications', 'count', 'info', 'currentTime', 'currentDate'));

    }

    public function confirmedAppointmentSearch(Request $request)
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
        $notifications = Notification::where('specialties', $info->specialties)
            ->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = Doctor::all();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $searchTerm = $request->input('search');

        $appointments = Appointment::where('specialties', $info->specialties)
            ->where(function ($query) use ($searchTerm) {
                $query->orWhere('first_name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%');
            })
            ->where(function ($query) {
                $query->where('status', 'confirmed');
            })
            ->where(function ($query) use ($profile) {
                $query->where('doctor_id', $profile->id)
                    ->orWhereNull('doctor_id');
            })
            ->orderBy('appointment_date', 'desc')
            ->paginate(5);

        return view('doctor.appointment.confirmed_appointment_search', compact('appointments', 'profile', 'doctors', 'amTime', 'pmTime', 'limitNotifications', 'count', 'info', 'currentTime', 'currentDate'));
    }

    public function doneAppointmentSearch(Request $request)
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
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $searchTerm = $request->input('search');

        $appointments = Appointment::where('specialties', $info->specialties)
            ->where(function ($query) use ($searchTerm) {
                $query->orWhere('first_name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%');
            })
            ->where(function ($query) {
                $query->where('status', 'done');
            })
            ->where(function ($query) use ($profile) {
                $query->where('doctor_id', $profile->id)
                    ->orWhereNull('doctor_id');
            })
            ->orderBy('appointment_date', 'desc')
            ->paginate(5);

        return view('doctor.appointment.done_appointment_search', compact('appointments', 'profile', 'doctors', 'amTime', 'pmTime', 'limitNotifications', 'count', 'info', 'currentTime', 'currentDate'));
    }

    public function appointmentCalendar()
    {
        $profile = auth()->user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('specialties', $info->specialties)
            ->where('type', 'doctor')
            ->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = Doctor::all();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

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


        return view('doctor.appointment.appointment_calendar', compact('profile', 'doctors', 'amTime', 'pmTime', 'limitNotifications', 'count', 'info', 'currentTime', 'currentDate'));
    }

    public function appointmentEvents()
    {
        $user = Auth::user();
        $info = Doctor::where('account_id', $user->id)->first();
        $appointments = Appointment::where('specialties', $info->specialties)
            ->where('doctor_id', $user->id)
            ->orWhereNull('doctor_id')
            ->get(); // Replace with your own query to fetch the event data

        $events = [];
        foreach ($appointments as $appointment) {

            $appointmentDateTime = DateTime::createFromFormat('Y-m-d h:i A', $appointment->appointment_date . ' ' . $appointment->appointment_time);

            // Calculate the end time by adding a fixed duration (e.g., 1 hour)
            $endDateTime = clone $appointmentDateTime;
            $endDateTime->modify('+1 hour'); // Add exactly 1 hour

            $events[] = [
                'appointment_id' => $appointment->id,
                'title' => ucwords($appointment->appointment_type),
                // Replace with the field containing the event title
                'start' => $appointmentDateTime->format('Y-m-d H:i:s'),
                // Format the start date and time
                'end' => $endDateTime->format('Y-m-d H:i:s'),
                // Format the end date and time
                'status' => ucwords($appointment->status),
                // Format the end date and time
                'type' => 'appointment',
            ];
        }

        return response()->json($events);
    }

    public function holidayEvents()
    {
        $currentYear = date('Y'); // Get the current year
        $staticHolidays = [
            // Static holidays for the current year with date information
            [
                'title' => 'New Year',
                'start' => $currentYear . '-01-01',
                'end' => $currentYear . '-01-01',
                'type' => 'holiday',
            ],
            [
                'title' => 'Independence Day',
                'start' => $currentYear . '-07-04',
                'end' => $currentYear . '-07-04',
                'type' => 'holiday',
            ],
            [
                'title' => 'Christmas Day',
                'start' => $currentYear . '-12-25',
                'end' => $currentYear . '-12-25',
                'type' => 'holiday',
            ],
            [
                'title' => 'All Saints Day',
                'start' => $currentYear . '-11-01',
                'end' => $currentYear . '-11-01',
                'type' => 'holiday',
            ],
            [
                'title' => 'Bonifacio Day',
                'start' => $currentYear . '-11-30',
                'end' => $currentYear . '-11-30',
                'type' => 'holiday',
            ],
            [
                'title' => 'Rizal Day',
                'start' => $currentYear . '-12-30',
                'end' => $currentYear . '-12-30',
                'type' => 'holiday',
            ],
            [
                'title' => 'Ninoy Aquino Day',
                'start' => $currentYear . '-8-21',
                'end' => $currentYear . '-8-21',
                'type' => 'holiday',
            ],

        ];

        // Initialize an array to store all holidays
        $allHolidays = [];

        // Define the number of years to generate holidays for (e.g., 1 year before, current year, and 1 year after)
        $yearsToGenerate = 3; // You can adjust this as needed

        // Loop through the past year, current year, and next year
        for ($i = -$yearsToGenerate + 1; $i <= $yearsToGenerate; $i++) {
            $year = $currentYear + $i;

            // Loop through the static holidays and add them to the allHolidays array
            foreach ($staticHolidays as $staticHoliday) {
                // Clone the static holiday array
                $holiday = $staticHoliday;

                // Update the 'start' and 'end' dates with the current year
                $holiday['start'] = $year . substr($holiday['start'], 4); // Replace the year portion
                $holiday['end'] = $year . substr($holiday['end'], 4); // Replace the year portion

                // Append the holiday to the allHolidays array
                $allHolidays[] = $holiday;
            }
        }

        return response()->json($allHolidays);

    }

    public function availabilityDates()
    {
        $user = Auth::user();
        $availabilities = Doctor_availabilities::where('doctor_id', $user->id)
            ->get(); // Replace with your own query to fetch the event data

        $events = [];

        foreach ($availabilities as $available) {
            $events[] = [
                'availability_id' => $available->id,
                'title' => ucwords($available->type),
                // Replace with the field containing the event title
                'start' => $available->date,
                // Format the start date and time
                'end' => $available->date,
                'availability' => $available->type,
                'reason' => $available->reason,
                // Format the end date and time
                'type' => 'availability'
            ];
        }

        return response()->json($events);
    }

    public function calendarConfirmedAppointment(Request $request)
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
            'title' => 'Appointment Confirmed',
            'message' => $message,
            'date' => $currentDate,
            'time' => $currentTime,
        ]);

        return redirect()->route('doctor.appointment.calendar')->with('success', 'Appointment Confirmed successfully.');
    }

    public function calendarDoneAppointment(Request $request)
    {
        $profile = auth()->user();
        $doctor = Doctor::where('account_id', $profile->id)->first();
        $appointment = Appointment::findOrFail($request->input('appointment_id'));

        $appointment->status = 'done';
        $appointment->save();

        $currentTime = Carbon::now()->toTimeString();
        $currentDate = Carbon::now()->toDateString();
        $message = ' Your appointment that has ' . $appointment->appointment_type . ' that dated ' . $appointment->appointment_date . ' and timed ' . $appointment->appointment_time . ' is done.';

        Notification::create([
            'account_id' => $appointment->account_id,
            'title' => 'Appointment Done',
            'message' => $message,
            'date' => $currentDate,
            'time' => $currentTime,
        ]);

        return redirect()->route('doctor.appointment.calendar')->with('success', 'Appointment Done successfully.');
    }

    public function doctorAvailability(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'availabilityDate' => 'required|date',
            'availability' => 'required|string|max:255',
        ]);

        $profile = auth()->user();
        $doctor = Doctor::where('account_id', $profile->id)->first();
        $availabilityDate = $request->availabilityDate;
        $readableDate = date('F j, Y', strtotime($availabilityDate));

        if ($request->availability == 'available') {

            return redirect()->route('doctor.appointment.calendar')->with('info', "Your availability is doesn't change. ");

        } else {

            Doctor_availabilities::create([
                'doctor_id' => $doctor->account_id,
                'date' => $request->availabilityDate,
                'reason' => $request->reason,
                'type' => $request->availability,
            ]);

            return redirect()->route('doctor.appointment.calendar')->with('success', 'Your availability is now set to ' . $request->availability . ' on ' . $readableDate);
        }
    }

    public function updateDoctorAvailability(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'availability' => 'required|string|max:255',
        ]);

        $profile = auth()->user();
        $doctor = Doctor::where('account_id', $profile->id)->first();
        $availability = Doctor_availabilities::where('doctor_id', $profile->id)->first();
        $availabilityDate = $availability->date;
        $readableDate = date('F j, Y', strtotime($availabilityDate));

        $availabilitytUpdatedData = [
            'type' => $request->input('availability'),
            'reason' => $request->input('reason'),
        ];

        $availabilityChange = $this->hasChanges($availability, $availabilitytUpdatedData);

        if ($availabilityChange == false) {

            return redirect()->route('doctor.appointment.calendar')->with('info', "Your availability hasn't changed.");

        } else {

            $availability->type = $request->input('availability');
            $availability->reason = $request->input('reason');
            $availability->save();


            if ($availability->type == 'available') {

                $availability = Doctor_availabilities::where('id', $availability->id)->first();
                $availability->delete();

            }

            return redirect()->route('doctor.appointment.calendar')->with('success', 'Your availability is now set to ' . $request->availability . ' on ' . $readableDate);
        }
    }

    public function patientList()
    {
        $profile = auth()->user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('specialties', $info->specialties)
            ->where('type', 'doctor')
            ->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = User::where('role', 'doctor')->get();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $patients = Patient::where('physician', $profile->id)->paginate(5);

        return view('doctor.patient.patient', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count', 'info', 'currentTime', 'currentDate'));
    }

    public function admittedPatientList()
    {
        $profile = auth()->user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('specialties', $info->specialties)
            ->where('type', 'doctor')
            ->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = User::where('role', 'doctor')->get();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $patients = Patient::where('physician', $profile->id)
            ->where('type', 'admitted_patient')
            ->paginate(5);

        return view('doctor.patient.patient_admitted', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count', 'info', 'currentTime', 'currentDate'));
    }

    public function outpatientList()
    {
        $profile = auth()->user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('specialties', $info->specialties)
            ->where('type', 'doctor')
            ->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = User::where('role', 'doctor')->get();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $patients = Patient::where('physician', $profile->id)
            ->where('type', 'outpatient')
            ->paginate(5);

        return view('doctor.patient.patient_outpatient', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count', 'info', 'currentTime', 'currentDate'));
    }

    public function patientSearch(Request $request)
    {
        $profile = auth()->user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('specialties', $info->specialties)
            ->where('type', 'doctor')
            ->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = User::where('role', 'doctor')->get();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $searchTerm = $request->input('search');

        $patients = Patient::where('physician', $profile->id)
            ->where(function ($query) use ($searchTerm) {
                $query->orWhere('first_name', 'LIKE', '%' . $searchTerm . '%');
                $query->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%');
                $query->orWhere('diagnosis', 'LIKE', '%' . $searchTerm . '%');
            })->paginate(5);

        return view('doctor.patient.patient_search', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count', 'info', 'currentTime', 'currentDate'));
    }

    public function admittedPatientSearch(Request $request)
    {
        $profile = auth()->user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('specialties', $info->specialties)
            ->where('type', 'doctor')
            ->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = User::where('role', 'doctor')->get();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $searchTerm = $request->input('search');

        $patients = Patient::where('physician', $profile->id)
            ->where('type', 'admitted_patient')
            ->where(function ($query) use ($searchTerm) {
                $query->orWhere('first_name', 'LIKE', '%' . $searchTerm . '%');
                $query->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%');
                $query->orWhere('diagnosis', 'LIKE', '%' . $searchTerm . '%');
            })->paginate(5);

        return view('doctor.patient.patient_admitted_search', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count', 'info', 'currentTime', 'currentDate'));
    }

    public function outpatientSearch(Request $request)
    {
        $profile = auth()->user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('specialties', $info->specialties)
            ->where('type', 'doctor')
            ->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = User::where('role', 'doctor')->get();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $searchTerm = $request->input('search');

        $patients = Patient::where('physician', $profile->id)
            ->where('type', 'outpatient')
            ->where(function ($query) use ($searchTerm) {
                $query->orWhere('first_name', 'LIKE', '%' . $searchTerm . '%');
                $query->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%');
                $query->orWhere('diagnosis', 'LIKE', '%' . $searchTerm . '%');
            })->paginate(5);

        return view('doctor.patient.patient_outpatient_search', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count', 'info', 'currentTime', 'currentDate'));
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
    public function appointmentReport(Request $request)
    {
        $profile = auth()->user();
        $appointment = Appointment::where('id', $request->input('appointment_id'))->first();
        $currentYear = Carbon::now()->year; // Get current year
        $currentDate = date('Y-m-d');
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $doctor = User::where('id', $appointment->doctor_id)->first();
        $randomNumber = mt_rand(100, 999);
        $reference = 'DAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

        return view('doctor.report.appointment_report', compact('appointment', 'currentTime', 'currentDate', 'doctor', 'profile', 'reference'));
    }

    public function saveReport(Request $request)
    {
        $reference = $request->input('reference');
        $time = $request->input('time');
        $date = $request->input('date');
        $readableDate = date('F j, Y', strtotime($date));
        $appointment = Appointment::where('id', $request->input('appointment_id'))->first();
        $appointmentDate = date('F j, Y', strtotime($appointment->appointment_date));

        $doctor = User::where('id', $appointment->doctor_id)->first();
        $profile = auth()->user();
        $content =
            '             Appointment Report
            ------------------------

            Report Reference Number: '.$reference.'
            Report Date and Time: '.$readableDate.' '. $time .'

            Patient Information:
            - Name: '.$appointment->first_name.' '. $appointment->last_name .'
            - Date of Birth: '.$appointment->birthdate.'
              Address:
                - Street: '.$appointment->street.'
                - Brgy: '.$appointment->brgy.'
                - City: '.$appointment->city.'
                - Province '.$appointment->province.'
              Contact Information: 
                - Email: '.$appointment->email.'
                - Phone: '.$appointment->phone.'
            
            Appointment Details:
            - Appointment Date and Time: '.$appointmentDate.' '. $appointment->appointment_time.'
            - Reason for Visit: '.$appointment->appointment_type.'
            - Doctor: Dr. '.$doctor->first_name.' '.$doctor->last_name.'
            - Appointment Status: '.$appointment->status.'

            Report Status: Finalized';

        Report::create([
            'reference_number' => $request->input('reference'),
            'report_type' => 'Doctor appointment report',
            'date' => $request->input('date'),
            'time' => $request->input('time'),
            'user_id' => $profile->id,
            'author_type' => $profile->role,
            'content' => $content,
        ]);

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
        $notifications = Notification::where('specialties', $info->specialties)
            ->where('type', 'doctor')
            ->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = Doctor::all();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $appointments = Appointment::where('doctor_id', $profile->id)
            ->orWhere('specialties', $info->specialties)
            ->orWhereNull('doctor_id')
            ->orderByRaw("TIME_FORMAT(appointment_time, '%h:%i %p') DESC, appointment_date DESC")
            ->paginate(5);

        return redirect()->route('doctor.appointment', compact('appointments', 'profile', 'doctors', 'amTime', 'pmTime', 'limitNotifications', 'count', 'info', 'currentTime', 'currentDate'));
    }

    public function notification()
    {

        $profile = Auth::user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('specialties', $info->specialties)
            ->where('type', 'doctor')
            ->orderBy('created_at', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        return view('doctor.notification.notification', compact('profile', 'notifications', 'limitNotifications', 'count', 'info', 'currentTime', 'currentDate'));

    }

    public function notificationRead(Request $request)
    {

        $notification = Notification::findOrFail($request->input('id'));

        if ($notification->is_read == 0) {
            $notification->is_read = 1;
            $notification->save();

            return redirect()->back();

        } else {
            return redirect()->back();
        }

    }

    public function deleteNotification(Request $request)
    {
        $notification = Notification::where('id', $request->input('id'))->first();
        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted successfully');
    }

    public function deleteNotificationAll(Request $request)
    {
        $profile = auth()->user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('specialties', $info->specialties)
            ->where('type', 'doctor')
            ->orderBy('date', 'desc')->get();

        if ($notifications->isEmpty()) {
            return redirect()->back()->with('info', 'No notification to delete.');

        } else {

            foreach ($notifications as $notification) {
                $notification->delete();
            }

            return redirect()->back()->with('success', 'User deleted successfully');
        }
    }

    public function deleteAllNotification(Request $request)
    {

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

    public function doctorLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}