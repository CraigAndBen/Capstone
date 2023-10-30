<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Report;
use App\Models\Patient;
use App\Models\Diagnose;
use Barryvdh\DomPDF\PDF;
use Illuminate\View\View;
use App\Models\Medication;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        // Get the current year
        $currentYear = Carbon::now()->year;

        $admittedPatientsByMonth = DB::table('patients')
            ->select(DB::raw('DATE_FORMAT(admitted_date, "%M") as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('admitted_date', $currentYear)
            ->groupBy('month')
            ->get();

        $outpatientPatientsByMonth = DB::table('patients')
            ->select(DB::raw('DATE_FORMAT(date, "%M") as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('date', $currentYear)
            ->groupBy('month')
            ->get();


        $patientsByYear = DB::table('patients')
            ->whereYear('admitted_date', $currentYear)
            ->orWhereYear('date', $currentYear)
            ->get();

        $patientCount = $patientsByYear->count();

        $rankedDiagnosis = Diagnose::select('diagnose', DB::raw('MONTH(date) as month'))
            ->selectRaw('COUNT(*) as total_occurrences')
            ->whereYear('date', $currentYear)
            ->whereNotNull('diagnose')
            ->groupBy('diagnose', 'month')
            ->orderByDesc('total_occurrences')
            ->get();

        $diagnosesWithOccurrences = Diagnose::select('diagnose')
            ->selectRaw('COUNT(*) as total_occurrences')
            ->whereYear('date', $currentYear)
            ->groupBy('diagnose')
            ->orderBy('total_occurrences', 'desc') // Order by occurrences in descending order
            ->take(5) // Limit the result to the top 5 diagnoses
            ->get();

        $diagnosisCount = $diagnosesWithOccurrences->count();

        // Retrieve the rank 1 diagnosis for the current year
        $rank1Diagnosis = $rankedDiagnosis->firstWhere('month', Carbon::now()->month);

        return view('admin_dashboard', compact('profile', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'admittedPatientsByMonth', 'outpatientPatientsByMonth', 'patientCount', 'rankedDiagnosis', 'diagnosesWithOccurrences', 'diagnosisCount', 'rank1Diagnosis'));
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
        $patients = Patient::orderBy('created_at', 'desc')
            ->paginate(5);
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

        $patients = Patient::where('first_name', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%')
            ->paginate(5);

        return view('admin.patient.patient_search', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
    }

    public function patientStore(Request $request)
    {
        $type = $request->input('patient_type');

        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'physician' => 'required|string|max:255',
        ]);

        if ($type == 'admitted_patient') {

            $request->validate([
                'admitted_date' => 'required|date',
                'admitted_time' => 'required|time',
            ]);

        } elseif ($type == 'outpatient') {
            $request->validate([
                'date' => 'required',
                'time' => 'required',
            ]);
        }

        $patientData = $request->only([
            'first_name' => 'first_name',
            'middle_name' => 'middle_name',
            'last_name' => 'last_name',
            'street' => 'street',
            'gender' => 'gender',
            'brgy' => 'brgy',
            'city' => 'city',
            'province' => 'province',
            'birthdate' => 'birthdate',
            'phone' => 'phone',
            'type' => 'type',
            'admitted_date' => 'admitted_date',
            'admitted_time' => 'admitted_time',
            'discharged_date' => 'discharged_date',
            'discharged_time' => 'discharged_time',
            'room_number' => 'room_number',
            'bed_number' => 'bed_number',
            'date' => 'date',
            'time' => 'time',
            'physician' => 'physician',
            'medical_condition' => 'medical_condition',
            'guardian_first_name' => 'guardian_first_name',
            'guardian_last_name' => 'guardian_last_name',
            'guardian_birthdate' => 'guardian_birthdate',
            'relationship' => 'relationship',
            'guardian_phone' => 'guardian_phone',
            'guardian_email' => 'guardian_email',
        ]);

        $patient = Patient::create($patientData);

        // Retrieve the ID of the last inserted patient
        $patientId = $patient->id;
        $patientType = $request->input('type');

        $diagnosisDates = $request->input('diagnosesDate', []); // Retrieve an array of diagnosis dates
        $diagnosisTimes = $request->input('diagnosesTime', []); // Retrieve an array of diagnosis dates
        $diagnoses = $request->input('diagnoses', []); // Retrieve an array of diagnoses

        // Iterate through the diagnosis data and save them
        foreach ($diagnosisDates as $key => $diagnosisDate) {
            $diagnosis = new Diagnose();
            $diagnosis->patient_id = $patientId; // Assuming you have the patient object
            $diagnosis->patient_type = $patientType; // Assuming you have the patient object

            // Assign diagnosis data from the arrays
            $diagnosis->date = $diagnosisDate;
            $diagnosis->time = $diagnosisTimes[$key];
            $diagnosis->diagnose = $diagnoses[$key];

            // Save the diagnosis record
            $diagnosis->save();
        }

        $medicationNames = $request->input('medicationName', []); // Retrieve an array of medication names
        $medicationDates = $request->input('medicationDate', []); // Retrieve an array of medication dates
        $dosages = $request->input('medicationDosage', []); // Retrieve an array of dosages
        $durations = $request->input('medicationDuration', []); // Retrieve an array of durations
        $medicationTimes = $request->input('medicationTime', []); // Retrieve an array of medication times

        // Iterate through the medication data and save them
        foreach ($medicationNames as $key => $medicationName) {
            $medication = new Medication();
            $medication->patient_id = $patientId; // Assuming you have the patient object
            $medication->patient_type = $patientType; // Assuming you have the patient object

            // Assign medication data from the arrays
            $medication->medication_name = $medicationName;
            $medication->date = $medicationDates[$key];
            $medication->dosage = $dosages[$key];
            $medication->duration = $durations[$key];
            $medication->time = $medicationTimes[$key];

            // Save the medication record
            $medication->save();
        }



        return back()->with('success', 'Patient added successfully.');
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
                    'last_name' => 'required|string|max:255',
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
                    'guardian_first_name' => $request->input('guardian_first_name'),
                    'guardian_last_name' => $request->input('guardian_last_name'),
                    'guardian_birthdate' => $request->input('guardian_birthdate'),
                    'relationship' => $request->input('relationship'),
                    'guardian_phone' => $request->input('guardian_phone'),
                    'guardian_email' => $request->input('guardian_email'),

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
                    'last_name' => 'required|string|max:255',
                    'admitted_date' => 'required|date',
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
                    'admitted_time' => $request->input('admitted_time'),
                    'discharged_date' => $request->input('discharged_date'),
                    'discharged_time' => $request->input('discharged_time'),
                    'room_number' => $request->input('room_number'),
                    'bed_number' => $request->input('bed_number'),
                    'physician' => $request->input('physician'),
                    'medical_condition' => $request->input('medical_condition'),
                    'guardian_first_name' => $request->input('guardian_first_name'),
                    'guardian_last_name' => $request->input('guardian_last_name'),
                    'guardian_birthdate' => $request->input('guardian_birthdate'),
                    'relationship' => $request->input('relationship'),
                    'guardian_phone' => $request->input('guardian_phone'),
                    'guardian_email' => $request->input('guardian_email'),

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
                    $patient->admitted_time = $request->input('admitted_time');
                    $patient->discharged_date = $request->input('discharged_date');
                    $patient->discharged_time = $request->input('discharged_time');
                    $patient->room_number = $request->input('room_number');
                    $patient->bed_number = $request->input('bed_number');
                    $patient->physician = $request->input('physician');
                    $patient->medical_condition = $request->input('medical_condition');
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
        })->paginate(5);

        return view('admin.patient.patient_admitted_search', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
    }

    public function viewPatientReport(Request $request)
    {

        $profile = auth()->user();
        $patient = Patient::where('id', $request->input('patient_id'))->first();
        $currentYear = Carbon::now()->year; // Get current year
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $doctor = User::where('id', $patient->physician)->first();
        $diagnoses = Diagnose::where('patient_id', $patient->id)->get();
        $medications = Medication::where('patient_id', $patient->id)->get();
        $randomNumber = mt_rand(100, 999);
        $reference = 'PIR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

        $data = [
            'patient' => $patient,
            'currentTime' => $currentTime,
            'currentDate' => $currentDate,
            'profile' => $profile,
            'doctor' => $doctor,
            'diagnoses' => $diagnoses,
            'medications' => $medications,
            'reference' => $reference,
        ];

        $pdf = app('dompdf.wrapper')->loadView('admin.report.patient_report', $data);

        return $pdf->stream('patient_report.pdf');

    }

    public function downloadPatientReport(Request $request)
    {

        $profile = auth()->user();
        $patient = Patient::where('id', $request->input('patient_id'))->first();
        $currentYear = Carbon::now()->year; // Get current year
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $doctor = User::where('id', $patient->physician)->first();
        $diagnoses = Diagnose::where('patient_id', $patient->id)->get();
        $medications = Medication::where('patient_id', $patient->id)->get();
        $randomNumber = mt_rand(100, 999);
        $reference = 'PIR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

        if ($patient->type == 'admitted_patient') {
            $innerContent = '
                Admission Details:
                - Admitted Date and Time: ' . $patient->admitted_date . ' ' . $patient->admitted_time . '
                - Discharged Date and Time: ' . $patient->discharged_date . ' ' . $patient->discharged_time . '
                - Doctor: Dr. ' . $doctor->first_name . ' ' . $doctor->last_name . '
            ';
        } else {
            $innerContent = '
            Appointment Details:
            - Appointment Date and Time: ' . $patient->admitted_date . ' ' . $patient->admitted_time . '
            - Doctor: Dr. ' . $doctor->first_name . ' ' . $doctor->last_name . '
        ';
        }

        $content =
            '           Patient Information Report
            ------------------------

            Report Reference Number: ' . $reference . '
            Report Date and Time: ' . $currentDate . ' ' . $currentTime . '

            Patient Information:
            - Name: ' . $patient->first_name . ' ' . $patient->last_name . '
            - Date of Birth: ' . $patient->birthdate . '
              Address:
                - Street: ' . $patient->street . '
                - Brgy: ' . $patient->brgy . '
                - City: ' . $patient->city . '
                - Province ' . $patient->province . '
              Contact Information: 
                - Email: ' . $patient->email . '
                - Phone: ' . $patient->phone . '
            ' . $innerContent . '

            Report Status: Finalized';

        Report::create([
            'reference_number' => $reference,
            'report_type' => 'Patient Information Report',
            'date' => $currentDate,
            'time' => $currentTime,
            'user_id' => $profile->id,
            'author_type' => $profile->role,
            'content' => $content,
        ]);

        $data = [
            'patient' => $patient,
            'currentTime' => $currentTime,
            'currentDate' => $currentDate,
            'profile' => $profile,
            'doctor' => $doctor,
            'diagnoses' => $diagnoses,
            'medications' => $medications,
            'reference' => $reference,
        ];

        $pdf = app('dompdf.wrapper')->loadView('admin.report.patient_report', $data);

        return $pdf->download('patient_report.pdf');
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

    public function deleteNotification(Request $request)
    {
        $notification = Notification::where('id', $request->input('id'))->first();
        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted successfully');
    }

    public function deleteNotificationAll(Request $request)
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->get(); // Split the string into an array using a delimiter (e.g., comma)

        if ($notifications->isEmpty()) {
            return redirect()->back()->with('info', 'No notification to delete.');

        } else {

            foreach ($notifications as $notification) {
                $notification->delete();
            }

            return redirect()->back()->with('success', 'User deleted successfully');
        }
    }


    public function patientGenderDemo()
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
        $type = 'patient';


        return view('admin.analytics.gender.gender', compact('profile', 'limitNotifications', 'count', 'genderCountsByMonth', 'year', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'totalGenderCounts', 'type'));
    }

    public function admittedGenderDemo()
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

        $uniqueCombinedYears = $admittedYears;


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
        $type = 'admitted';


        return view('admin.analytics.gender.gender', compact('profile', 'limitNotifications', 'count', 'genderCountsByMonth', 'year', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'totalGenderCounts', 'type'));
    }

    public function outpatientGenderDemo()
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

        $admittedYears = Patient::select(DB::raw('YEAR(date) as year'))
            ->distinct()
            ->whereNotNull('date')
            ->pluck('year')
            ->toArray();

        $uniqueCombinedYears = $admittedYears;


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
                ->whereBetween('date', [$startDate, $endDate])
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
        $type = 'outpatient';



        return view('admin.analytics.gender.gender', compact('profile', 'limitNotifications', 'count', 'genderCountsByMonth', 'year', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'totalGenderCounts', 'type'));
    }

    public function patientGenderSearch(Request $request)
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
        $type = $request->input('type');
        $year = $request->input('year');

        if ($type == 'patient') {

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

        } else if ($type == 'admitted') {

            $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
                ->distinct()
                ->whereNotNull('admitted_date')
                ->pluck('year')
                ->toArray();

            $uniqueCombinedYears = array_unique($admittedYears);

        } else if ($type == 'outpatient') {

            $admittedYears = Patient::select(DB::raw('YEAR(date) as year'))
                ->distinct()
                ->whereNotNull('date')
                ->pluck('year')
                ->toArray();

            $uniqueCombinedYears = array_unique($admittedYears);
        }

        // Initialize an array to store gender counts for each month
        $genderCountsByMonth = [];
        $totalMaleCount = 0;
        $totalFemaleCount = 0;

        // Loop through each month of the current year
        for ($month = 1; $month <= 12; $month++) {
            // Get the start and end dates of the current month
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            if ($type == 'patient') {
                $patients = Patient::select('gender')
                    ->whereBetween('admitted_date', [$startDate, $endDate])
                    ->orWhereBetween('date', [$startDate, $endDate])
                    ->get();
            } else if ($type == 'admitted') {
                $patients = Patient::select('gender')
                    ->whereBetween('admitted_date', [$startDate, $endDate])
                    ->get();
            } else if ($type == 'outpatient') {
                $patients = Patient::select('gender')
                    ->whereBetween('date', [$startDate, $endDate])
                    ->get();
            }
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


        return view('admin.analytics.gender.gender_search', compact('profile', 'limitNotifications', 'count', 'genderCountsByMonth', 'year', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'totalGenderCounts', 'type'));
    }

    public function genderReport(Request $request)
    {
        $year = $request->input('year');
        $type = $request->input('type');
        $currentYear = Carbon::now()->year; // Get current year
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $currentTime = $currentDateTime->format('h:i A');
        $randomNumber = mt_rand(100, 999);
        $reference = 'GAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

        switch ($type) {
            case $type == 'patient':

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

                return view('admin.report.gender_report', compact('genderCountsByMonth', 'year', 'currentTime', 'currentDate', 'totalGenderCounts', 'reference'));

            case $type == 'admitted':

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

                return view('admin.report.gender_report', compact('genderCountsByMonth', 'year', 'currentTime', 'currentDate', 'totalGenderCounts', 'reference'));

            case $type == 'outpatient':

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
                        ->whereBetween('date', [$startDate, $endDate])
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

                return view('admin.report.gender_report', compact('genderCountsByMonth', 'year', 'currentTime', 'currentDate', 'totalGenderCounts', 'reference'));
        }
    }

    public function patientAgeDemo()
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
        $totalPatientCount = 0;

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
        $type = 'patient';

        return view('admin.analytics.age.age', compact('profile', 'limitNotifications', 'labels', 'datasets', 'year', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'totalPatientCount', 'count', 'type'));
    }

    public function admittedAgeDemo()
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

        $uniqueCombinedYears = array_unique($admittedYears);
        $totalPatientCount = 0;

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
        $type = 'admitted';

        return view('admin.analytics.age.age', compact('profile', 'limitNotifications', 'labels', 'datasets', 'year', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'totalPatientCount', 'count', 'type'));
    }

    public function outpatientAgeDemo()
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
            ->whereNotNull('date')
            ->pluck('year')
            ->toArray();

        $uniqueCombinedYears = array_unique($admittedYears);
        $totalPatientCount = 0;

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
                ->whereBetween('date', [$startDate, $endDate])
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
        $type = 'outpatient';

        return view('admin.analytics.age.age', compact('profile', 'limitNotifications', 'labels', 'datasets', 'year', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'totalPatientCount', 'count', 'type'));
    }

    public function patientAgeSearch(Request $request)
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
        $type = $request->input('type');

        if ($type == 'patient') {

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

        } else if ($type == 'admitted') {

            $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
                ->distinct()
                ->whereNotNull('admitted_date')
                ->pluck('year')
                ->toArray();

            $uniqueCombinedYears = array_unique($admittedYears);

        } else if ($type == 'outpatient') {

            $admittedYears = Patient::select(DB::raw('YEAR(date) as year'))
                ->distinct()
                ->whereNotNull('date')
                ->pluck('year')
                ->toArray();

            $uniqueCombinedYears = array_unique($admittedYears);
        }

        $totalPatientCount = 0;

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

            if ($type == 'patient') {
                $patients = Patient::select('birthdate')
                ->whereBetween('admitted_date', [$startDate, $endDate])
                ->orWhereBetween('date', [$startDate, $endDate])
                ->get();
            } else if ($type == 'admitted') {
                $patients = Patient::select('birthdate')
                ->whereBetween('admitted_date', [$startDate, $endDate])
                ->get();
            } else if ($type == 'outpatient') {
                $patients = Patient::select('birthdate')
                ->whereBetween('date', [$startDate, $endDate])
                ->get();
            }

            // Retrieve admitted patient data for the current month
   

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
        $type = 'patient';

        return view('admin.analytics.age.age_search', compact('profile', 'limitNotifications', 'count', 'labels', 'datasets', 'year', 'totalPatientCount', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'type'));
    }

    public function ageReport(Request $request)
    {
        $year = $request->input('year');
        $currentYear = Carbon::now()->year; // Get current year
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $currentTime = $currentDateTime->format('h:i A');
        $randomNumber = mt_rand(100, 999);
        $reference = 'AAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;
        
        $type = $request->input('type');

        if ($type == 'patient') {

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

        } else if ($type == 'admitted') {

            $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
                ->distinct()
                ->whereNotNull('admitted_date')
                ->pluck('year')
                ->toArray();

            $uniqueCombinedYears = array_unique($admittedYears);

        } else if ($type == 'outpatient') {

            $admittedYears = Patient::select(DB::raw('YEAR(date) as year'))
                ->distinct()
                ->whereNotNull('date')
                ->pluck('year')
                ->toArray();

            $uniqueCombinedYears = array_unique($admittedYears);
        }

        $totalPatientCount = 0;

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

            if ($type == 'patient') {
                $patients = Patient::select('birthdate')
                ->whereBetween('admitted_date', [$startDate, $endDate])
                ->orWhereBetween('date', [$startDate, $endDate])
                ->get();
            } else if ($type == 'admitted') {
                $patients = Patient::select('birthdate')
                ->whereBetween('admitted_date', [$startDate, $endDate])
                ->get();
            } else if ($type == 'outpatient') {
                $patients = Patient::select('birthdate')
                ->whereBetween('date', [$startDate, $endDate])
                ->get();
            }

            // Retrieve admitted patient data for the current month
   

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

        return view('admin.report.age_report', compact('labels', 'datasets', 'year', 'currentTime', 'currentDate', 'totalPatientCount','reference'));

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

        return view('admin.analytics.admitted.admitted', compact('profile', 'limitNotifications', 'count', 'admitPatientCountsByMonth', 'totalAdmittedPatients', 'year', 'admittedYears', 'currentTime', 'currentDate'));
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

        return view('admin.analytics.admitted.admitted_search', compact('profile', 'limitNotifications', 'count', 'admitPatientCountsByMonth', 'totalAdmittedPatients', 'year', 'admittedYears', 'currentTime', 'currentDate'));
    }

    public function admittedDemoReport(Request $request)
    {
        $year = $request->input('year');
        $currentYear = Carbon::now()->year; // Get current year
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $currentTime = $currentDateTime->format('h:i A');
        $randomNumber = mt_rand(100, 999);
        $reference = 'APAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

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

        return view('admin.report.admit_report', compact('admitPatientCountsByMonth', 'year', 'currentTime', 'currentDate', 'totalAdmittedPatients','reference'));
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

        return view('admin.analytics.outpatient.outpatient', compact('profile', 'limitNotifications', 'count', 'admitPatientCountsByMonth', 'totalAdmittedPatients', 'year', 'admittedYears', 'currentTime', 'currentDate'));
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

        return view('admin.analytics.outpatient.outpatient_search', compact('profile', 'limitNotifications', 'count', 'admitPatientCountsByMonth', 'totalAdmittedPatients', 'year', 'admittedYears', 'currentTime', 'currentDate'));
    }

    public function outpatientDemoReport(Request $request)
    {
        $year = $request->input('year');
        $currentYear = Carbon::now()->year; // Get current year
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $currentTime = $currentDateTime->format('h:i A');
        $randomNumber = mt_rand(100, 999);
        $reference = 'APAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

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

        return view('admin.report.outpatient_report', compact('admitPatientCountsByMonth', 'year', 'currentTime', 'currentDate', 'totalAdmittedPatients','reference'));

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

        $AdmittedDiagnoseData = Diagnose::select('diagnose')
            ->distinct()
            ->whereNotNull('diagnose')
            ->pluck('diagnose')
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

        return view('admin.analytics.diagnose.diagnose', compact('profile', 'limitNotifications', 'count', 'AdmittedDiagnoseData', 'uniqueCombinedYears', 'currentTime', 'currentDate'));
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

        $AdmittedDiagnoseData = Diagnose::select('diagnose')
            ->distinct()
            ->whereNotNull('diagnose')
            ->pluck('diagnose')
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
            $diagnosePatientCounts = Diagnose::whereBetween('date', [$startDate, $endDate])
                ->where('diagnose', $specificDiagnosis)
                ->count();

            // Store the diagnose patient count for the current month in the array
            $diagnosePatientCountsByMonth[] = [
                'month' => $startDate->format('F'),
                'count' => $diagnosePatientCounts,
            ];
        }

        return view('admin.analytics.diagnose.diagnose_search', compact('profile', 'limitNotifications', 'count', 'diagnosePatientCountsByMonth', 'AdmittedDiagnoseData', 'uniqueCombinedYears', 'selectedYear', 'specificDiagnosis', 'currentTime', 'currentDate'));
    }

    public function diagnoseReport(Request $request)
    {
        $year = $request->input('year');
        $diagnose = $request->input('diagnose');
        $currentYear = Carbon::now()->year; // Get current year
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $currentTime = $currentDateTime->format('h:i A');
        $randomNumber = mt_rand(100, 999);
        $reference = 'DPAP-' . $currentDateWithoutHyphens . '-' . $randomNumber;

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
            $diagnosePatientCounts = Diagnose::whereBetween('date', [$startDate, $endDate])
                ->where('diagnose', $specificDiagnosis)
                ->count();

            // Store the diagnose patient count for the current month in the array
            $diagnosePatientCountsByMonth[] = [
                'month' => $startDate->format('F'),
                'count' => $diagnosePatientCounts,
            ];
        }

        return view('admin.report.diagnose_report', compact('diagnosePatientCountsByMonth', 'year', 'currentTime', 'currentDate', 'diagnose', 'specificDiagnosis','reference'));
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

        $admittedDiagnoses = Diagnose::select('diagnose')
            ->selectRaw('COUNT(*) as total_occurrences')
            ->whereYear('date', $currentYear)
            ->groupBy('diagnose')
            ->get();

        // Merge the two collections and sort them by total_occurrences in descending order
        $mergedDiagnoses = $admittedDiagnoses;

        $rankedDiagnosis = $mergedDiagnoses->groupBy('diagnose')
            ->map(function ($group) {
                $firstItem = $group->first();
                return [
                    'diagnose' => $firstItem['diagnose'],
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

        $diagnoseData = Diagnose::select('diagnose')
            ->distinct()
            ->pluck('diagnose')
            ->toArray();

        return view('admin.analytics.trend.diagnose', compact('profile', 'limitNotifications', 'count', 'diagnoseData', 'limitDiagnosis', 'countUniqueYears', 'rankedDiagnosis', 'currentTime', 'currentDate'));
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

        $admittedDiagnoses = Diagnose::select('diagnose')
            ->selectRaw('COUNT(*) as total_occurrences')
            ->whereYear('date', $currentYear)
            ->groupBy('diagnose')
            ->get();

        // Merge the two collections and sort them by total_occurrences in descending order
        $mergedDiagnoses = $admittedDiagnoses;

        $rankedDiagnosis = $mergedDiagnoses->groupBy('diagnose')
            ->map(function ($group) {
                $firstItem = $group->first();
                return [
                    'diagnose' => $firstItem['diagnose'],
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

        $diagnoseData = Diagnose::select('diagnose')
            ->distinct()
            ->pluck('diagnose')
            ->toArray();
        // The specific diagnosis you want to analyze
        $specificDiagnosis = $request->input('diagnose');

        // Retrieve admitted patient data for the specific diagnosis
        $patients = Diagnose::where('diagnose', $specificDiagnosis)
            ->get();

        $admittedYearData = DB::table('diagnoses')
            ->select(DB::raw('YEAR(date) as year'), DB::raw('COUNT(*) as count'))
            ->where('diagnose', $specificDiagnosis)
            ->where('patient_type', 'admitted')
            ->groupBy(DB::raw('YEAR(date)'))
            ->get();

        $outpatientYearData = DB::table('diagnoses')
            ->select(DB::raw('YEAR(date) as year'), DB::raw('COUNT(*) as count'))
            ->where('diagnose', $specificDiagnosis)
            ->where('patient_type', 'outpatient')
            ->groupBy(DB::raw('YEAR(date)'))
            ->get();

        // Query the database to get the monthly trend data for the specific diagnosis and year for both admitted_date and date
        $admittedMonthData = DB::table('diagnoses')
            ->select(DB::raw('MONTH(date) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('date', $currentYear)
            ->where('diagnose', $specificDiagnosis)
            ->where('patient_type', 'admitted')
            ->groupBy(DB::raw('MONTH(date)'))
            ->get();

        $outpatientMonthData = DB::table('diagnoses')
            ->select(DB::raw('MONTH(date) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('date', $currentYear)
            ->where('diagnose', $specificDiagnosis)
            ->where('patient_type', 'outpatient')
            ->groupBy(DB::raw('MONTH(date)'))
            ->get();

        // Create an array of years
        $years = [];
        $admittedYearCounts = [];
        $outpatientYearCounts = [];

        // Initialize counts for each year
        foreach (range(date('Y') - 10, date('Y')) as $year) {
            $years[] = $year;
            $admittedYearCounts[] = 0;
            $outpatientYearCounts[] = 0;
        }

        // Fill in the data for the available years
        foreach ($admittedYearData as $admitted) {
            $yearIndex = array_search($admitted->year, $years);
            if ($yearIndex !== false) {
                $admittedYearCounts[$yearIndex] = $admitted->count;
            }
        }

        foreach ($outpatientYearData as $outpatient) {
            $yearIndex = array_search($outpatient->year, $years);
            if ($yearIndex !== false) {
                $outpatientYearCounts[$yearIndex] = $outpatient->count;
            }
        }

        // Create an array with all months in the year
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
            'December',
        ];

        // Initialize the combined data with zero counts for all months
        $combinedData = [];

        foreach ($allMonths as $month) {
            $combinedData[$month] = [
                'admitted_count' => 0,
                'outpatient_count' => 0,
            ];
        }

        // Fill in the data for the available months
        foreach ($admittedMonthData as $admitted) {
            $month = date('F', mktime(0, 0, 0, $admitted->month, 1));
            $combinedData[$month]['admitted_count'] = $admitted->count;
        }

        foreach ($outpatientMonthData as $outpatient) {
            $month = date('F', mktime(0, 0, 0, $outpatient->month, 1));
            $combinedData[$month]['outpatient_count'] = $outpatient->count;
        }

        // Prepare the data for the chart
        $months = array_keys($combinedData);
        $admittedMonthCounts = array_column($combinedData, 'admitted_count');
        $outpatientMonthCounts = array_column($combinedData, 'outpatient_count');

        return view('admin.analytics.trend.diagnose_search', compact('profile', 'limitNotifications', 'count', 'diagnoseData', 'limitDiagnosis', 'years', 'admittedYearCounts', 'outpatientYearCounts', 'months', 'admittedMonthCounts', 'outpatientMonthCounts', 'specificDiagnosis', 'rankedDiagnosis', 'currentTime', 'currentDate'));
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
            ->get();

        $admittedYearData = DB::table('patients')
            ->select(DB::raw('YEAR(admitted_date) as year'), DB::raw('COUNT(*) as count'))
            ->where('diagnosis', $specificDiagnosis)
            ->groupBy(DB::raw('YEAR(admitted_date)'))
            ->get();

        $outpatientYearData = DB::table('patients')
            ->select(DB::raw('YEAR(date) as year'), DB::raw('COUNT(*) as count'))
            ->where('diagnosis', $specificDiagnosis)
            ->groupBy(DB::raw('YEAR(date)'))
            ->get();

        // Query the database to get the monthly trend data for the specific diagnosis and year for both admitted_date and date
        $admittedMonthData = DB::table('patients')
            ->select(DB::raw('MONTH(admitted_date) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('admitted_date', $currentYear)
            ->where('diagnosis', $specificDiagnosis)
            ->groupBy(DB::raw('MONTH(admitted_date)'))
            ->get();

        $outpatientMonthData = DB::table('patients')
            ->select(DB::raw('MONTH(date) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('date', $currentYear)
            ->where('diagnosis', $specificDiagnosis)
            ->groupBy(DB::raw('MONTH(date)'))
            ->get();

        // Create an array of years
        $years = [];
        $admittedYearCounts = [];
        $outpatientYearCounts = [];

        // Initialize counts for each year
        foreach (range(date('Y') - 10, date('Y')) as $year) {
            $years[] = $year;
            $admittedYearCounts[] = 0;
            $outpatientYearCounts[] = 0;
        }

        // Fill in the data for the available years
        foreach ($admittedYearData as $admitted) {
            $yearIndex = array_search($admitted->year, $years);
            if ($yearIndex !== false) {
                $admittedYearCounts[$yearIndex] = $admitted->count;
            }
        }

        foreach ($outpatientYearData as $outpatient) {
            $yearIndex = array_search($outpatient->year, $years);
            if ($yearIndex !== false) {
                $outpatientYearCounts[$yearIndex] = $outpatient->count;
            }
        }

        // Create an array with all months in the year
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
            'December',
        ];

        // Initialize the combined data with zero counts for all months
        $combinedData = [];

        foreach ($allMonths as $month) {
            $combinedData[$month] = [
                'admitted_count' => 0,
                'outpatient_count' => 0,
            ];
        }

        // Fill in the data for the available months
        foreach ($admittedMonthData as $admitted) {
            $month = date('F', mktime(0, 0, 0, $admitted->month, 1));
            $combinedData[$month]['admitted_count'] = $admitted->count;
        }

        foreach ($outpatientMonthData as $outpatient) {
            $month = date('F', mktime(0, 0, 0, $outpatient->month, 1));
            $combinedData[$month]['outpatient_count'] = $outpatient->count;
        }

        // Prepare the data for the chart
        $months = array_keys($combinedData);
        $admittedMonthCounts = array_column($combinedData, 'admitted_count');
        $outpatientMonthCounts = array_column($combinedData, 'outpatient_count');

        return view('admin.report.diagnose_trend_report', compact('year', 'currentTime', 'currentDate', 'specificDiagnosis', 'years', 'admittedYearCounts', 'outpatientYearCounts', 'months', 'admittedMonthCounts', 'outpatientMonthCounts'));
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

    // Logout
    public function adminLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}