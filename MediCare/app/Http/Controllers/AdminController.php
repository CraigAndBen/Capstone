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
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        $patientsByMonth = DB::table('patients')
            ->select(DB::raw('DATE_FORMAT(admitted_date, "%M") as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('admitted_date', $currentYear)
            ->groupBy('month')
            ->get();

        $patientsByYear = DB::table('patients')
            ->whereYear('admitted_date', $currentYear)
            ->get();

        $patientCount = $patientsByYear->count();

        $rankedDiagnosis = Patient::select('diagnosis', DB::raw('MONTH(admitted_date) as month'))
            ->selectRaw('COUNT(*) as total_occurrences')
            ->whereYear('admitted_date', $currentYear)
            ->whereNotNull('diagnosis')
            ->groupBy('diagnosis', 'month')
            ->orderByDesc('total_occurrences')
            ->get();


        $diagnosesWithOccurrences = Patient::select('diagnosis')
            ->selectRaw('COUNT(*) as total_occurrences')
            ->whereYear('admitted_date', $currentYear)
            ->groupBy('diagnosis')
            ->orderBy('total_occurrences', 'desc') // Order by occurrences in descending order
            ->take(5) // Limit the result to the top 5 diagnoses
            ->get();

        $diagnosisCount = $diagnosesWithOccurrences->count();

        // $limitDiagnosis = $rankedDiagnosis->take(5);

        // Retrieve the rank 1 diagnosis for the current year
        $rank1Diagnosis = $rankedDiagnosis->firstWhere('month', Carbon::now()->month);

        return view('admin_dashboard', compact('profile', 'limitNotifications', 'count', 'patientsByMonth', 'patientCount', 'rankedDiagnosis', 'diagnosesWithOccurrences', 'rank1Diagnosis', 'currentTime', 'currentDate', 'diagnosisCount'));
    }

    public function profile(Request $request): View
    {

        $profile = $request->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        return view('admin.profile.profile', compact('profile', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
    }

    public function passwordProfile(Request $request): View
    {
        $profile = $request->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        return view('admin.profile.profile_password', compact('profile', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
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

            return redirect()->back()->with('info', 'Current password is incorrect.');

        } else {

            if (Hash::check($request->input('password'), $user->password)) {

                return redirect()->back()->with('info', "Password doesn't change.");
            } else {

                $user->password = Hash::make($request->input('password'));

                $user->save();

                return redirect()->back()->with('success', 'Password updated successfull.');
            }


        }
    }

    public function patientList()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = User::where('role', 'doctor')->get();
        $patients = Patient::orderBy('admitted_date', 'desc')->orderBy('date', 'desc')->paginate(5);
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');


        return view('admin.patient.patient', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count', 'currentTime', 'currentDate'));

    }

    public function patientSearch(Request $request)
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = User::where('role', 'doctor')->get();
        $searchTerm = $request->input('search');
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        $patients = Patient::where(function ($query) use ($searchTerm) {
            $query->orWhere('first_name', 'LIKE', '%' . $searchTerm . '%');
            $query->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%');
            $query->orWhere('diagnosis', 'LIKE', '%' . $searchTerm . '%');
        })->paginate(5);

        return view('admin.patient.patient_search', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
    }

    public function patientStore(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
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
            'date' => 'required|date',
            'time' => 'required',
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
            'date' => $request->input('date'),
            'time' => $request->input('time'),
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
        $patients = Patient::where('type', 'outpatient')->orderBy('created_at', 'desc')->paginate(5);
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        return view('admin.patient.patient_outpatient', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
    }

    public function outpatientSearch(Request $request)
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = User::where('role', 'doctor')->get();
        $searchTerm = $request->input('search');
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        $patients = Patient::where('type', 'outpatient')->where(function ($query) use ($searchTerm) {
            $query->orWhere('first_name', 'LIKE', '%' . $searchTerm . '%');
            $query->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%');
            $query->orWhere('diagnosis', 'LIKE', '%' . $searchTerm . '%');
        })->paginate(5);

        return view('admin.patient.patient_outpatient_search', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
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
                    'date' => $request->input('date'),
                    'time' => $request->input('time'),
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
                    $patient->date = $request->input('date');
                    $patient->time = $request->input('time');
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
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $patients = Patient::where('type', 'admitted_patient')->orderBy('created_at', 'desc')->paginate(5);

        return view('admin.patient.patient_admitted', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
    }

    public function patientAdmittedSearch(Request $request)
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = User::where('role', 'doctor')->get();
        $searchTerm = $request->input('search');
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $patients = Patient::whereNull('discharged_date')->where(function ($query) use ($searchTerm) {
            $query->orWhere('first_name', 'LIKE', '%' . $searchTerm . '%');
            $query->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%');
            $query->orWhere('diagnosis', 'LIKE', '%' . $searchTerm . '%');
        })->paginate(5);

        return view('admin.patient.patient_admitted_search', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
    }

    public function patientReport(Request $request)
    {
        $patient = Patient::where('id', $request->input('patient_id'))->first();
        $currentYear = Carbon::now()->year; // Get current year
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $doctor = User::where('id',$patient->physician)->first();

        return view('admin.report.patient_report', compact('patient', 'currentTime', 'currentDate','doctor'));
    }

    public function notification()
    {

        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        return view('admin.notification.notification', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate'));

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
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        // Current year
        $year = Carbon::now()->year;

        $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->whereNotNull('admitted_date')
            ->pluck('year')
            ->toArray();

        $outpatientYears = Patient::select(DB::raw('YEAR(date) as year'))
            ->distinct()
            ->whereNotNull('date')
            ->pluck('year')
            ->toArray();

        $combinedYears = array_merge($admittedYears, $outpatientYears);

        $uniqueCombinedYears = array_unique($combinedYears);


        // Initialize an array to store gender counts for each month
        $genderCountsByMonth = [];
        $totalMaleCount = 0;
        $totalFemaleCount = 0;

        // Loop through each month of the current year
        for ($month = 1; $month <= 12; $month++) {
            // Get the start and end dates of the current month
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            // Retrieve admitted patient data for the current month
            $patients = Patient::select('gender')
                ->whereBetween('admitted_date', [$startDate, $endDate])
                ->orWhereBetween('date', [$startDate, $endDate])
                ->get();

            // Count the number of male and female patients for the current month
            $maleCount = $patients->where('gender', 'male')->count();
            $femaleCount = $patients->where('gender', 'female')->count();

            $totalMaleCount += $maleCount;
            $totalFemaleCount += $femaleCount;

            // Store the gender counts for the current month in the array
            $genderCountsByMonth[] = [
                'month' => $startDate->format('F'),
                'male' => $maleCount,
                'female' => $femaleCount,
            ];
        }

        $totalGenderCounts = $totalMaleCount + $totalFemaleCount;



        return view('admin.patient-demo.gender', compact('profile', 'limitNotifications', 'count', 'genderCountsByMonth', 'year', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'totalGenderCounts'));
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
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        //Selected Year
        $year = $request->input('year');

        $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->whereNotNull('admitted_date')
            ->pluck('year')
            ->toArray();

        $outpatientYears = Patient::select(DB::raw('YEAR(date) as year'))
            ->distinct()
            ->whereNotNull('date')
            ->pluck('year')
            ->toArray();

        $combinedYears = array_merge($admittedYears, $outpatientYears);

        $uniqueCombinedYears = array_unique($combinedYears);

        // Initialize an array to store gender counts for each month
        $genderCountsByMonth = [];
        $totalMaleCount = 0;
        $totalFemaleCount = 0;

        // Loop through each month of the current year
        for ($month = 1; $month <= 12; $month++) {
            // Get the start and end dates of the current month
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            // Retrieve admitted patient data for the current month
            $patients = Patient::select('gender')
                ->whereBetween('admitted_date', [$startDate, $endDate])
                ->orWhereBetween('date', [$startDate, $endDate])
                ->get();

            // Count the number of male and female patients for the current month
            $maleCount = $patients->where('gender', 'male')->count();
            $femaleCount = $patients->where('gender', 'female')->count();

            $totalMaleCount += $maleCount;
            $totalFemaleCount += $femaleCount;

            // Store the gender counts for the current month in the array
            $genderCountsByMonth[] = [
                'month' => $startDate->format('F'),
                'male' => $maleCount,
                'female' => $femaleCount,
            ];
        }

        $totalGenderCounts = $totalMaleCount + $totalFemaleCount;


        return view('admin.patient-demo.gender_search', compact('profile', 'limitNotifications', 'count', 'genderCountsByMonth', 'year', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'totalGenderCounts'));
    }

    public function genderReport(Request $request)
    {
        $year = $request->input('year');
        $currentYear = Carbon::now()->year; // Get current year
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->pluck('year')
            ->toArray();

        // Initialize an array to store gender counts for each month
        $genderCountsByMonth = [];
        $totalMaleCount = 0;
        $totalFemaleCount = 0;

        // Loop through each month of the current year
        for ($month = 1; $month <= 12; $month++) {
            // Get the start and end dates of the current month
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            // Retrieve admitted patient data for the current month
            $patients = Patient::select('gender')
                ->whereBetween('admitted_date', [$startDate, $endDate])
                ->orWhereBetween('date', [$startDate, $endDate])
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

        $totalGenderCounts = $totalMaleCount + $totalFemaleCount;

        return view('admin.report.gender_report', compact('genderCountsByMonth', 'year', 'currentTime', 'currentDate', 'totalGenderCounts'));

    }

    public function ageDemo()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        // Get the current year
        $year = Carbon::now()->year;

        $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->whereNotNull('admitted_date')
            ->pluck('year')
            ->toArray();

        $outpatientYears = Patient::select(DB::raw('YEAR(date) as year'))
            ->distinct()
            ->whereNotNull('date')
            ->pluck('year')
            ->toArray();

        $combinedYears = array_merge($admittedYears, $outpatientYears);

        $uniqueCombinedYears = array_unique($combinedYears);

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

        $totalPatientCount = 0;

        // Loop through each month of the current year
        for ($month = 1; $month <= 12; $month++) {
            // Get the start and end dates of the current month
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            // Retrieve admitted patient data for the current month
            $patients = Patient::select('birthdate')
                ->whereBetween('admitted_date', [$startDate, $endDate])
                ->orWhereBetween('date', [$startDate, $endDate])
                ->get();

            // Increment the total patient count for this month
            $totalPatientCount += $patients->count();

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

        return view('admin.patient-demo.age', compact('profile', 'limitNotifications', 'count', 'labels', 'datasets', 'year', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'totalPatientCount'));
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
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        // Get the current year
        $year = $request->input('year');
        $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->whereNotNull('admitted_date')
            ->pluck('year')
            ->toArray();

        $outpatientYears = Patient::select(DB::raw('YEAR(date) as year'))
            ->distinct()
            ->whereNotNull('date')
            ->pluck('year')
            ->toArray();

        $combinedYears = array_merge($admittedYears, $outpatientYears);

        $uniqueCombinedYears = array_unique($combinedYears);
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

        $totalPatientCount = 0;

        // Loop through each month of the current year
        for ($month = 1; $month <= 12; $month++) {
            // Get the start and end dates of the current month
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            // Retrieve admitted patient data for the current month
            $patients = Patient::select('birthdate')
                ->whereBetween('admitted_date', [$startDate, $endDate])
                ->get();

            // Increment the total patient count for this month
            $totalPatientCount += $patients->count();

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

        return view('admin.patient-demo.age_search', compact('profile', 'limitNotifications', 'count', 'labels', 'datasets', 'year', 'totalPatientCount', 'uniqueCombinedYears', 'currentTime', 'currentDate'));
    }

    public function ageReport(Request $request)
    {
        $year = $request->input('year');
        $currentYear = Carbon::now()->year; // Get current year
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

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

        $totalPatientCount = 0;

        // Loop through each month of the current year
        for ($month = 1; $month <= 12; $month++) {
            // Get the start and end dates of the current month
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            // Retrieve admitted patient data for the current month
            $patients = Patient::select('birthdate')
                ->whereBetween('admitted_date', [$startDate, $endDate])
                ->orWhereBetween('date', [$startDate, $endDate])
                ->get();

            // Increment the total patient count for this month
            $totalPatientCount += $patients->count();

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

        return view('admin.report.age_report', compact('labels', 'datasets', 'year', 'currentTime', 'currentDate', 'totalPatientCount'));

    }

    public function admittedDemo()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        // Get the current year
        $year = Carbon::now()->year;

        $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->where('type', 'admitted_patient')
            ->whereNotNull('admitted_date')
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
            $admitPatientCounts = Patient::where('type', 'admitted_patient')->whereBetween('admitted_date', [$startDate, $endDate])
                ->count();

            // Store the admit patient count for the current month in the array
            $admitPatientCountsByMonth[] = [
                'month' => $startDate->format('F'),
                'count' => $admitPatientCounts,
            ];
        }

        $totalAdmittedPatients = array_sum(array_column($admitPatientCountsByMonth, 'count'));

        return view('admin.patient-demo.admitted', compact('profile', 'limitNotifications', 'count', 'admitPatientCountsByMonth', 'totalAdmittedPatients', 'year', 'admittedYears', 'currentTime', 'currentDate'));
    }


    public function admittedDemoSearch(Request $request)
    {
        $request->validate([
            'year' => 'required',
        ]);

        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        // Get the current year
        $year = $request->input('year');
        $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->where('type', 'admitted_patient')
            ->whereNotNull('admitted_date')
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
            $admitPatientCounts = Patient::where('type', 'admitted_patient')->whereBetween('admitted_date', [$startDate, $endDate])
                ->count();

            // Store the admit patient count for the current month in the array
            $admitPatientCountsByMonth[] = [
                'month' => $startDate->format('F'),
                'count' => $admitPatientCounts,
            ];
        }

        $totalAdmittedPatients = array_sum(array_column($admitPatientCountsByMonth, 'count'));

        return view('admin.patient-demo.admitted_search', compact('profile', 'limitNotifications', 'count', 'admitPatientCountsByMonth', 'totalAdmittedPatients', 'year', 'admittedYears', 'currentTime', 'currentDate'));
    }

    public function admittedDemoReport(Request $request)
    {
        $year = $request->input('year');
        $currentYear = Carbon::now()->year; // Get current year
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->where('type', 'admitted_patient')
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
            $admitPatientCounts = Patient::where('type', 'admitted_patient')->whereBetween('admitted_date', [$startDate, $endDate])
                ->count();

            // Store the admit patient count for the current month in the array
            $admitPatientCountsByMonth[] = [
                'month' => $startDate->format('F'),
                'count' => $admitPatientCounts,
            ];
        }

        $totalAdmittedPatients = array_sum(array_column($admitPatientCountsByMonth, 'count'));

        return view('admin.report.admit_report', compact('admitPatientCountsByMonth', 'year', 'currentTime', 'currentDate', 'totalAdmittedPatients'));
    }

    public function outpatientDemo()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        // Get the current year
        $year = Carbon::now()->year;

        $admittedYears = Patient::select(DB::raw('YEAR(date) as year'))
            ->distinct()
            ->where('type', 'outpatient')
            ->whereNotNull('date')
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
            $admitPatientCounts = Patient::where('type', 'outpatient')->whereBetween('date', [$startDate, $endDate])
                ->count();

            // Store the admit patient count for the current month in the array
            $admitPatientCountsByMonth[] = [
                'month' => $startDate->format('F'),
                'count' => $admitPatientCounts,
            ];
        }

        $totalAdmittedPatients = array_sum(array_column($admitPatientCountsByMonth, 'count'));

        return view('admin.patient-demo.outpatient', compact('profile', 'limitNotifications', 'count', 'admitPatientCountsByMonth', 'totalAdmittedPatients', 'year', 'admittedYears', 'currentTime', 'currentDate'));
    }

    public function outpatientDemoSearch(Request $request)
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        // Get the current year
        $year = $request->input('year');

        $admittedYears = Patient::select(DB::raw('YEAR(date) as year'))
            ->distinct()
            ->where('type', 'outpatient')
            ->whereNotNull('date')
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
            $admitPatientCounts = Patient::where('type', 'outpatient')->whereBetween('date', [$startDate, $endDate])
                ->count();

            // Store the admit patient count for the current month in the array
            $admitPatientCountsByMonth[] = [
                'month' => $startDate->format('F'),
                'count' => $admitPatientCounts,
            ];
        }

        $totalAdmittedPatients = array_sum(array_column($admitPatientCountsByMonth, 'count'));

        return view('admin.patient-demo.outpatient_search', compact('profile', 'limitNotifications', 'count', 'admitPatientCountsByMonth', 'totalAdmittedPatients', 'year', 'admittedYears', 'currentTime', 'currentDate'));
    }

    public function outpatientDemoReport(Request $request)
    {
        $year = $request->input('year');
        $currentYear = Carbon::now()->year; // Get current year
        $currentDate = date('Y-m-d');
        $currentTime = Carbon::now()->toTimeString(); // Get current time (24-hour format)

        $admittedYears = Patient::select(DB::raw('YEAR(date) as year'))
            ->distinct()
            ->where('type', 'outpatient')
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
            $admitPatientCounts = Patient::where('type', 'outpatient')->whereBetween('date', [$startDate, $endDate])
                ->count();

            // Store the admit patient count for the current month in the array
            $admitPatientCountsByMonth[] = [
                'month' => $startDate->format('F'),
                'count' => $admitPatientCounts,
            ];
        }

        $totalAdmittedPatients = array_sum(array_column($admitPatientCountsByMonth, 'count'));

        return view('admin.report.outpatient_report', compact('admitPatientCountsByMonth', 'year', 'currentTime', 'currentDate', 'totalAdmittedPatients'));

    }
    public function diagnoseDemo()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        $AdmittedDiagnoseData = Patient::select('diagnosis')
            ->distinct()
            ->whereNotNull('diagnosis')
            ->pluck('diagnosis')
            ->toArray();

        $currentYear = Carbon::now()->year;

        $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->whereNotNull('admitted_date')
            ->pluck('year')
            ->toArray();

        $outpatientYears = Patient::select(DB::raw('YEAR(date) as year'))
            ->distinct()
            ->whereNotNull('date')
            ->pluck('year')
            ->toArray();

        $combinedYears = array_merge($admittedYears, $outpatientYears);

        $uniqueCombinedYears = array_unique($combinedYears);

        return view('admin.patient-demo.diagnose', compact('profile', 'limitNotifications', 'count', 'AdmittedDiagnoseData', 'uniqueCombinedYears', 'currentTime', 'currentDate'));
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
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        $AdmittedDiagnoseData = Patient::select('diagnosis')
            ->distinct()
            ->whereNotNull('diagnosis')
            ->pluck('diagnosis')
            ->toArray();

        $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->whereNotNull('admitted_date')
            ->pluck('year')
            ->toArray();

        $outpatientYears = Patient::select(DB::raw('YEAR(date) as year'))
            ->distinct()
            ->whereNotNull('date')
            ->pluck('year')
            ->toArray();

        $combinedYears = array_merge($admittedYears, $outpatientYears);

        $uniqueCombinedYears = array_unique($combinedYears);

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
                ->orWhereBetween('date', [$startDate, $endDate])
                ->where('diagnosis', $specificDiagnosis)
                ->count();

            // Store the diagnose patient count for the current month in the array
            $diagnosePatientCountsByMonth[] = [
                'month' => $startDate->format('F'),
                'count' => $diagnosePatientCounts,
            ];
        }

        return view('admin.patient-demo.diagnose_search', compact('profile', 'limitNotifications', 'count', 'diagnosePatientCountsByMonth', 'AdmittedDiagnoseData', 'uniqueCombinedYears', 'selectedYear', 'specificDiagnosis', 'currentTime', 'currentDate'));
    }

    public function diagnoseReport(Request $request)
    {
        $year = $request->input('year');
        $diagnose = $request->input('diagnose');
        $currentYear = Carbon::now()->year; // Get current year
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

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
                ->orWhereBetween('date', [$startDate, $endDate])
                ->where('diagnosis', $specificDiagnosis)
                ->count();

            // Store the diagnose patient count for the current month in the array
            $diagnosePatientCountsByMonth[] = [
                'month' => $startDate->format('F'),
                'count' => $diagnosePatientCounts,
            ];
        }

        return view('admin.report.diagnose_report', compact('diagnosePatientCountsByMonth', 'year', 'currentTime', 'currentDate', 'diagnose'));
    }

    public function diagnoseTrend()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentYear = Carbon::now()->year;
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        $admittedDiagnoses = Patient::select('diagnosis')
            ->selectRaw('COUNT(*) as total_occurrences')
            ->whereYear('admitted_date', $currentYear)
            ->groupBy('diagnosis')
            ->get();

        $dateDiagnoses = Patient::select('diagnosis')
            ->selectRaw('COUNT(*) as total_occurrences')
            ->whereYear('date', $currentYear)
            ->groupBy('diagnosis')
            ->get();

        // Merge the two collections and sort them by total_occurrences in descending order
        $mergedDiagnoses = $admittedDiagnoses->concat($dateDiagnoses);

        $rankedDiagnosis = $mergedDiagnoses->groupBy('diagnosis')
            ->map(function ($group) {
                $firstItem = $group->first();
                return [
                    'diagnosis' => $firstItem['diagnosis'],
                    'total_occurrences' => $group->sum('total_occurrences'),
                ];
            })
            ->sortByDesc('total_occurrences')
            ->values();

        $diagnosisCount = $rankedDiagnosis->count();

        $limitDiagnosis = $rankedDiagnosis->take(5);

        $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->whereNotNull('admitted_date')
            ->pluck('year')
            ->toArray();

        $outpatientYears = Patient::select(DB::raw('YEAR(date) as year'))
            ->distinct()
            ->whereNotNull('date')
            ->pluck('year')
            ->toArray();

        $combinedYears = array_merge($admittedYears, $outpatientYears);

        $uniqueCombinedYears = array_unique($combinedYears);

        // Count the number of unique years
        $countUniqueYears = count($uniqueCombinedYears);

        $diagnoseData = Patient::select('diagnosis')
            ->distinct()
            ->pluck('diagnosis')
            ->toArray();

        return view('admin.trend.diagnose_trend', compact('profile', 'limitNotifications', 'count', 'diagnoseData', 'limitDiagnosis', 'countUniqueYears', 'rankedDiagnosis', 'currentTime', 'currentDate'));
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
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        $admittedDiagnoses = Patient::select('diagnosis')
            ->selectRaw('COUNT(*) as total_occurrences')
            ->whereYear('admitted_date', $currentYear)
            ->groupBy('diagnosis')
            ->get();

        $dateDiagnoses = Patient::select('diagnosis')
            ->selectRaw('COUNT(*) as total_occurrences')
            ->whereYear('date', $currentYear)
            ->groupBy('diagnosis')
            ->get();

        // Merge the two collections and sort them by total_occurrences in descending order
        $mergedDiagnoses = $admittedDiagnoses->concat($dateDiagnoses);

        $rankedDiagnosis = $mergedDiagnoses->groupBy('diagnosis')
            ->map(function ($group) {
                $firstItem = $group->first();
                return [
                    'diagnosis' => $firstItem['diagnosis'],
                    'total_occurrences' => $group->sum('total_occurrences'),
                ];
            })
            ->sortByDesc('total_occurrences')
            ->values();

        $diagnosisCount = $rankedDiagnosis->count();

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
            ->get();

        // Initialize an array to store the yearly trend data
        $yearlyTrendData = [];

        // Loop through the patient data to calculate the yearly trend
        $currentYear = null;
        $yearlyCount = 0;

        foreach ($patients as $patient) {
            $admittedDate = Carbon::parse($patient->admitted_date); // Convert to Carbon object
            $outpatientDate = Carbon::parse($patient->date); // Convert to Carbon object

            $year = $admittedDate->format('Y');
            $anotherYear = $outpatientDate->format('Y');

            if ($year !== $currentYear || $anotherYear !== $currentYear) {
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

        // Initialize variables
        $currentMonth = null;
        $monthlyCount = 0;
        $monthlyTrendData = [];

        // Loop through the patient data to calculate the monthly trend
        foreach ($patients as $patient) {
            $admittedDate = Carbon::parse($patient->admitted_date); // Convert to Carbon object
            $outpatientDate = Carbon::parse($patient->date); // Convert to Carbon object

            $admittedMonth = $admittedDate->format('F');
            $outpatientMonth = $outpatientDate->format('F');

            // Check if the admitted month is different from the current month
            if ($admittedMonth !== $currentMonth) {
                // Save the count for the previous month
                if ($currentMonth !== null) {
                    $monthlyTrendData[] = [
                        'month' => $currentMonth,
                        'count' => $monthlyCount,
                    ];
                }

                // Reset the count for the current month
                $currentMonth = $admittedMonth;
                $monthlyCount = 1;
            } else {
                $monthlyCount++;
            }

            // Check if the outpatient month is different from the admitted month
            if ($outpatientMonth !== $admittedMonth) {
                // Save the count for the outpatient month
                $monthlyTrendData[] = [
                    'month' => $outpatientMonth,
                    'count' => 1,
                ];
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

        return view('admin.trend.diagnose_trend_search', compact('profile', 'limitNotifications', 'count', 'diagnoseData', 'limitDiagnosis', 'monthlyTrendData', 'specificDiagnosis', 'yearlyTrendData', 'rankedDiagnosis', 'currentTime', 'currentDate'));
    }


    public function diagnoseTrendReport(Request $request)
    {
        $year = $request->input('year');
        $currentYear = Carbon::now()->year; // Get current year
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

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
            $outpatientDate = Carbon::parse($patient->date); // Convert to Carbon object

            $year = $admittedDate->format('Y');
            $anotherYear = $outpatientDate->format('Y');

            if ($year !== $currentYear || $anotherYear !== $currentYear) {
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

        // Create an array with all month names
        $allMonths = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        // Initialize counts for all months to 0
        $monthlyCounts = array_fill_keys($allMonths, 0);

        // Loop through the patient data to calculate the monthly trend
        $currentMonth = null;
        $monthlyCount = 0;
        foreach ($patients as $patient) {
            $admittedDate = Carbon::parse($patient->admitted_date); // Convert to Carbon object
            $outpatientDate = Carbon::parse($patient->date); // Convert to Carbon object

            $month = $admittedDate->format('F');
            $anotherMonth = $outpatientDate->format('F');

            // Increment the counts for the relevant months
            $monthlyCounts[$month]++;
            $monthlyCounts[$anotherMonth]++;

            if ($month !== $currentMonth || $anotherMonth !== $currentMonth) {
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

        return view('admin.report.diagnose_trend_report', compact('yearlyTrendData', 'monthlyTrendData', 'year', 'currentTime', 'currentDate', 'specificDiagnosis'));
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