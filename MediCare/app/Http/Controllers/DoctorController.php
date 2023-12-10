<?php

namespace App\Http\Controllers;

use DateTime;
use DateInterval;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Report;
use App\Models\Patient;
use App\Models\Diagnose;
use Illuminate\View\View;
use App\Models\Medication;
use App\Models\Appointment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Doctor_availabilities;
use Illuminate\Http\RedirectResponse;
use TCPDF;


class DoctorController extends Controller
{
    public function dashboard()
    {

        $profile = auth()->user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('account_id', $profile->id)
            ->orderBy('date', 'desc')->get();
        $notificationsAlert = Notification::where('account_id', $profile->id)
            ->where('is_read', 0)->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $appointments = Appointment::all();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        // Get the current year
        $currentYear = Carbon::now()->year;

        $admittedPatientsByMonth = DB::table('patients')
            ->select(DB::raw('DATE_FORMAT(admitted_date, "%M") as month'), DB::raw('COUNT(*) as count'))
            ->where('physician', $profile->id)
            ->whereYear('admitted_date', $currentYear)
            ->groupBy('month')
            ->get();

        $outpatientPatientsByMonth = DB::table('patients')
            ->select(DB::raw('DATE_FORMAT(date, "%M") as month'), DB::raw('COUNT(*) as count'))
            ->where('physician', $profile->id)
            ->whereYear('date', $currentYear)
            ->groupBy('month')
            ->get();


        $patientsByYear = DB::table('patients')
            ->where('physician', $profile->id)
            ->whereYear('admitted_date', $currentYear)
            ->orWhereYear('date', $currentYear)
            ->get();

        $patientCount = $patientsByYear->count();

        $currentMonth = Carbon::now()->month;

        // Retrieve appointments for the specific doctor in the current month
        $currentMonthAppointments = DB::table('appointments')
            ->where('doctor_id', $profile->id)
            ->whereMonth('appointment_date', $currentMonth)
            ->orderBy('appointment_date', 'desc')
            ->get();

        $currentAppointmentCount = $currentMonthAppointments->count();
        $limitCurrentMonthAppointments = $currentMonthAppointments->take(3);

        // Retrieve the monthly appointments for the specific doctor for the current year
        $monthlyAppointments = DB::table('appointments')
            ->select(DB::raw('MONTH(appointment_date) as month'), DB::raw('COUNT(*) as count'))
            ->where('doctor_id', $profile->id)
            ->whereYear('appointment_date', $currentYear)
            ->groupBy('month')
            ->get();

        $appointmentCount = $monthlyAppointments->count();

        // Format the data for the line graph
        $months = [];
        $appointmentCounts = [];
        for ($month = 1; $month <= 12; $month++) {
            $months[] = Carbon::createFromFormat('m', $month)->format('F');
            $appointmentCounts[] = $monthlyAppointments->where('month', $month)->pluck('count')->first() ?? 0;
        }



        return view('doctor_dashboard', compact('profile', 'limitNotifications', 'count', 'info', 'admittedPatientsByMonth', 'outpatientPatientsByMonth', 'patientCount', 'limitCurrentMonthAppointments', 'months', 'appointmentCounts', 'currentAppointmentCount', 'appointmentCount', 'currentDate', 'currentTime', 'notificationsAlert'));
    }

    public function profile(Request $request): View
    {
        $profile = $request->user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('account_id', $profile->id)
            ->orderBy('date', 'desc')->get();
        $notificationsAlert = Notification::where('account_id', $profile->id)
            ->where('is_read', 0)->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        return view('doctor.profile.profile', compact('profile', 'info', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'notificationsAlert'));
    }

    public function socialProfile(Request $request): View
    {

        $profile = $request->user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('account_id', $profile->id)
            ->orderBy('date', 'desc')->get();
        $notificationsAlert = Notification::where('account_id', $profile->id)
            ->where('is_read', 0)->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        return view('doctor.profile.profile_social', compact('profile', 'info', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'notificationsAlert'));
    }

    public function passwordProfile(Request $request): View
    {
        $profile = $request->user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('account_id', $profile->id)
            ->orderBy('date', 'desc')->get();
        $notificationsAlert = Notification::where('account_id', $profile->id)
            ->where('is_read', 0)->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        return view('doctor.profile.profile_password', compact('profile', 'limitNotifications', 'count', 'info', 'currentTime', 'currentDate', 'notificationsAlert'));
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
        $notifications = Notification::where('account_id', $profile->id)
            ->orderBy('date', 'desc')->get();
        $notificationsAlert = Notification::where('account_id', $profile->id)
            ->where('is_read', 0)->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = Doctor::all();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $appointments = Appointment::where('doctor_id', $profile->id)
            ->orderByRaw("TIME_FORMAT(appointment_time, '%h:%i %p') DESC, appointment_date DESC")
            ->get();

        return view('doctor.appointment.appointment', compact('appointments', 'profile', 'doctors', 'amTime', 'pmTime', 'limitNotifications', 'count', 'info', 'currentTime', 'currentDate', 'notificationsAlert'));
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
        $notifications = Notification::where('account_id', $profile->id)
            ->orderBy('date', 'desc')->get();
        $notificationsAlert = Notification::where('account_id', $profile->id)
            ->where('is_read', 0)->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = Doctor::all();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $appointments = Appointment::where('doctor_id', $profile->id)
            ->where('status', 'confirmed')
            ->orderBy('appointment_date', 'desc')->get();

        return view('doctor.appointment.confirmed_appointment', compact('appointments', 'profile', 'doctors', 'amTime', 'pmTime', 'limitNotifications', 'count', 'info', 'currentTime', 'currentDate', 'notificationsAlert'));

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
        $notifications = Notification::where('account_id', $profile->id)
            ->orderBy('date', 'desc')->get();
        $notificationsAlert = Notification::where('account_id', $profile->id)
            ->where('is_read', 0)->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = Doctor::all();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $appointments = Appointment::where('doctor_id', $profile->id)
            ->where('status', 'done')->get();

        return view('doctor.appointment.done_appointment', compact('appointments', 'profile', 'doctors', 'amTime', 'pmTime', 'limitNotifications', 'count', 'info', 'currentTime', 'currentDate', 'notificationsAlert'));
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
        $message = ' Your appointment that has ' . $appointment->appointment_type . ' that dated ' . $appointment->appointment_date . ' and timed ' . $appointment->appointment_time . ' is confirmed by Dr. ' . $profile->first_name . ' ' . $profile->last_name;

        Notification::create([
            'account_id' => $appointment->account_id,
            'title' => 'Appointment confirmed',
            'message' => $message,
            'date' => $currentDate,
            'time' => $currentTime,
            'type' => 'user',
        ]);

        $message = 'You have successfully confirmed the appointment for ' . $appointment->appointment_type . ' scheduled on ' . $appointment->appointment_date . ' at ' . $appointment->appointment_time . '.';

        Notification::create([
            'account_id' => $appointment->doctor_id,
            'title' => 'Appointment confirmed',
            'message' => $message,
            'date' => $currentDate,
            'time' => $currentTime,
            'type' => 'doctor',
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

        $message = 'You have successfully done the appointment for ' . $appointment->appointment_type . ' scheduled on ' . $appointment->appointment_date . ' at ' . $appointment->appointment_time . '.';

        Notification::create([
            'account_id' => $appointment->doctor_id,
            'title' => 'Appointment Done',
            'message' => $message,
            'date' => $currentDate,
            'time' => $currentTime,
            'type' => 'doctor',
        ]);


        return redirect()->back()->with('success', 'Appointment Done successfully.');
    }

    public function cancelAppointment(Request $request)
    {
        $appointment = Appointment::findOrFail($request->input('appointment_id'));

        $appointment->status = 'cancelled';
        $appointment->save();
        $currentTime = Carbon::now()->toTimeString();
        $currentDate = Carbon::now()->toDateString();

        $message = 'You have successfully cancel the appointment for ' . $appointment->appointment_type . ' scheduled on ' . $appointment->appointment_date . ' at ' . $appointment->appointment_time . '.';

        Notification::create([
            'account_id' => $appointment->doctor_id,
            'title' => 'Appointment Done',
            'message' => $message,
            'date' => $currentDate,
            'time' => $currentTime,
            'type' => 'doctor',
        ]);

        return redirect()->back()->with('success', 'Appointment Cancelled successfully.');
    }

    public function appointmentCalendar()
    {
        $profile = auth()->user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('account_id', $profile->id)
            ->orderBy('date', 'desc')->get();
        $notificationsAlert = Notification::where('account_id', $profile->id)
            ->where('is_read', 0)->get();
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


        return view('doctor.appointment.appointment_calendar', compact('profile', 'doctors', 'amTime', 'pmTime', 'limitNotifications', 'count', 'info', 'currentTime', 'currentDate', 'notificationsAlert'));
    }

    public function appointmentEvents()
    {
        $user = Auth::user();
        $info = Doctor::where('account_id', $user->id)->first();
        $appointments = Appointment::where(function ($query) use ($user) {
            $query->where('doctor_id', $user->id)
                ->orWhereNull('doctor_id');
        })
            ->whereNotIn('status', ['cancelled', 'unavailable'])
            ->get();

        $events = [];
        foreach ($appointments as $appointment) {

            $appointmentDateTime = DateTime::createFromFormat('Y-m-d h:i A', $appointment->appointment_date . ' ' . $appointment->appointment_time);

            // Calculate the end time by adding a fixed duration (e.g., 1 hour)
            $endDateTime = clone $appointmentDateTime;
            $interval = new DateInterval('PT30M'); // PT30M represents 30 minutes
            $endDateTime->add($interval);

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
        $message = ' Your appointment that has ' . $appointment->appointment_type . ' that dated ' . $appointment->appointment_date . ' and timed ' . $appointment->appointment_time . ' is confirmed by Dr. ' . $profile->first_name . ' ' . $profile->last_name;

        Notification::create([
            'account_id' => $appointment->account_id,
            'title' => 'Appointment Confirmed',
            'message' => $message,
            'date' => $currentDate,
            'time' => $currentTime,
            'type' => 'user',
        ]);

        $message = 'You have successfully confirmed the appointment for ' . $appointment->appointment_type . ' scheduled on ' . $appointment->appointment_date . ' at ' . $appointment->appointment_time . '.';

        Notification::create([
            'account_id' => $appointment->doctor_id,
            'title' => 'Appointment Confirmed',
            'message' => $message,
            'date' => $currentDate,
            'time' => $currentTime,
            'type' => 'doctor',
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

        $message = 'You have successfully done the appointment for ' . $appointment->appointment_type . ' scheduled on ' . $appointment->appointment_date . ' at ' . $appointment->appointment_time . '.';

        Notification::create([
            'account_id' => $appointment->doctor_id,
            'title' => 'Appointment Done',
            'message' => $message,
            'date' => $currentDate,
            'time' => $currentTime,
            'type' => 'doctor',
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

    public function getDiagnoses($id)
    {
        $diagnoses = Diagnose::where('patient_id', $id)->get();

        return response()->json($diagnoses);
    }

    public function getMedications($id)
    {
        $medications = Medication::where('patient_id', $id)->get();

        return response()->json($medications);
    }

    public function patientList()
    {
        $profile = auth()->user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('account_id', $profile->id)
            ->orderBy('date', 'desc')->get();
        $notificationsAlert = Notification::where('account_id', $profile->id)
            ->where('is_read', 0)->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = User::where('role', 'doctor')->get();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $patients = Patient::where('physician', $profile->id)->orderBy('created_at', 'desc')->get();

        return view('doctor.patient.patient', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count', 'info', 'currentTime', 'currentDate', 'notificationsAlert'));
    }

    public function admittedPatientList()
    {
        $profile = auth()->user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('account_id', $profile->id)
            ->orderBy('date', 'desc')->get();
        $notificationsAlert = Notification::where('account_id', $profile->id)
            ->where('is_read', 0)->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = User::where('role', 'doctor')->get();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $patients = Patient::where('physician', $profile->id)
            ->where('type', 'admitted_patient')
            ->get();

        return view('doctor.patient.patient_admitted', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count', 'info', 'currentTime', 'currentDate', 'notificationsAlert'));
    }

    public function outpatientList()
    {
        $profile = auth()->user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('account_id', $profile->id)
            ->orderBy('date', 'desc')->get();
        $notificationsAlert = Notification::where('account_id', $profile->id)
            ->where('is_read', 0)->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = User::where('role', 'doctor')->get();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $patients = Patient::where('physician', $profile->id)
            ->where('type', 'outpatient')
            ->get();

        return view('doctor.patient.patient_outpatient', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count', 'info', 'currentTime', 'currentDate', 'notificationsAlert'));
    }

    public function patientUpdate(Request $request)
    {
        $patient = Patient::where('id', $request->id)->first();

        switch ($patient) {
            case $patient->type == 'outpatient':

                $patientUpdatedData = [
                    'medical_condition' => $request->input('medical_condition'),
                ];

                // Retrieve the request data
                $diagnosisDates = $request->input('diagnosesDate');
                $diagnosisTimes = $request->input('diagnosesTime');
                $diagnoses = $request->input('diagnoses');

                // Retrieve the existing data from the database
                $existingDiagnoses = Diagnose::where('patient_id', $request->id)->get();

                // Initialize a boolean variable to track changes
                $diagnoseChangesDetected = false;

                foreach ($diagnoses as $index => $newDiagnosis) {
                    $existingDiagnosis = $existingDiagnoses->get($index);
                    $newDiagnoseDate = $diagnosisDates[$index];
                    $newDiagnoseTime = $diagnosisTimes[$index];

                    // Check if an existing record exists for this index
                    if ($existingDiagnosis) {
                        // Compare both the new diagnosis and new diagnoseDate with the existing ones
                        if ($this->hasChanges($existingDiagnosis, ['diagnose' => $newDiagnosis, 'date' => $newDiagnoseDate, 'time' => $newDiagnoseTime])) {
                            // At least one of the fields has been updated
                            $diagnoseChangesDetected = true;
                            // You can log or perform other actions here

                            // Update the existing record with the new data
                            $existingDiagnosis->diagnose = $newDiagnosis;
                            $existingDiagnosis->date = $newDiagnoseDate;
                            $existingDiagnosis->time = $newDiagnoseTime;
                            $existingDiagnosis->save(); // Save the changes to the database
                        }
                    } else {
                        // No existing record for this index, this may mean a new diagnosis was added
                        // Handle new diagnoses here if needed
                        $newDiagnosisRecord = new Diagnose(); // Assuming Diagnosis is your Eloquent model or equivalent
                        $newDiagnosisRecord->patient_id = $patient->id; // Assuming Diagnosis is your Eloquent model or equivalent
                        $newDiagnosisRecord->diagnose = $newDiagnosis;
                        $newDiagnosisRecord->date = $newDiagnoseDate;
                        $newDiagnosisRecord->time = $newDiagnoseTime;
                        $newDiagnosisRecord->save(); // Save the new dia
                        $diagnoseChangesDetected = true;
                    }
                }

                // Retrieve the request data
                $medicationNames = $request->input('medicationName');
                $medicationDates = $request->input('medicationDate');
                $dosages = $request->input('medicationDosage');
                $durations = $request->input('medicationDuration');
                $medicationTimes = $request->input('medicationTime');

                // Retrieve the existing medication data from the database
                $existingMedications = Medication::where('patient_id', $request->id)->get();

                // Initialize a boolean variable to track changes
                $medicationChangesDetected = false;

                foreach ($medicationNames as $index => $newMedicationName) {
                    $existingMedication = $existingMedications->get($index);
                    $newMedicationDate = $medicationDates[$index];
                    $newDosage = $dosages[$index];
                    $newDuration = $durations[$index];
                    $newMedicationTime = $medicationTimes[$index];

                    // Check if an existing record exists for this index
                    if ($existingMedication) {
                        // Compare both the new medication data with the existing ones
                        if (
                            $this->hasChanges($existingMedication, [
                                'medication_name' => $newMedicationName,
                                'date' => $newMedicationDate,
                                'dosage' => $newDosage,
                                'duration' => $newDuration,
                                'time' => $newMedicationTime,
                            ])
                        ) {
                            // At least one of the fields has been updated
                            $medicationChangesDetected = true;
                            // You can log or perform other actions here

                            // Update the existing record with the new data
                            $existingMedication->medication_name = $newMedicationName;
                            $existingMedication->date = $newMedicationDate;
                            $existingMedication->dosage = $newDosage;
                            $existingMedication->duration = $newDuration;
                            $existingMedication->time = $newMedicationTime;
                            $existingMedication->save(); // Save the changes to the database
                        }
                    } else {
                        // No existing record for this index, this may mean a new medication was added
                        $newMedicationRecord = new Medication(); // Assuming Medication is your Eloquent model or equivalent
                        $newMedicationRecord->patient_id = $patient->id; // Assuming Medication is your Eloquent model or equivalent
                        $newMedicationRecord->medication_name = $newMedicationName;
                        $newMedicationRecord->date = $newMedicationDate;
                        $newMedicationRecord->dosage = $newDosage;
                        $newMedicationRecord->duration = $newDuration;
                        $newMedicationRecord->time = $newMedicationTime;
                        $newMedicationRecord->save(); // Save the new medication
                        $medicationChangesDetected = true;
                    }
                }

                $patientChange = $this->hasChanges($patient, $patientUpdatedData);

                if ($patientChange || $diagnoseChangesDetected || $medicationChangesDetected) {
                    $patient->medical_condition = $request->input('medical_condition');

                    $patient->save();

                    return redirect()->back()->with('success', 'Patient Information Updated Successfully.');
                } else {
                    return redirect()->back()->with('info', 'No changes were made.');
                }

            case $patient->type == 'admitted_patient':

                $patientUpdatedData = [
                    'medical_condition' => $request->input('medical_condition'),
                ];

                // Retrieve the request data
                $diagnosisDates = $request->input('diagnosesDate');
                $diagnosisTimes = $request->input('diagnosesTime');
                $diagnoses = $request->input('diagnoses');

                // Retrieve the existing data from the database
                $existingDiagnoses = Diagnose::where('patient_id', $request->id)->get();

                // Initialize a boolean variable to track changes
                $diagnoseChangesDetected = false;

                foreach ($diagnoses as $index => $newDiagnosis) {
                    $existingDiagnosis = $existingDiagnoses->get($index);
                    $newDiagnoseDate = $diagnosisDates[$index];
                    $newDiagnoseTime = $diagnosisTimes[$index];

                    // Check if an existing record exists for this index
                    if ($existingDiagnosis) {
                        // Compare both the new diagnosis and new diagnoseDate with the existing ones
                        if ($this->hasChanges($existingDiagnosis, ['diagnose' => $newDiagnosis, 'date' => $newDiagnoseDate, 'time' => $newDiagnoseTime])) {
                            // At least one of the fields has been updated
                            $diagnoseChangesDetected = true;
                            // You can log or perform other actions here

                            // Update the existing record with the new data
                            $existingDiagnosis->diagnose = $newDiagnosis;
                            $existingDiagnosis->date = $newDiagnoseDate;
                            $existingDiagnosis->time = $newDiagnoseTime;
                            $existingDiagnosis->save(); // Save the changes to the database
                        }
                    } else {
                        // No existing record for this index, this may mean a new diagnosis was added
                        // Handle new diagnoses here if needed
                        $newDiagnosisRecord = new Diagnose(); // Assuming Diagnosis is your Eloquent model or equivalent
                        $newDiagnosisRecord->patient_id = $patient->id; // Assuming Diagnosis is your Eloquent model or equivalent
                        $newDiagnosisRecord->diagnose = $newDiagnosis;
                        $newDiagnosisRecord->date = $newDiagnoseDate;
                        $newDiagnosisRecord->time = $newDiagnoseTime;
                        $newDiagnosisRecord->save(); // Save the new dia
                        $diagnoseChangesDetected = true;
                    }
                }

                // Retrieve the request data
                $medicationNames = $request->input('medicationName');
                $medicationDates = $request->input('medicationDate');
                $dosages = $request->input('medicationDosage');
                $durations = $request->input('medicationDuration');
                $medicationTimes = $request->input('medicationTime');

                // Retrieve the existing medication data from the database
                $existingMedications = Medication::where('patient_id', $request->id)->get();

                // Initialize a boolean variable to track changes
                $medicationChangesDetected = false;

                foreach ($medicationNames as $index => $newMedicationName) {
                    $existingMedication = $existingMedications->get($index);
                    $newMedicationDate = $medicationDates[$index];
                    $newDosage = $dosages[$index];
                    $newDuration = $durations[$index];
                    $newMedicationTime = $medicationTimes[$index];

                    // Check if an existing record exists for this index
                    if ($existingMedication) {
                        // Compare both the new medication data with the existing ones
                        if (
                            $this->hasChanges($existingMedication, [
                                'medication_name' => $newMedicationName,
                                'date' => $newMedicationDate,
                                'dosage' => $newDosage,
                                'duration' => $newDuration,
                                'time' => $newMedicationTime,
                            ])
                        ) {
                            // At least one of the fields has been updated
                            $medicationChangesDetected = true;
                            // You can log or perform other actions here

                            // Update the existing record with the new data
                            $existingMedication->medication_name = $newMedicationName;
                            $existingMedication->date = $newMedicationDate;
                            $existingMedication->dosage = $newDosage;
                            $existingMedication->duration = $newDuration;
                            $existingMedication->time = $newMedicationTime;
                            $existingMedication->save(); // Save the changes to the database
                        }
                    } else {
                        // No existing record for this index, this may mean a new medication was added
                        $newMedicationRecord = new Medication(); // Assuming Medication is your Eloquent model or equivalent
                        $newMedicationRecord->patient_id = $patient->id; // Assuming Medication is your Eloquent model or equivalent
                        $newMedicationRecord->medication_name = $newMedicationName;
                        $newMedicationRecord->date = $newMedicationDate;
                        $newMedicationRecord->dosage = $newDosage;
                        $newMedicationRecord->duration = $newDuration;
                        $newMedicationRecord->time = $newMedicationTime;
                        $newMedicationRecord->save(); // Save the new medication
                        $medicationChangesDetected = true;
                    }
                }

                $patientChange = $this->hasChanges($patient, $patientUpdatedData);

                if ($patientChange || $diagnoseChangesDetected || $medicationChangesDetected) {

                    $patient->medical_condition = $request->input('medical_condition');

                    $patient->save();

                    return redirect()->back()->with('success', 'Patient Information Updated Successfully.');
                } else {
                    return redirect()->back()->with('info', 'No changes were made.');
                }
        }
    }

    public function viewAppointmentReport(Request $request)
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

        $data = [
            'appointment' => $appointment,
            'currentTime' => $currentTime,
            'currentDate' => $currentDate,
            'profile' => $profile,
            'doctor' => $doctor,
            'reference' => $reference,
        ];

        // Create new PDF document
        $pdf = new TCPDF();
        // Add a page
        $pdf->AddPage();
        $pdf->SetPrintHeader(false);

        // Read HTML content from a file
        $htmlFilePath = resource_path('views/doctor/report/appointment_report.blade.php');
        $htmlContent = view()->file($htmlFilePath, $data)->render();

        // Set content with HTML
        $pdf->writeHTML($htmlContent);

        // Output PDF to browser
        $pdf->Output($reference . '.pdf', 'I');

    }

    public function downloadAppointmentReport(Request $request)
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

        $readableDate = date('F j, Y', strtotime($currentDate));
        $appointment = Appointment::where('id', $request->input('appointment_id'))->first();
        $appointmentDate = date('F j, Y', strtotime($appointment->appointment_date));

        $doctor = User::where('id', $appointment->doctor_id)->first();
        $profile = auth()->user();
        $content =
            '             Appointment Report
            ------------------------

            Report Reference Number: ' . $reference . '
            Report Date and Time: ' . $readableDate . ' ' . $currentTime . '

            Patient Information:
            - Name: ' . $appointment->first_name . ' ' . $appointment->last_name . '
            - Date of Birth: ' . $appointment->birthdate . '
              Address:
                - Street: ' . $appointment->street . '
                - Brgy: ' . $appointment->brgy . '
                - City: ' . $appointment->city . '
                - Province ' . $appointment->province . '
              Contact Information: 
                - Email: ' . $appointment->email . '
                - Phone: ' . $appointment->phone . '
            
            Appointment Details:
            - Appointment Date and Time: ' . $appointmentDate . ' ' . $appointment->appointment_time . '
            - Reason for Visit: ' . $appointment->appointment_type . '
            - Doctor: Dr. ' . $doctor->first_name . ' ' . $doctor->last_name . '
            - Appointment Status: ' . $appointment->status . '

            Report Status: Finalized';

        Report::create([
            'reference_number' => $reference,
            'report_type' => 'Doctor appointment report',
            'date' => $currentDate,
            'time' => $currentTime,
            'user_id' => $profile->id,
            'author_type' => $profile->role,
            'content' => $content,
        ]);

        $data = [
            'appointment' => $appointment,
            'currentTime' => $currentTime,
            'currentDate' => $currentDate,
            'profile' => $profile,
            'doctor' => $doctor,
            'reference' => $reference,
        ];

        // Create new PDF document
        $pdf = new TCPDF();
        // Add a page
        $pdf->AddPage();
        $pdf->SetPrintHeader(false);

        // Read HTML content from a file
        $htmlFilePath = resource_path('views/doctor/report/appointment_report.blade.php');
        $htmlContent = view()->file($htmlFilePath, $data)->render();

        // Set content with HTML
        $pdf->writeHTML($htmlContent);

        // Output PDF to browser
        $pdf->Output($reference . '.pdf', 'D');
    }

    public function notification()
    {

        $profile = Auth::user();
        $info = Doctor::where('account_id', $profile->id)->first();
        $notifications = Notification::where('account_id', $profile->id)
            ->orderBy('date', 'desc')->get();
        $notificationsAlert = Notification::where('account_id', $profile->id)
            ->where('is_read', 0)->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        return view('doctor.notification.notification', compact('profile', 'notifications', 'limitNotifications', 'count', 'info', 'currentTime', 'currentDate', 'notificationsAlert'));

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