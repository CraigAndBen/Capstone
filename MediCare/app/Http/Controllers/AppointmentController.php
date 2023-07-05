<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function showAppointment(){
        
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

        $users = User::where('role', 'doctor')->get();
        $infos = Doctor::all();

        return view('user.appointment', compact('users', 'infos','amTime','pmTime'));
    }

    public function createAppointment(Request $request){

        return redirect()->with('status', "Appointment Created Successfully.");
    }

}
