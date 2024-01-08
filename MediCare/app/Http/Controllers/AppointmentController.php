<?php

namespace App\Http\Controllers;

use DateTime;
use DateInterval;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Holiday;
use App\Models\Appointment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Doctor_availabilities;

class AppointmentController extends Controller
{
    public function showAppointment()
    {
        $user = Auth::user();
        // Retrieve all the appointments and group them by appointment time and specialties

        $timeList = [
            '8:30 AM',
            '9:00 AM',
            '9:30 AM',
            '10:30 AM',
            '11:00 AM',
            '11:30 AM',
            '1:30 PM',
            '2:00 PM',
            '2:30 PM',
            '3:00 PM',
            '3:30 PM',
            '4:00 PM',
        ];

        $users = User::where('role', 'doctor')->get();
        $infos = Doctor::all();
        $availability = Doctor_availabilities::all();

        // Fetch appointment data here
        $appointments = Appointment::where('account_id', $user->id)->get(); // Replace with your own query to fetch the data
        $notificationsAlert = Notification::where('account_id', $user->id)->where('is_read', 0)->get();

        $events = [];
        foreach ($appointments as $appointment) {
            $events[] = [
                'title' => $appointment->title,
                // Replace with the field containing the label
                'start' => $appointment->date,
                // Replace with the field containing the date
            ];
        }

        return view('user.appointment.appointment_create', compact('users', 'infos', 'timeList', 'availability', 'notificationsAlert'))->with('events', json_encode($events));
    }

    public function appointmentEvents()
    {

        $user = Auth::user();
        $appointments = Appointment::where('account_id', $user->id)
            ->where(function ($query) {
                $query->whereNotIn('status', ['cancelled', 'unavailable']);
            })
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
                'start' => $appointmentDateTime->format('Y-m-d H:i:s'),
                'end' => $endDateTime->format('Y-m-d H:i:s'),
                'status' => ucwords($appointment->status),
                'type' => 'appointment',
            ];
        }

        return response()->json($events);
    }

    public function holidayEvents()
    {
        $holidays = Holiday::all();

        $events = [];
        foreach ($holidays as $holiday) {
            $start = Carbon::parse($holiday->date)->format('Y-m-d H:i:s');
            $end = Carbon::parse($holiday->date)->endOfDay()->format('Y-m-d H:i:s');

            $events[] = [
                'holiday_id' => $holiday->id,
                'title' => ucwords($holiday->name),
                'start' => $start,
                'end' => $end,
                'type' => 'holiday',
            ];
        }

        return response()->json($events);

    }

    public function doctorSpecialties(Request $request)
    {
        $selectedDate = $request->input('date');
        $doctors = Doctor::all();
        $availability = Doctor_availabilities::all();

        $availableDoctors = [];

        foreach ($doctors as $doctor) {
            $isAvailable = true;
            foreach ($availability as $avail) {
                if ($doctor->account_id == $avail->doctor_id && $selectedDate == $avail->date) {
                    $isAvailable = false;
                    break; // No need to check further if doctor is unavailable on this date
                }
            }

            if ($isAvailable) {
                $user = User::where('id', $doctor->account_id)->first();

                $availableDoctors[] = [
                    'id' => $doctor->account_id,
                    'firstName' => $user->first_name,
                    'lastName' => $user->last_name,
                    'specialty' => $doctor->specialties,
                ];
            }
        }

        usort($availableDoctors, function ($a, $b) {
            return strcmp($a['specialty'], $b['specialty']);
        });


        return response()->json($availableDoctors);
    }

    public function getAvailableTime(Request $request)
    {
        $selectedDate = $request->input('selectedDate');
        $selectedSpecialty = $request->input('selectedSpecialty');

        // Fetch all appointment times for the selected date and specialty
        $appointments = Appointment::where('appointment_date', $selectedDate)
            ->where('doctor_id', $selectedSpecialty)
            ->where('status', 'pending')
            ->orWhere('status', 'confirmed')
            ->get();

        $availableTimes = [
            '8:30 AM',
            '9:00 AM',
            '9:30 AM',
            '10:30 AM',
            '11:00 AM',
            '11:30 AM',
            '1:30 PM',
            '2:00 PM',
            '2:30 PM',
            '3:00 PM',
            '3:30 PM',
            '4:00 PM',
        ];

        // Remove appointment times from available times
        foreach ($appointments as $appointment) {
            // Parse the appointment time to match the format "8 30 AM"
            $appointmentTime = $appointment->appointment_time;

            // Loop through the available times and remove the matching appointment time
            foreach ($availableTimes as $key => $availableTime) {
                if ($appointmentTime === $availableTime) {
                    unset($availableTimes[$key]);
                }
            }
        }


        // Return available times as JSON
        return response()->json(array_values($availableTimes));
    }

    public function createAppointment(Request $request)
    {
        $timeList = [
            '8:30 AM',
            '9:00 AM',
            '9:30 AM',
            '10:30 AM',
            '11:00 AM',
            '11:30 AM',
            '1:30 PM',
            '2:00 PM',
            '2:30 PM',
            '3:00 PM',
            '3:30 PM',
            '4:00 PM',
        ];

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
            'specialties' => 'required|string|max:255',
            'phone' => 'required',
            'appointment_type' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|string|max:255',
            'reason' => 'required|string|max:255',
            'check' => 'accepted',
        ]);

        $existingAppointments = Appointment::where('specialties', $request->input('specialties'))
            ->where('appointment_date', $request->input('appointment_date'))
            ->where('appointment_time', $request->input('appointment_time'))
            ->whereNotIn('status', ['unavailable', 'cancelled'])
            ->get();

        if ($existingAppointments->count() > 0) {

            $appoint_time = $existingAppointments->pluck('appointment_time');

            foreach ($appoint_time as $time) {
                $timeList = array_filter($timeList, function ($value) use ($time) {
                    return $value !== $time;
                });
            }

            $time = implode(', ', $timeList);
            $rawDate = $request->input('appointment_date');
            $dateTime = new DateTime($rawDate);
            $readableDate = $dateTime->format('F j, Y');

            return back()->with([
                'data' => $timeList,
                'info' => 'The current time is unavailable. Please select a date from ' . $readableDate . ' and choose from the available times: ' . $time . '.',
            ]);
        }

        $user = Auth::user();
        $doctor = Doctor::where('account_id', $request->input('specialties'))->first();

        Appointment::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'account_id' => $user->id,
            'street' => $request->input('street'),
            'gender' => $request->input('gender'),
            'brgy' => $request->input('brgy'),
            'city' => $request->input('city'),
            'province' => $request->input('province'),
            'doctor_id' => $request->input('specialties'),
            'specialties' => $doctor->specialties,
            'birthdate' => $request->input('birthdate'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'appointment_type' => $request->input('appointment_type'),
            'appointment_date' => $request->input('appointment_date'),
            'appointment_time' => $request->input('appointment_time'),
            'reason' => $request->input('reason'),
            'status' => 'pending',
        ]);

        $appointment = Appointment::latest()->first();
        $currentTime = Carbon::now()->toTimeString();
        $currentDate = Carbon::now()->toDateString();
        $rawDate = $request->input('appointment_date');
        $dateTime = new DateTime($rawDate);
        $readableDate = $dateTime->format('F j, Y');

        $message = 'You successfully created new appointment with ' . $appointment->appointment_type . ' scheduled for ' . $readableDate . ' at ' . $appointment->appointment_time . '.';

        Notification::create([
            'account_id' => $appointment->account_id,
            'title' => 'Appointment Created',
            'message' => $message,
            'date' => $currentDate,
            'time' => $currentTime,
            'type' => 'user',
            'specialties' => $request->input('specialties'),
        ]);

        $message = 'You have a new appointment from ' . ucwords($user->first_name) . ' ' . ucwords($user->last_name) . ' with ' . $appointment->appointment_type . ' scheduled for ' . $readableDate . ' at ' . $appointment->appointment_time . '.';

        Notification::create([
            'title' => 'New Appointment',
            'message' => $message,
            'date' => $currentDate,
            'time' => $currentTime,
            'type' => 'doctor',
            'account_id' => $request->input('specialties'),
        ]);



        return back()->with('success', 'Appointment created successfully for ' . $readableDate . ' at ' . $request->input('appointment_time'));
    }

    public function appointment()
    {

        $timeList = [
            '8:30 AM',
            '9:00 AM',
            '9:30 AM',
            '10:30 AM',
            '11:00 AM',
            '11:30 AM',
            '1:30 PM',
            '2:00 PM',
            '2:30 PM',
            '3:00 PM',
            '3:30 PM',
            '4:00 PM',
        ];

        $user = Auth::user();
        $doctors = Doctor::orderBy('specialties')->get();
        $infos = User::where('role', 'doctor')->get();
        $appointments = Appointment::where('account_id', $user->id)->orderBy('appointment_date', 'desc')->paginate(5);
        $notificationsAlert = Notification::where('account_id', $user->id)->where('is_read', 0)->get();

        return view('user.appointment.appointment', compact('appointments', 'infos', 'timeList', 'notificationsAlert', 'doctors'));
    }

    public function confirmedAppointmentList()
    {
        $timeList = [
            '8:30 AM',
            '9:00 AM',
            '9:30 AM',
            '10:30 AM',
            '11:00 AM',
            '11:30 AM',
            '1:30 PM',
            '2:00 PM',
            '2:30 PM',
            '3:00 PM',
            '3:30 PM',
            '4:00 PM',
        ];

        $user = Auth::user();
        $doctors = Doctor::orderBy('specialties')->get();
        $infos = User::where('role', 'doctor')->get();
        $appointments = Appointment::where('account_id', $user->id)->where('status', 'confirmed')->paginate(5);
        $notificationsAlert = Notification::where('account_id', $user->id)->where('is_read', 0)->get();

        return view('user.appointment.confirmed_appointment', compact('appointments', 'infos', 'timeList', 'doctors', 'notificationsAlert'));
    }

    public function doneAppointmentList()
    {

        $timeList = [
            '8:30 AM',
            '9:00 AM',
            '9:30 AM',
            '10:30 AM',
            '11:00 AM',
            '11:30 AM',
            '1:30 PM',
            '2:00 PM',
            '2:30 PM',
            '3:00 PM',
            '3:30 PM',
            '4:00 PM',
        ];


        $user = Auth::user();
        $doctors = Doctor::orderBy('specialties')->get();
        $infos = User::where('role', 'doctor')->get();
        $appointments = Appointment::where('account_id', $user->id)->where('status', 'done')->paginate(5);
        $notificationsAlert = Notification::where('account_id', $user->id)->where('is_read', 0)->get();

        return view('user.appointment.done_appointment', compact('appointments', 'infos', 'timeList', 'notificationsAlert', 'doctors'));
    }

    public function cancelledAppointmentList()
    {

        $timeList = [
            '8:30 AM',
            '9:00 AM',
            '9:30 AM',
            '10:30 AM',
            '11:00 AM',
            '11:30 AM',
            '1:30 PM',
            '2:00 PM',
            '2:30 PM',
            '3:00 PM',
            '3:30 PM',
            '4:00 PM',
        ];

        $user = Auth::user();
        $doctors = Doctor::orderBy('specialties')->get();
        $infos = User::where('role', 'doctor')->get();
        $appointments = Appointment::where('account_id', $user->id)->where('status', 'cancelled')->paginate(5);
        $notificationsAlert = Notification::where('account_id', $user->id)->where('is_read', 0)->get();

        return view('user.appointment.cancelled_appointment', compact('appointments', 'infos', 'timeList', 'notificationsAlert', 'doctors'));
    }

    public function unavailableAppointmentList()
    {

        $timeList = [
            '8:30 AM',
            '9:00 AM',
            '9:30 AM',
            '10:30 AM',
            '11:00 AM',
            '11:30 AM',
            '1:30 PM',
            '2:00 PM',
            '2:30 PM',
            '3:00 PM',
            '3:30 PM',
            '4:00 PM',
        ];

        $user = Auth::user();
        $infos = User::where('role', 'doctor')->get();
        $doctors = Doctor::orderBy('specialties')->get();
        $appointments = Appointment::where('account_id', $user->id)->where('status', 'unavailable')->paginate(5);
        $notificationsAlert = Notification::where('account_id', $user->id)->where('is_read', 0)->get();

        return view('user.appointment.unavailable_appointment', compact('appointments', 'infos', 'timeList', 'notificationsAlert', 'doctors'));
    }
    public function updateAppointment(Request $request)
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
            'email' => 'required|string|email|max:255',
            'phone' => 'required',
            'appointment_type' => 'required|string|max:255',
            'reason' => 'required|string|max:255',
        ]);

        $appointment = Appointment::where('id', $request->appointment_id)->first();

        $doctor = $request->filled('specialties');
        $date = $request->filled('appointment_date');
        $time = $request->filled('appointment_time');
        $status = $date || $doctor || $time;

        if (!$status) {
            $appointmentUpdatedData = [
                'first_name' => $request->input('first_name'),
                'middle_name' => $request->input('middle_name'),
                'last_name' => $request->input('last_name'),
                'street' => $request->input('street'),
                'brgy' => $request->input('brgy'),
                'city' => $request->input('city'),
                'province' => $request->input('province'),
                'birthdate' => $request->input('birthdate'),
                'gender' => $request->input('gender'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'appointment_type' => $request->input('appointment_type'),
                'reason' => $request->input('reason'),
            ];

        } else {
            $appointmentUpdatedData = [
                'first_name' => $request->input('first_name'),
                'middle_name' => $request->input('middle_name'),
                'last_name' => $request->input('last_name'),
                'street' => $request->input('street'),
                'brgy' => $request->input('brgy'),
                'city' => $request->input('city'),
                'province' => $request->input('province'),
                'birthdate' => $request->input('birthdate'),
                'gender' => $request->input('gender'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'appointment_date' => $request->input('appointment_date'),
                'appointment_time' => $request->input('appointment_time'),
                'doctor_id' => $request->input('specialties'),
                'appointment_type' => $request->input('appointment_type'),
                'reason' => $request->input('reason'),
            ];
        }

        $appointmentChange = $this->hasChanges($appointment, $appointmentUpdatedData);

        if ($appointmentChange) {

            if (!$status) {
                $appointment->update([
                    'first_name' => $request->input('first_name'),
                    'middle_name' => $request->input('middle_name'),
                    'last_name' => $request->input('last_name'),
                    'street' => $request->input('street'),
                    'brgy' => $request->input('brgy'),
                    'city' => $request->input('city'),
                    'province' => $request->input('province'),
                    'birthdate' => $request->input('birthdate'),
                    'gender' => $request->input('gender'),
                    'phone' => $request->input('phone'),
                    'email' => $request->input('email'),
                    'appointment_type' => $request->input('appointment_type'),
                    'reason' => $request->input('reason'),
                ]);


                $currentTime = Carbon::now()->toTimeString();
                $currentDate = Carbon::now()->toDateString();
                $rawDate = $request->input('appointment_date');
                $dateTime = new DateTime($rawDate);
                $readableDate = $dateTime->format('F j, Y');

                $message = 'You have successfully updated your appointment information for ' . $appointment->appointment_type . ' scheduled on ' . $readableDate . ' at ' . $appointment->appointment_time . '.';

                Notification::create([
                    'account_id' => $appointment->account_id,
                    'title' => 'Appointment Updated',
                    'message' => $message,
                    'date' => $currentDate,
                    'time' => $currentTime,
                    'type' => 'user',
                ]);

            } else {
                $appointment->update([
                    'first_name' => $request->input('first_name'),
                    'middle_name' => $request->input('middle_name'),
                    'last_name' => $request->input('last_name'),
                    'street' => $request->input('street'),
                    'brgy' => $request->input('brgy'),
                    'city' => $request->input('city'),
                    'province' => $request->input('province'),
                    'birthdate' => $request->input('birthdate'),
                    'gender' => $request->input('gender'),
                    'phone' => $request->input('phone'),
                    'email' => $request->input('email'),
                    'appointment_type' => $request->input('appointment_type'),
                    'appointment_date' => $request->input('appointment_date'),
                    'appointment_time' => $request->input('appointment_time'),
                    'doctor_id' => $request->input('specialties'),
                    'reason' => $request->input('reason'),
                ]);

                $currentTime = Carbon::now()->toTimeString();
                $currentDate = Carbon::now()->toDateString();
                $rawDate = $request->input('appointment_date');
                $dateTime = new DateTime($rawDate);
                $readableDate = $dateTime->format('F j, Y');

                $message = 'You have successfully updated your appointment information for ' . $appointment->appointment_type . '. The appointment is now scheduled for ' . $readableDate . ' at ' . $appointment->appointment_time . '.';

                Notification::create([
                    'account_id' => $appointment->account_id,
                    'title' => 'Appointment Updated',
                    'message' => $message,
                    'date' => $currentDate,
                    'time' => $currentTime,
                    'type' => 'user',
                ]);

                $user = User::where('id', $appointment->account_id)->first();

                $message = ucwords($user->first_name) . ' ' . ucwords($user->last_name) . ' has updated their appointment for ' . $appointment->appointment_type . ' The appointment is now scheduled for ' . $readableDate . ' at ' . $appointment->appointment_time . '.';

                Notification::create([
                    'account_id' => $appointment->doctor_id,
                    'title' => 'Appointment Updated',
                    'message' => $message,
                    'date' => $currentDate,
                    'time' => $currentTime,
                    'type' => 'doctor',
                ]);
            }

            return redirect()->back()->with('success', 'Appointment updated successfully.');
        } else {
            return redirect()->back()->with('info', 'No changes were made.');
        }
    }
    public function cancelAppointment(Request $request)
    {

        $appointment = Appointment::findOrFail($request->input('appointment_id'));
        $user = User::where('id', $appointment->account_id)->first();

        $appointment->status = 'cancelled';
        $appointment->save();

        $currentTime = Carbon::now()->toTimeString();
        $currentDate = Carbon::now()->toDateString();
        $rawDate = $request->input('appointment_date');
        $dateTime = new DateTime($rawDate);
        $readableDate = $dateTime->format('F j, Y');

        $message = 'You have successfully canceled your appointment for ' . $appointment->appointment_type . ' scheduled on ' . $readableDate . ' at ' . $appointment->appointment_time . '.';


        Notification::create([
            'account_id' => $appointment->account_id,
            'title' => 'Appointment Cancelled',
            'message' => $message,
            'date' => $currentDate,
            'time' => $currentTime,
            'type' => 'user',
        ]);

        $message = ucwords($user->first_name) . ' ' . ucwords($user->last_name) . ' has canceled their appointment for ' . $appointment->appointment_type . ' scheduled on ' . $readableDate . ' at ' . $appointment->appointment_time . '.';

        Notification::create([
            'account_id' => $appointment->doctor_id,
            'title' => 'Appointment Cancelled',
            'message' => $message,
            'date' => $currentDate,
            'time' => $currentTime,
            'type' => 'doctor',
        ]);

        return redirect()->route('user.appointment')->with('info', 'Appoinment cancelled successfully.');

    }

    public function calendarCancelAppointment(Request $request)
    {
        $appointment = Appointment::findOrFail($request->input('appointment_id'));
        $user = User::where('id', $appointment->account_id)->first();

        $appointment->status = 'cancelled';
        $appointment->save();

        $currentTime = Carbon::now()->toTimeString();
        $currentDate = Carbon::now()->toDateString();
        $rawDate = $request->input('appointment_date');
        $dateTime = new DateTime($rawDate);
        $readableDate = $dateTime->format('F j, Y');

        $message = 'You have successfully canceled your appointment for ' . $appointment->appointment_type . ' scheduled on ' . $readableDate . ' at ' . $appointment->appointment_time . '.';


        Notification::create([
            'account_id' => $appointment->account_id,
            'title' => 'Appointment Cancelled',
            'message' => $message,
            'date' => $currentDate,
            'time' => $currentTime,
            'type' => 'user',
            'specialties' => $request->input('specialties'),
        ]);

        $message = ucwords($user->first_name) . ' ' . ucwords($user->last_name) . ' has canceled their appointment for ' . $appointment->appointment_type . ' scheduled on ' . $readableDate . ' at ' . $appointment->appointment_time . '.';

        Notification::create([
            'account_id' => $appointment->doctor_id,
            'title' => 'Appointment Cancelled',
            'message' => $message,
            'date' => $currentDate,
            'time' => $currentTime,
            'type' => 'doctor',
            'specialties' => $request->input('specialties'),
        ]);

        return redirect()->route('user.show.appointment')->with('info', 'Appoinment cancelled successfully.');

    }

    public function deleteAppointment(Request $request)
    {
        $appointment = Appointment::findOrFail($request->input('id'));

        $appointment->delete();

        // Redirect with a success message
        return redirect()->route('user.cancelled.appointment')->with('success', 'Appointment deleted successfully.');

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