<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function show(){
        
        $users = User::where('role', 'doctor')->get();
        $infos = Doctor::all();

        return view('user.appointment', compact('users', 'infos'));
    }

    public function createAppointment(Request $request){

        return redirect()->with('status', "Appointment Created Successfully.");
    }

    public function getDoctorsBySpecialty($specialtyId)
{
    // Retrieve doctors from the database based on the selected specialty ID
    $doctor = Doctor::where('id', $specialtyId)->first();
    $doctors = Doctor::where('specialties', $doctor->specialties);

    return response()->json($doctors);
}

}
