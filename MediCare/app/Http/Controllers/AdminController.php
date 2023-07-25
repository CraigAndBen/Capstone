<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
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

        // Retrieve the rank 1 diagnosis for the current year
        $rank1Diagnosis = $rankedDiagnosis->firstWhere('month', Carbon::now()->month);

        $data = Patient::whereYear('admitted_date', $currentYear)
            ->selectRaw('MONTH(admitted_date) as month, COUNT(*) as count')
            ->groupBy('month')
            ->get();

        // Prepare data for the chart
        $labels = $data->map(function ($item) {
            return Carbon::createFromDate(null, $item->month, null)->format('F'); // Format as full month name
        });
        $values = $data->pluck('count');

        return view('admin_dashboard', compact('profile', 'limitNotifications', 'count', 'labels', 'values', 'patientCount', 'rankedDiagnosis', 'rank1Diagnosis'));
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
        $patients = Patient::all();
        $limitPatients = $patients->take(5);

        return view('admin.patient.patient', compact('limitPatients', 'profile', 'doctors', 'limitNotifications', 'count'));

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
            $columns = Schema::getColumnListing('patient'); // Replace 'your_table' with the actual table name

            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', '%' . $searchTerm . '%');
            }
        })->get();

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
            'admitted_date' => $request->input('admitted_date'),
            'discharged_date' => $request->input('discharged_date'),
            'room_number' => $request->input('room_number'),
            'bed_number' => $request->input('bed_number'),
            'physician' => $request->input('physician'),
            'medical_condition' => $request->input('medical_condition'),
            'diagnosis' => $request->input('diagnosis'),
            'medication' => $request->input('medication'),
        ]);

        return back()->with('success', 'Patient added sucessfully.');

    }

    public function patientUpdate(Request $request)
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

        $patient = Patient::where('id', $request->id)->first();

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

        ];

        $appointmentChange = $this->hasChanges($patient, $patientUpdatedData);

        if ($appointmentChange) {
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

            $patient->save();

            return redirect()->back()->with('success', 'Patient Information Updated Successfully.');
        } else {
            return redirect()->back()->with('info', 'No changes were made.');
        }
    }

    public function patienAdmittedtList()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = User::where('role', 'doctor')->get();
        $patients = Patient::whereNull('discharged_date')->get();
        $limitPatients = $patients->take(5);

        return view('admin.patient.patient_admitted', compact('limitPatients', 'profile', 'doctors', 'limitNotifications', 'count'));
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
            $columns = Schema::getColumnListing('patient'); // Replace 'your_table' with the actual table name

            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', '%' . $searchTerm . '%');
            }
        })->get();

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

        // Retrieve data from the database
        $data = Patient::select('gender', \DB::raw('COUNT(*) as count'))
            ->groupBy('gender')
            ->get();

        // Prepare data for the chart
        $labels = $data->pluck('gender');
        $values = $data->pluck('count');

        return view('admin.patient-demo.gender', compact('profile', 'limitNotifications', 'count', 'labels', 'values'));
    }

    public function genderFetch(Request $request)
    {
        $selectedYear = $request->input('year');

        // Fetch data for the selected year from your data source
        $data = Patient::whereYear('admitted_at', $selectedYear)
            ->select('label', 'value')
            ->get();

        // Prepare data for the chart
        $labels = $data->pluck('label');
        $values = $data->pluck('value');

        return response()->json([
            'labels' => $labels,
            'values' => $values
        ]);
    }

    public function ageDemo()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();

         // Get the current year
         $currentYear = Carbon::now()->year;
         $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
         ->distinct()
         ->pluck('year')
         ->toArray();

         // Initialize an array to store the age group counts for each month
         $ageGroupsByMonth = [];
 
         // Create an array of age group labels
         $ageGroups = [
             '0-9', '10-19', '20-29', '30-39', '40-49',
             '50-59', '60-69', '70-79', '80-89', '90+',
         ];
 
         // Loop through each month of the current year
         for ($month = 1; $month <= 12; $month++) {
             // Get the start and end dates of the current month
             $startDate = Carbon::createFromDate($currentYear, $month, 1)->startOfMonth();
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

        return view('admin.patient-demo.age', compact('profile', 'limitNotifications', 'count', 'labels','datasets','currentYear','admittedYears'));
    }

    public function ageSearch(Request $request)
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();

                 // Get the current year
                 $yearSelected = $request->input('year');
                 $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
                 ->distinct()
                 ->pluck('year')
                 ->toArray();
        
                 // Initialize an array to store the age group counts for each month
                 $ageGroupsByMonth = [];
         
                 // Create an array of age group labels
                 $ageGroups = [
                     '0-9', '10-19', '20-29', '30-39', '40-49',
                     '50-59', '60-69', '70-79', '80-89', '90+',
                 ];
         
                 // Loop through each month of the current year
                 for ($month = 1; $month <= 12; $month++) {
                     // Get the start and end dates of the current month
                     $startDate = Carbon::createFromDate($yearSelected, $month, 1)->startOfMonth();
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

        return view('admin.patient-demo.age_search', compact('profile', 'limitNotifications', 'count', 'labels','datasets','yearSelected','admittedYears'));
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

    private function calculateAgeGroup($birthdate)
    {
        $age = Carbon::parse($birthdate)->age;

        // Determine the age group label
        $ageGroup = floor($age / 10) * 10 . '-' . (floor($age / 10) * 10 + 9) . ' yrs old';

        // Determine the group name based on the age range
        $groupName = $age >= 0 && $age <= 20 ? 'Children' : ($age >= 21 && $age <= 40 ? 'Young Adults' : ($age >= 41 && $age <= 60 ? 'Middle-aged Adults' : 'Senior Adults'));

        return [
            'age_group_label' => $ageGroup,
            'group_name' => $groupName,
        ];
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