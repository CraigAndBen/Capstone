<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Guardian;
use App\Models\User_info;
use Illuminate\View\View;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use App\Http\Requests\ProfileUpdateRequest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    public function dashboard()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentYear = Carbon::now()->year;
        $patients = Patient::whereYear('admitted_date', $currentYear)->get();
        $patientCount = $patients->count();

        $rankedDiagnosis = Patient::select('diagnosis', DB::raw('MONTH(admitted_date) as month'))
            ->selectRaw('COUNT(*) as total_occurrences')
            ->whereYear('admitted_date', $currentYear)
            ->groupBy('diagnosis', 'month')
            ->orderByDesc('total_occurrences')
            ->get();

        $diagnosisCount = $rankedDiagnosis->count();
        $limitDiagnosis = $rankedDiagnosis->take(5);

        // Retrieve the rank 1 diagnosis for the current year
        $rank1Diagnosis = $rankedDiagnosis->firstWhere('month', Carbon::now()->month);

        $data = Patient::whereYear('admitted_date', $currentYear)
            ->selectRaw('MONTH(admitted_date) as month, COUNT(*) as count')
            ->groupBy('month')
            ->get();

        // Prepare data for the chart
        $labels = $data->map(function ($item) {
            return Carbon::createFromDate(null, $item->month, null)->format('F');
        });
        $values = $data->pluck('count');

        return view('admin_dashboard', compact('profile', 'limitNotifications', 'count', 'labels', 'values', 'patientCount', 'limitDiagnosis', 'rank1Diagnosis', 'diagnosisCount'));
    }

    public function profile(Request $request): View
    {

        $profile = $request->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();

        return view('admin.profile.profile', compact('profile', 'limitNotifications', 'count'));
    }

    public function passwordProfile(Request $request): View
    {
        $profile = $request->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();

        return view('admin.profile.profile_password', compact('profile', 'limitNotifications', 'count'));
    }

    /**
     * Update the user's profile information.
     */
    public function profileUpdate(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);
        $user = $request->user();

        $userUpdatedData = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
        ];

        $userChange = $this->hasChanges($user, $userUpdatedData);

        if ($userChange) {

            if ($user->email != $request->input('email')) {

                $request->validate([
                    'email' => 'required|string|email|unique:users|max:255',
                ]);

                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $user->email = $request->input('email');
                $saved = $user->save();

                if ($saved) {
                    return redirect()->back()->with('success', 'Profile updated successfully.');
                } else {
                    return redirect()->back()->with('info', 'Profile not updated successfully.');
                }

            } else {

                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $saved = $user->save();

                if ($saved) {
                    return redirect()->back()->with('success', 'Profile updated successfully.');
                } else {
                    return redirect()->back()->with('info', 'Profile not updated successfully.');
                }
            }

        } else {
            return redirect()->back()->with('info', 'No changes were made.');
        }

    }

    /**
     * Delete the user's account.
     */
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

    public function patientList()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = User::where('role', 'doctor')->get();
        $patients = Patient::orderBy('admitted_date', 'desc')->paginate(10);

        return view('admin.patient.patient', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count'));

    }

    public function patientSearch(Request $request)
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = User::where('role', 'doctor')->get();
        $searchTerm = $request->input('search');

        $patients = Patient::where(function ($query) use ($searchTerm) {
            $query->orWhere('first_name', 'LIKE', '%' . $searchTerm . '%');
            $query->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%');
            $query->orWhere('diagnosis', 'LIKE', '%' . $searchTerm . '%');
        })->paginate(10);

        return view('admin.patient.patient_search', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count'));
    }

    public function patientStore(Request $request)
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
            'phone' => 'required',
            'admitted_date' => 'required|date',
            'room_number' => 'required',
            'bed_number' => 'required',
            'physician' => 'required|string|max:255',
        ]);

        Patient::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'street' => $request->input('street'),
            'gender' => $request->input('gender'),
            'brgy' => $request->input('brgy'),
            'city' => $request->input('city'),
            'province' => $request->input('province'),
            'birthdate' => $request->input('birthdate'),
            'phone' => $request->input('phone'),
            'type' => 'admitted_patient',
            'admitted_date' => $request->input('admitted_date'),
            'discharged_date' => $request->input('discharged_date'),
            'room_number' => $request->input('room_number'),
            'bed_number' => $request->input('bed_number'),
            'physician' => $request->input('physician'),
            'medical_condition' => $request->input('medical_condition'),
            'diagnosis' => $request->input('diagnosis'),
            'medication' => $request->input('medication'),
            'guardian_first_name' => $request->input('guardian_first_name'),
            'guardian_last_name' => $request->input('guardian_last_name'),
            'guardian_birthdate' => $request->input('guardian_birthdate'),
            'relationship' => $request->input('relationship'),
            'guardian_phone' => $request->input('guardian_phone'),
            'guardian_email' => $request->input('guardian_email'),
        ]);

        return back()->with('success', 'Patient added sucessfully.');

    }
    public function outpatientStore(Request $request)
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
            'phone' => 'required',
            'physician' => 'required|string|max:255',
        ]);

        Patient::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'street' => $request->input('street'),
            'gender' => $request->input('gender'),
            'brgy' => $request->input('brgy'),
            'city' => $request->input('city'),
            'province' => $request->input('province'),
            'birthdate' => $request->input('birthdate'),
            'phone' => $request->input('phone'),
            'type' => 'outpatient',
            'admitted_date' => $request->input('admitted_date'),
            'discharged_date' => $request->input('discharged_date'),
            'room_number' => $request->input('room_number'),
            'bed_number' => $request->input('bed_number'),
            'physician' => $request->input('physician'),
            'medical_condition' => $request->input('medical_condition'),
            'diagnosis' => $request->input('diagnosis'),
            'medication' => $request->input('medication'),
            'guardian_first_name' => $request->input('guardian_first_name'),
            'guardian_last_name' => $request->input('guardian_last_name'),
            'guardian_birthdate' => $request->input('guardian_birthdate'),
            'relationship' => $request->input('relationship'),
            'guardian_phone' => $request->input('guardian_phone'),
            'guardian_email' => $request->input('guardian_email'),
        ]);

        return back()->with('success', 'Outpatient added sucessfully.');

    }

    public function outpatientList()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = User::where('role', 'doctor')->get();
        $patients = Patient::where('type', 'outpatient')->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.patient.patient_outpatient', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count'));
    }

    public function outpatientSearch(Request $request)
    {

        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = User::where('role', 'doctor')->get();
        $searchTerm = $request->input('search');

        $patients = Patient::where('type', 'outpatient')->where(function ($query) use ($searchTerm) {
            $query->orWhere('first_name', 'LIKE', '%' . $searchTerm . '%');
            $query->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%');
            $query->orWhere('diagnosis', 'LIKE', '%' . $searchTerm . '%');
        })->paginate(10);

        return view('admin.patient.patient_outpatient_search', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count'));
    }

    public function patientUpdate(Request $request)
    {

        $patient = Patient::where('id', $request->id)->first();

        switch ($patient) {
            case $patient->type == 'outpatient':

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
                    'phone' => 'required',
                    'physician' => 'required|string|max:255',
                ]);

                $patientUpdatedData = [
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
                    'physician' => $request->input('physician'),
                    'medical_condition' => $request->input('medical_condition'),
                    'diagnosis' => $request->input('diagnosis'),
                    'medication' => $request->input('medication'),
                    'guardian_first_name' => $request->input('guardian_first_name'),
                    'guardian_last_name' => $request->input('guardian_last_name'),
                    'guardian_birthdate' => $request->input('guardian_birthdate'),
                    'relationship' => $request->input('relationship'),
                    'guardian_phone' => $request->input('guardian_phone'),
                    'guardian_email' => $request->input('guardian_email'),

                ];

                $patientChange = $this->hasChanges($patient, $patientUpdatedData);

                if ($patientChange) {
                    $patient->first_name = $request->input('first_name');
                    $patient->middle_name = $request->input('middle_name');
                    $patient->last_name = $request->input('last_name');
                    $patient->street = $request->input('street');
                    $patient->brgy = $request->input('brgy');
                    $patient->city = $request->input('city');
                    $patient->province = $request->input('province');
                    $patient->birthdate = $request->input('birthdate');
                    $patient->gender = $request->input('gender');
                    $patient->phone = $request->input('phone');
                    $patient->physician = $request->input('physician');
                    $patient->medical_condition = $request->input('medical_condition');
                    $patient->diagnosis = $request->input('diagnosis');
                    $patient->medication = $request->input('medication');
                    $patient->guardian_first_name = $request->input('guardian_first_name');
                    $patient->guardian_last_name = $request->input('guardian_last_name');
                    $patient->guardian_birthdate = $request->input('guardian_birthdate');
                    $patient->relationship = $request->input('relationship');
                    $patient->guardian_phone = $request->input('guardian_phone');
                    $patient->guardian_email = $request->input('guardian_email');

                    $patient->save();

                    return redirect()->back()->with('success', 'Patient Information Updated Successfully.');
                } else {
                    return redirect()->back()->with('info', 'No changes were made.');
                }

            case $patient->type == 'admitted_patient':

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
                    'phone' => 'required',
                    'admitted_date' => 'required|date',
                    'room_number' => 'required',
                    'bed_number' => 'required',
                    'physician' => 'required|string|max:255',
                ]);

                $patientUpdatedData = [
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
                    'admitted_date' => $request->input('admitted_date'),
                    'discharged_date' => $request->input('discharged_date'),
                    'room_number' => $request->input('room_number'),
                    'bed_number' => $request->input('bed_number'),
                    'physician' => $request->input('physician'),
                    'medical_condition' => $request->input('medical_condition'),
                    'diagnosis' => $request->input('diagnosis'),
                    'medication' => $request->input('medication'),
                    'guardian_first_name' => $request->input('guardian_first_name'),
                    'guardian_last_name' => $request->input('guardian_last_name'),
                    'guardian_birthdate' => $request->input('guardian_birthdate'),
                    'relationship' => $request->input('relationship'),
                    'guardian_phone' => $request->input('guardian_phone'),
                    'guardian_email' => $request->input('guardian_email'),

                ];

                $patientChange = $this->hasChanges($patient, $patientUpdatedData);

                if ($patientChange) {
                    $patient->first_name = $request->input('first_name');
                    $patient->middle_name = $request->input('middle_name');
                    $patient->last_name = $request->input('last_name');
                    $patient->street = $request->input('street');
                    $patient->brgy = $request->input('brgy');
                    $patient->city = $request->input('city');
                    $patient->province = $request->input('province');
                    $patient->birthdate = $request->input('birthdate');
                    $patient->gender = $request->input('gender');
                    $patient->phone = $request->input('phone');
                    $patient->admitted_date = $request->input('admitted_date');
                    $patient->discharged_date = $request->input('discharged_date');
                    $patient->room_number = $request->input('room_number');
                    $patient->bed_number = $request->input('bed_number');
                    $patient->physician = $request->input('physician');
                    $patient->medical_condition = $request->input('medical_condition');
                    $patient->diagnosis = $request->input('diagnosis');
                    $patient->medication = $request->input('medication');
                    $patient->guardian_first_name = $request->input('guardian_first_name');
                    $patient->guardian_last_name = $request->input('guardian_last_name');
                    $patient->guardian_birthdate = $request->input('guardian_birthdate');
                    $patient->relationship = $request->input('relationship');
                    $patient->guardian_phone = $request->input('guardian_phone');
                    $patient->guardian_email = $request->input('guardian_email');

                    $patient->save();

                    return redirect()->back()->with('success', 'Patient Information Updated Successfully.');
                } else {
                    return redirect()->back()->with('info', 'No changes were made.');
                }
        }
    }

    public function patienAdmittedtList()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = User::where('role', 'doctor')->get();
        $patients = Patient::where('type', 'admitted_patient')->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.patient.patient_admitted', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count'));
    }

    public function patientAdmittedSearch(Request $request)
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = User::where('role', 'doctor')->get();
        $searchTerm = $request->input('search');
        $patients = Patient::whereNull('discharged_date')->where(function ($query) use ($searchTerm) {
            $query->orWhere('first_name', 'LIKE', '%' . $searchTerm . '%');
            $query->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%');
            $query->orWhere('diagnosis', 'LIKE', '%' . $searchTerm . '%');
        })->paginate(10);

        return view('admin.patient.patient_admitted_search', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count'));
    }

    public function notification()
    {

        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();

        return view('admin.notification.notification', compact('profile', 'notifications', 'limitNotifications', 'count'));

    }

    public function notificationRead(Request $request)
    {

        $notification = Notification::findOrFail($request->input('id'));

        if ($notification->is_read == 0) {
            $notification->is_read = 1;
            $notification->save();

            return redirect()->route('admin.notification');
        } else {
            return redirect()->route('admin.notification');
        }

    }

    public function genderDemo()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();

        // Current year
        $year = Carbon::now()->year;
        $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->pluck('year')
            ->toArray();

        // Initialize an array to store gender counts for each month
        $genderCountsByMonth = [];

        // Loop through each month of the current year
        for ($month = 1; $month <= 12; $month++) {
            // Get the start and end dates of the current month
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            // Retrieve admitted patient data for the current month
            $patients = Patient::select('gender')
                ->whereBetween('admitted_date', [$startDate, $endDate])
                ->get();

            // Count the number of male and female patients for the current month
            $maleCount = $patients->where('gender', 'male')->count();
            $femaleCount = $patients->where('gender', 'female')->count();

            // Store the gender counts for the current month in the array
            $genderCountsByMonth[] = [
                'month' => $startDate->format('F'),
                'male' => $maleCount,
                'female' => $femaleCount,
            ];
        }
        return view('admin.patient-demo.gender', compact('profile', 'limitNotifications', 'count', 'genderCountsByMonth', 'year', 'admittedYears'));
    }

    public function genderSearch(Request $request)
    {
        $request->validate([
            'year' => 'required',
        ]);

        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();

        //Selected Year
        $year = $request->input('year');
        $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->pluck('year')
            ->toArray();

        // Initialize an array to store gender counts for each month
        $genderCountsByMonth = [];

        // Loop through each month of the current year
        for ($month = 1; $month <= 12; $month++) {
            // Get the start and end dates of the current month
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            // Retrieve admitted patient data for the current month
            $patients = Patient::select('gender')
                ->whereBetween('admitted_date', [$startDate, $endDate])
                ->get();

            // Count the number of male and female patients for the current month
            $maleCount = $patients->where('gender', 'male')->count();
            $femaleCount = $patients->where('gender', 'female')->count();

            // Store the gender counts for the current month in the array
            $genderCountsByMonth[] = [
                'month' => $startDate->format('F'),
                'male' => $maleCount,
                'female' => $femaleCount,
            ];
        }

        return view('admin.patient-demo.gender_search', compact('profile', 'limitNotifications', 'count', 'genderCountsByMonth', 'year', 'admittedYears'));
    }

    public function genderReport(Request $request)
    {
        $year = $request->input('year');
        $currentYear = Carbon::now()->year; // Get current year
        $currentDate = date('Y-m-d');
        $currentTime = Carbon::now()->toTimeString(); // Get current time (24-hour format)

        $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->pluck('year')
            ->toArray();

        // Initialize an array to store gender counts for each month
        $genderCountsByMonth = [];

        // Loop through each month of the current year
        for ($month = 1; $month <= 12; $month++) {
            // Get the start and end dates of the current month
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            // Retrieve admitted patient data for the current month
            $patients = Patient::select('gender')
                ->whereBetween('admitted_date', [$startDate, $endDate])
                ->get();

            // Count the number of male and female patients for the current month
            $maleCount = $patients->where('gender', 'male')->count();
            $femaleCount = $patients->where('gender', 'female')->count();

            // Store the gender counts for the current month in the array
            $genderCountsByMonth[] = [
                'month' => $startDate->format('F'),
                'male' => $maleCount,
                'female' => $femaleCount,
            ];
        }
        return view('admin.report.gender_report', compact('genderCountsByMonth', 'year', 'currentTime', 'currentDate'));

    }

    public function ageDemo()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();

        // Get the current year
        $year = Carbon::now()->year;
        $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->pluck('year')
            ->toArray();

        // Initialize an array to store the age group counts for each month
        $ageGroupsByMonth = [];

        // Create an array of age group labels
        $ageGroups = [
            '0-9',
            '10-19',
            '20-29',
            '30-39',
            '40-49',
            '50-59',
            '60-69',
            '70-79',
            '80-89',
            '90+',
        ];

        // Loop through each month of the current year
        for ($month = 1; $month <= 12; $month++) {
            // Get the start and end dates of the current month
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            // Retrieve admitted patient data for the current month
            $patients = Patient::select('birthdate')
                ->whereBetween('admitted_date', [$startDate, $endDate])
                ->get();

            // Initialize the age group counts with zeros for the current month
            $ageGroupCounts = [];
            foreach ($ageGroups as $ageGroup) {
                $ageGroupCounts[$ageGroup] = 0;
            }

            // Calculate age groups for the current month and count occurrences
            foreach ($patients as $patient) {
                $age = Carbon::parse($patient->birthdate)->age;
                $ageGroup = floor($age / 10) * 10 . '-' . (floor($age / 10) * 10 + 9);
                $ageGroupCounts[$ageGroup]++;
            }

            // Store the age group counts for the current month in the array
            $ageGroupsByMonth[] = [
                'month' => $startDate->format('F'),
                'data' => array_values($ageGroupCounts),
            ];
        }

        // Prepare data for the bar graph
        $labels = $ageGroups;
        $datasets = $ageGroupsByMonth;

        return view('admin.patient-demo.age', compact('profile', 'limitNotifications', 'count', 'labels', 'datasets', 'year', 'admittedYears'));
    }

    public function ageSearch(Request $request)
    {
        $request->validate([
            'year' => 'required',
        ]);

        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();

        // Get the current year
        $year = $request->input('year');
        $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->pluck('year')
            ->toArray();

        // Initialize an array to store the age group counts for each month
        $ageGroupsByMonth = [];

        // Create an array of age group labels
        $ageGroups = [
            '0-9',
            '10-19',
            '20-29',
            '30-39',
            '40-49',
            '50-59',
            '60-69',
            '70-79',
            '80-89',
            '90+',
        ];

        // Loop through each month of the current year
        for ($month = 1; $month <= 12; $month++) {
            // Get the start and end dates of the current month
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            // Retrieve admitted patient data for the current month
            $patients = Patient::select('birthdate')
                ->whereBetween('admitted_date', [$startDate, $endDate])
                ->get();

            // Initialize the age group counts with zeros for the current month
            $ageGroupCounts = [];
            foreach ($ageGroups as $ageGroup) {
                $ageGroupCounts[$ageGroup] = 0;
            }

            // Calculate age groups for the current month and count occurrences
            foreach ($patients as $patient) {
                $age = Carbon::parse($patient->birthdate)->age;
                $ageGroup = floor($age / 10) * 10 . '-' . (floor($age / 10) * 10 + 9);
                $ageGroupCounts[$ageGroup]++;
            }

            // Store the age group counts for the current month in the array
            $ageGroupsByMonth[] = [
                'month' => $startDate->format('F'),
                'data' => array_values($ageGroupCounts),
            ];
        }

        // Prepare data for the bar graph
        $labels = $ageGroups;
        $datasets = $ageGroupsByMonth;

        return view('admin.patient-demo.age_search', compact('profile', 'limitNotifications', 'count', 'labels', 'datasets', 'year', 'admittedYears'));
    }

    public function ageReport(Request $request)
    {
        $year = $request->input('year');
        $currentYear = Carbon::now()->year; // Get current year
        $currentDate = date('Y-m-d');
        $currentTime = Carbon::now()->toTimeString(); // Get current time (24-hour format)

        $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->pluck('year')
            ->toArray();

        // Initialize an array to store the age group counts for each month
        $ageGroupsByMonth = [];

        // Create an array of age group labels
        $ageGroups = [
            '0-9',
            '10-19',
            '20-29',
            '30-39',
            '40-49',
            '50-59',
            '60-69',
            '70-79',
            '80-89',
            '90+',
        ];

        // Loop through each month of the current year
        for ($month = 1; $month <= 12; $month++) {
            // Get the start and end dates of the current month
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            // Retrieve admitted patient data for the current month
            $patients = Patient::select('birthdate')
                ->whereBetween('admitted_date', [$startDate, $endDate])
                ->get();

            // Initialize the age group counts with zeros for the current month
            $ageGroupCounts = [];
            foreach ($ageGroups as $ageGroup) {
                $ageGroupCounts[$ageGroup] = 0;
            }

            // Calculate age groups for the current month and count occurrences
            foreach ($patients as $patient) {
                $age = Carbon::parse($patient->birthdate)->age;
                $ageGroup = floor($age / 10) * 10 . '-' . (floor($age / 10) * 10 + 9);
                $ageGroupCounts[$ageGroup]++;
            }

            // Store the age group counts for the current month in the array
            $ageGroupsByMonth[] = [
                'month' => $startDate->format('F'),
                'data' => array_values($ageGroupCounts),
            ];
        }

        // Prepare data for the bar graph
        $labels = $ageGroups;
        $datasets = $ageGroupsByMonth;

        return view('admin.report.age_report', compact('labels', 'datasets', 'year', 'currentTime', 'currentDate'));

    }

    public function admitDemo()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();

        // Get the current year
        $year = Carbon::now()->year;
        $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->pluck('year')
            ->toArray();


        // Initialize an array to store admit patient counts for each month
        $admitPatientCountsByMonth = [];

        // Loop through each month of the current year
        for ($month = 1; $month <= 12; $month++) {
            // Get the start and end dates of the current month
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            // Retrieve admitted patient data for the current month
            $admitPatientCounts = Patient::whereBetween('admitted_date', [$startDate, $endDate])
                ->count();

            // Store the admit patient count for the current month in the array
            $admitPatientCountsByMonth[] = [
                'month' => $startDate->format('F'),
                'count' => $admitPatientCounts,
            ];
        }

        return view('admin.patient-demo.admit', compact('profile', 'limitNotifications', 'count', 'admitPatientCountsByMonth', 'year', 'admittedYears'));
    }

    public function admitSearch(Request $request)
    {
        $request->validate([
            'year' => 'required',
        ]);

        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();

        // Get the current year
        $year = $request->input('year');
        $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->pluck('year')
            ->toArray();


        // Initialize an array to store admit patient counts for each month
        $admitPatientCountsByMonth = [];

        // Loop through each month of the current year
        for ($month = 1; $month <= 12; $month++) {
            // Get the start and end dates of the current month
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            // Retrieve admitted patient data for the current month
            $admitPatientCounts = Patient::whereBetween('admitted_date', [$startDate, $endDate])
                ->count();

            // Store the admit patient count for the current month in the array
            $admitPatientCountsByMonth[] = [
                'month' => $startDate->format('F'),
                'count' => $admitPatientCounts,
            ];
        }

        return view('admin.patient-demo.admit_search', compact('profile', 'limitNotifications', 'count', 'admitPatientCountsByMonth', 'year', 'admittedYears'));
    }

    public function admitReport(Request $request)
    {
        $year = $request->input('year');
        $currentYear = Carbon::now()->year; // Get current year
        $currentDate = date('Y-m-d');
        $currentTime = Carbon::now()->toTimeString(); // Get current time (24-hour format)

        $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->pluck('year')
            ->toArray();


        // Initialize an array to store admit patient counts for each month
        $admitPatientCountsByMonth = [];

        // Loop through each month of the current year
        for ($month = 1; $month <= 12; $month++) {
            // Get the start and end dates of the current month
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            // Retrieve admitted patient data for the current month
            $admitPatientCounts = Patient::whereBetween('admitted_date', [$startDate, $endDate])
                ->count();

            // Store the admit patient count for the current month in the array
            $admitPatientCountsByMonth[] = [
                'month' => $startDate->format('F'),
                'count' => $admitPatientCounts,
            ];
        }

        return view('admin.report.admit_report', compact('admitPatientCountsByMonth', 'year', 'currentTime', 'currentDate'));
    }

    public function diagnoseDemo()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();

        $diagnoseData = Patient::select('diagnosis')
            ->distinct()
            ->pluck('diagnosis')
            ->toArray();

        $currentYear = Carbon::now()->year;
        $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->pluck('year')
            ->toArray();

        return view('admin.patient-demo.diagnose', compact('profile', 'limitNotifications', 'count', 'diagnoseData', 'admittedYears'));
    }

    public function diagnoseSearch(Request $request)
    {
        $request->validate([
            'diagnose' => 'required',
            'year' => 'required',
        ]);

        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();

        $diagnoseData = Patient::select('diagnosis')
            ->distinct()
            ->pluck('diagnosis')
            ->toArray();

        $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->pluck('year')
            ->toArray();

        // Define the specific diagnosis you want to analyze
        $specificDiagnosis = $request->input('diagnose');

        // Get the current year
        $selectedYear = $request->input('year');

        // Initialize an array to store diagnose patient counts for each month
        $diagnosePatientCountsByMonth = [];

        // Loop through each month of the current year
        for ($month = 1; $month <= 12; $month++) {
            // Get the start and end dates of the current month
            $startDate = Carbon::createFromDate($selectedYear, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            // Retrieve patients with the specific diagnosis for the current month
            $diagnosePatientCounts = Patient::whereBetween('admitted_date', [$startDate, $endDate])
                ->where('diagnosis', $specificDiagnosis)
                ->count();

            // Store the diagnose patient count for the current month in the array
            $diagnosePatientCountsByMonth[] = [
                'month' => $startDate->format('F'),
                'count' => $diagnosePatientCounts,
            ];
        }

        return view('admin.patient-demo.diagnose_search', compact('profile', 'limitNotifications', 'count', 'diagnosePatientCountsByMonth', 'diagnoseData', 'admittedYears', 'selectedYear', 'specificDiagnosis'));
    }

    public function diagnoseReport(Request $request)
    {
        $year = $request->input('year');
        $diagnose = $request->input('diagnose');
        $currentYear = Carbon::now()->year; // Get current year
        $currentDate = date('Y-m-d');
        $currentTime = Carbon::now()->toTimeString(); // Get current time (24-hour format)

        $diagnoseData = Patient::select('diagnosis')
            ->distinct()
            ->pluck('diagnosis')
            ->toArray();

        $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->pluck('year')
            ->toArray();

        // Define the specific diagnosis you want to analyze
        $specificDiagnosis = $request->input('diagnose');

        // Get the current year
        $year = $request->input('year');

        // Initialize an array to store diagnose patient counts for each month
        $diagnosePatientCountsByMonth = [];

        // Loop through each month of the current year
        for ($month = 1; $month <= 12; $month++) {
            // Get the start and end dates of the current month
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            // Retrieve patients with the specific diagnosis for the current month
            $diagnosePatientCounts = Patient::whereBetween('admitted_date', [$startDate, $endDate])
                ->where('diagnosis', $specificDiagnosis)
                ->count();

            // Store the diagnose patient count for the current month in the array
            $diagnosePatientCountsByMonth[] = [
                'month' => $startDate->format('F'),
                'count' => $diagnosePatientCounts,
            ];
        }

        return view('admin.report.diagnose_report', compact('diagnosePatientCountsByMonth', 'year', 'currentTime', 'currentDate','diagnose'));
    }

    public function diagnoseTrend()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentYear = Carbon::now()->year;

        $rankedDiagnosis = Patient::select('diagnosis', DB::raw('MONTH(admitted_date) as month'))
            ->selectRaw('COUNT(*) as total_occurrences')
            ->whereYear('admitted_date', $currentYear)
            ->groupBy('diagnosis', 'month')
            ->orderByDesc('total_occurrences')
            ->get();

        $limitDiagnosis = $rankedDiagnosis->take(5);

        // Retrieve the unique years from the "admitted" column
        $uniqueYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->get()
            ->pluck('year')
            ->toArray();

        // Count the number of unique years
        $countUniqueYears = count($uniqueYears);

        $diagnoseData = Patient::select('diagnosis')
            ->distinct()
            ->pluck('diagnosis')
            ->toArray();

        return view('admin.trend.diagnose_trend', compact('profile', 'limitNotifications', 'count', 'diagnoseData', 'limitDiagnosis', 'countUniqueYears', 'rankedDiagnosis'));
    }

    public function diagnoseTrendSearch(Request $request)
    {
        $request->validate([
            'diagnose' => 'required',
        ]);

        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentYear = Carbon::now()->year;

        $rankedDiagnosis = Patient::select('diagnosis', DB::raw('MONTH(admitted_date) as month'))
            ->selectRaw('COUNT(*) as total_occurrences')
            ->whereYear('admitted_date', $currentYear)
            ->groupBy('diagnosis', 'month')
            ->orderByDesc('total_occurrences')
            ->get();

        $limitDiagnosis = $rankedDiagnosis->take(5);

        // Retrieve the unique years from the "admitted" column
        $uniqueYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->get()
            ->pluck('year')
            ->toArray();

        // Count the number of unique years
        $countUniqueYears = count($uniqueYears);

        $diagnoseData = Patient::select('diagnosis')
            ->distinct()
            ->pluck('diagnosis')
            ->toArray();
        // The specific diagnosis you want to analyze
        $specificDiagnosis = $request->input('diagnose');

        // Retrieve admitted patient data for the specific diagnosis
        $patients = Patient::where('diagnosis', $specificDiagnosis)
            ->orderBy('admitted_date')
            ->get();

        // Initialize an array to store the yearly trend data
        $yearlyTrendData = [];

        // Loop through the patient data to calculate the yearly trend
        $currentYear = null;
        $yearlyCount = 0;

        foreach ($patients as $patient) {
            $admittedDate = Carbon::parse($patient->admitted_date); // Convert to Carbon object
            $year = $admittedDate->format('Y');

            if ($year !== $currentYear) {
                // Save the count for the previous year
                if ($currentYear !== null) {
                    $yearlyTrendData[] = [
                        'year' => $currentYear,
                        'count' => $yearlyCount,
                    ];
                }

                // Reset the count for the current year
                $currentYear = $year;
                $yearlyCount = 1;
            } else {
                $yearlyCount++;
            }
        }

        // Save the count for the last year
        if ($currentYear !== null) {
            $yearlyTrendData[] = [
                'year' => $currentYear,
                'count' => $yearlyCount,
            ];
        }

        // Initialize an array to store the monthly trend data
        $monthlyTrendData = [];

        // Loop through the patient data to calculate the monthly trend
        $currentMonth = null;
        $monthlyCount = 0;
        foreach ($patients as $patient) {
            $admittedDate = Carbon::parse($patient->admitted_date); // Convert to Carbon object
            $month = $admittedDate->format('F');

            if ($month !== $currentMonth) {
                // Save the count for the previous month
                if ($currentMonth !== null) {
                    $monthlyTrendData[] = [
                        'month' => $currentMonth,
                        'count' => $monthlyCount,
                    ];
                }

                // Reset the count for the current month
                $currentMonth = $month;
                $monthlyCount = 1;
            } else {
                $monthlyCount++;
            }
        }

        // Save the count for the last month
        if ($currentMonth !== null) {
            $monthlyTrendData[] = [
                'month' => $currentMonth,
                'count' => $monthlyCount,
            ];
        }


        return view('admin.trend.diagnose_trend_search', compact('profile', 'limitNotifications', 'count', 'diagnoseData', 'limitDiagnosis', 'monthlyTrendData', 'specificDiagnosis', 'yearlyTrendData', 'rankedDiagnosis'));
    }

    public function diagnoseTrendReport(Request $request)
    {
        $year = $request->input('year');
        $currentYear = Carbon::now()->year; // Get current year
        $currentDate = date('Y-m-d');
        $currentTime = Carbon::now()->toTimeString(); // Get current time (24-hour format)

        $rankedDiagnosis = Patient::select('diagnosis', DB::raw('MONTH(admitted_date) as month'))
            ->selectRaw('COUNT(*) as total_occurrences')
            ->whereYear('admitted_date', $currentYear)
            ->groupBy('diagnosis', 'month')
            ->orderByDesc('total_occurrences')
            ->get();

        $limitDiagnosis = $rankedDiagnosis->take(5);

        // Retrieve the unique years from the "admitted" column
        $uniqueYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->get()
            ->pluck('year')
            ->toArray();

        // Count the number of unique years
        $countUniqueYears = count($uniqueYears);

        $diagnoseData = Patient::select('diagnosis')
            ->distinct()
            ->pluck('diagnosis')
            ->toArray();
        // The specific diagnosis you want to analyze
        $specificDiagnosis = $request->input('diagnose');

        // Retrieve admitted patient data for the specific diagnosis
        $patients = Patient::where('diagnosis', $specificDiagnosis)
            ->orderBy('admitted_date')
            ->get();

        // Initialize an array to store the yearly trend data
        $yearlyTrendData = [];

        // Loop through the patient data to calculate the yearly trend
        $currentYear = null;
        $yearlyCount = 0;

        foreach ($patients as $patient) {
            $admittedDate = Carbon::parse($patient->admitted_date); // Convert to Carbon object
            $year = $admittedDate->format('Y');

            if ($year !== $currentYear) {
                // Save the count for the previous year
                if ($currentYear !== null) {
                    $yearlyTrendData[] = [
                        'year' => $currentYear,
                        'count' => $yearlyCount,
                    ];
                }

                // Reset the count for the current year
                $currentYear = $year;
                $yearlyCount = 1;
            } else {
                $yearlyCount++;
            }
        }

        // Save the count for the last year
        if ($currentYear !== null) {
            $yearlyTrendData[] = [
                'year' => $currentYear,
                'count' => $yearlyCount,
            ];
        }

        // Initialize an array to store the monthly trend data
        $monthlyTrendData = [];

        // Loop through the patient data to calculate the monthly trend
        $currentMonth = null;
        $monthlyCount = 0;
        foreach ($patients as $patient) {
            $admittedDate = Carbon::parse($patient->admitted_date); // Convert to Carbon object
            $month = $admittedDate->format('F');

            if ($month !== $currentMonth) {
                // Save the count for the previous month
                if ($currentMonth !== null) {
                    $monthlyTrendData[] = [
                        'month' => $currentMonth,
                        'count' => $monthlyCount,
                    ];
                }

                // Reset the count for the current month
                $currentMonth = $month;
                $monthlyCount = 1;
            } else {
                $monthlyCount++;
            }
        }

        // Save the count for the last month
        if ($currentMonth !== null) {
            $monthlyTrendData[] = [
                'month' => $currentMonth,
                'count' => $monthlyCount,
            ];
        }

        return view('admin.report.diagnose_trend_report', compact('yearlyTrendData','monthlyTrendData','specificDiagnosis', 'year', 'currentTime', 'currentDate','specificDiagnosis'));
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

    // Logout
    public function adminLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}