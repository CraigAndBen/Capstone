<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function showAppointment()
    {
        $user = Auth::user();

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

        // Fetch appointment data here
        $appointments = Appointment::where('account_id', $user->id)->get(); // Replace with your own query to fetch the data

        $events = [];
        foreach ($appointments as $appointment) {
            $events[] = [
                'title' => $appointment->title,
                // Replace with the field containing the label
                'start' => $appointment->date,
                // Replace with the field containing the date
            ];
        }

        return view('user.appointment.appointment_create', compact('users', 'infos', 'timeList'))->with('events', json_encode($events));
    }

    public function appointmentEvents(){

        $user = Auth::user();
        $appointments = Appointment::where('account_id', $user->id)->where('status','confirmed')->get(); // Replace with your own query to fetch the event data

        $events = [];
        foreach ($appointments as $appointment) {
            $events[] = [
                'title' => ucwords($appointment->appointment_type), // Replace with the field containing the event title
                'start' => $appointment->appointment_date,  // Replace with the field containing the event date
            ];
        }

        return response()->json($events);
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

        $appointments = Appointment::where('specialties', $request->input('specialties'))->whereNotIn('status', ['unavailable'])->get();
        $count = $appointments->count();

        if ($count != 0) {
            foreach ($appointments as $appointment) {
                if ($appointment->appointment_date != $request->input('appointment_date') || $appointment->appointment_time != $request->input('appointment_time')) {

                    $user = Auth::user();

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
                        'specialties' => $request->input('specialties'),
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
                    $currentDate = Carbon::now()->toTimeString();
                    $currentTime = Carbon::now()->toDateString();
                    $message = ' You have a new appointment that has ' . $appointment->appointment_type . ' that dated ' . $appointment->appointment_date . ' and timed ' . $appointment->appointment_time . '. ';

                    Notification::create([
                        'account_id' => $appointment->account_id,
                        'title' => 'appointment confirmation',
                        'message' => $message,
                        'date' => $currentDate,
                        'time' => $currentTime,
                    ]);

                    return back()->with('success', 'Appointment created successfully that dated: ' . $request->input('appointment_date') . ' and timed: ' . $request->input('appointment_time'));
                }

                $appoint = Appointment::where('appointment_date', $appointment->appointment_date)->get();

                $appoint_time = $appoint->pluck('appointment_time');

                foreach ($appoint_time as $time) {
                    $timeList = array_filter($timeList, function ($value) use ($time) {
                        return $value !== $time;
                    });
                }

                $time = implode(', ', $timeList);

                return back()->with([
                    'data' => $timeList,
                    'info' => 'The current time are unavailable, please select from this date: ' . $request->input('appointment_date') . ' available time: ' . $time . '.',
                ]);
            }
        } else {
            $user = Auth::user();

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
                'specialties' => $request->input('specialties'),
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
            $currentDate = Carbon::now()->toTimeString();
            $currentTime = Carbon::now()->toDateString();
            $message = ' You have a new appointment that has ' . $appointment->appointment_type . ' that dated ' . $appointment->appointment_date . ' and timed ' . $appointment->appointment_time . '. ';

            Notification::create([
                'account_id' => $appointment->account_id,
                'title' => 'Appointment Created',
                'message' => $message,
                'date' => $currentDate,
                'time' => $currentTime,
                'specialties' => $appointment->specialties,
            ]);

            return back()->with('success', 'Appointment created successfully that dated: ' . $request->input('appointment_date') . 'and timed:' . $request->input('appointment_time'));
        }



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
        $infos = Doctor::all();
        $appointments = Appointment::where('account_id', $user->id)->where('status', 'pending')->orderBy('created_at', 'desc')->paginate(10);

        return view('user.appointment.appointment', compact('appointments', 'infos', 'timeList'));
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
        $infos = Doctor::all();
        $doctors = User::all();
        $appointments = Appointment::where('account_id', $user->id)->where('status', 'confirmed')->paginate(10);
        return view('user.appointment.confirmed_appointment', compact('appointments', 'infos', 'timeList', 'doctors'));
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
        $infos = Doctor::all();
        $appointments = Appointment::where('account_id', $user->id)->where('status', 'done')->paginate(10);

        return view('user.appointment.done_appointment', compact('appointments', 'infos', 'timeList'));
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
        $infos = Doctor::all();
        $appointments = Appointment::where('account_id', $user->id)->where('status', 'cancelled')->paginate(10);

        return view('user.appointment.cancelled_appointment', compact('appointments', 'infos', 'timeList'));
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
            'specialties' => 'required|string|max:255',
            'appointment_type' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|string|max:255',
            'reason' => 'required|string|max:255',
        ]);

        $appointment = Appointment::where('id', $request->appointment_id)->first();

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
            'specialties' => $request->input('specialties'),
            'email' => $request->input('email'),
            'appointment_type' => $request->input('appointment_type'),
            'appointment_date' => $request->input('appointment_date'),
            'appointment_time' => $request->input('appointment_time'),
            'reason' => $request->input('reason'),
        ];

        $appointmentChange = $this->hasChanges($appointment, $appointmentUpdatedData);

        if ($appointmentChange) {

            if ($appointment->appointment_date != $request->input('appointment_date') || $appointment->appointment_time != $request->input('appointment_time')) {

                $appointment->first_name = $request->input('first_name');
                $appointment->middle_name = $request->input('middle_name');
                $appointment->last_name = $request->input('last_name');
                $appointment->street = $request->input('street');
                $appointment->brgy = $request->input('brgy');
                $appointment->city = $request->input('city');
                $appointment->province = $request->input('province');
                $appointment->birthdate = $request->input('birthdate');
                $appointment->gender = $request->input('gender');
                $appointment->phone = $request->input('phone');
                $appointment->email = $request->input('email');
                $appointment->appointment_type = $request->input('appointment_type');
                $appointment->appointment_date = $request->input('appointment_date');
                $appointment->appointment_time = $request->input('appointment_time');
                $appointment->reason = $request->input('reason');

                $appointment->save();

                return redirect()->back()->with('success', 'Profile updated successfully.');
            } else {
                return back()->with('info', 'The current date and time are unavailable, please select another date and time.');
            }
        } else {
            return redirect()->back()->with('info', 'No changes were made.');
        }
    }
    public function cancelAppointment(Request $request)
    {

        $appointment = Appointment::findOrFail($request->input('appointment_id'));

        if ($request->input('status') === 'pending') {

            $appointment->status = 'cancelled';
            $appointment->save();

            return redirect()->route('user.appointment')->with('info', 'Appoinment cancelled successfully.');
        }
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

                return $value;
            }
        }

        return false;

    }


}