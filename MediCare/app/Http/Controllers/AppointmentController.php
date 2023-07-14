<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function showAppointment(){
        
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

        return view('user.appointment.appointment_create', compact('users', 'infos','timeList'));
    }

    public function createAppointment(Request $request){

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

        $appointments = Appointment::all();
        $count = Appointment::count();    

        if($count != 0){
            foreach ($appointments as $appointment) {
                if($appointment->appointment_date != $request->input('appointment_date') || $appointment->appointment_time != $request->input('appointment_time')){
    
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
                    
                    return back()->with('success', 'Appointment Created Successfully.'); 
                }
    
                return back()->with('info', 'The current date and time are unavailable, please select another date and time.');
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
            
            return back()->with('success', 'Appointment Created Successfully.'); 
        }



    }

    public function appointment(){

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
        $appointments = Appointment::where('account_id', $user->id)->where('status', 'pending')->get();

        return view('user.appointment.appointment', compact('appointments','infos','timeList'));
    }

    public function confirmedAppointmentList(){

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
        $appointments = Appointment::where('account_id', $user->id)->where('status', 'confirmed')->get();
        return view('user.appointment.confirmed_appointment', compact('appointments','infos','timeList','doctors'));
    }

    public function doneAppointmentList(){

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
        $appointments = Appointment::where('account_id', $user->id)->where('status', 'done')->get();

        return view('user.appointment.done_appointment', compact('appointments','infos','timeList'));
    }

    public function cancelledAppointmentList(){

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
        $appointments = Appointment::where('account_id', $user->id)->where('status', 'cancelled')->get();

        return view('user.appointment.cancelled_appointment', compact('appointments','infos','timeList'));
    }
    public function updateAppointment(Request $request){

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
    
            if($appointmentChange) {

                if($appointment->appointment_date != $request->input('appointment_date') || $appointment->appointment_time != $request->input('appointment_time')){
    
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
    public function cancelAppointment(Request $request){

        $appointment = Appointment::findOrFail($request->input('appointment_id'));

        if ($request->input('status') === 'pending') {

            $appointment->status = 'cancelled';
            $appointment->save();

            return redirect()->route('user.appointment')->with('status', 'Appoinment cancelled successfully.');
        }
    }

    public function notification(){
        
        $user = Auth::user();
        $notifications = Notification::where('account_id', $user->id)->get();

        return view('user.notification.notification', compact('notifications'));

    }

    public function notificationRead(Request $request){

        $notification = Notification::findOrFail($request->input('id'));

        if($notification->is_read == 0){
            $notification->is_read = 1;
            $notification->save();
    
            return redirect()->route('user.notification');
        } else {
            return redirect()->route('user.notification');
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


}
