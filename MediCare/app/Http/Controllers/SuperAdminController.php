<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Admin;
use App\Models\Nurse;
use App\Models\Doctor;
use App\Models\Report;
use App\Models\Patient;
use App\Models\Product;
use App\Models\Category;
use App\Models\Diagnose;
use App\Models\Purchase;
use App\Models\User_info;
use Illuminate\View\View;
use App\Models\Appointment;
use App\Models\Notification;
use App\Models\Request_Form;
use Illuminate\Http\Request;
use App\Models\Product_price;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SuperAdminController extends Controller
{

    // Super Admin
    public function dashboard()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', 'admin')
            ->orwhere('type', 'supply_officer')
            ->orderBy('date', 'desc')->get();
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

        $patientsByMonth = DB::table('patients')
            ->select(DB::raw('DATE_FORMAT(admitted_date, "%M") as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('admitted_date', $currentYear)
            ->groupBy('month')
            ->get();

        $patientsByYear = DB::table('patients')
            ->whereYear('admitted_date', $currentYear)
            ->get();

        $patientCount = $patientsByYear->count();

        $monthlyAppointments = Appointment::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count')
        )
            ->where('status', 'done')
            ->whereYear('appointment_date', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $appointmentByYear = DB::table('appointments')
            ->where('status', 'done')
            ->whereYear('appointment_date', $currentYear)
            ->get();

        $appointmentCount = $appointmentByYear->count();
        $appointmentLabels = $monthlyAppointments->pluck('month');
        $appointmentData = $monthlyAppointments->pluck('count');

        $rolesData = DB::table('users')
            ->select(DB::raw('UPPER(role) as label'), DB::raw('count(*) as data'))
            ->where('role', '!=', 'super_admin')
            ->groupBy(DB::raw('UPPER(role)'))
            ->get();

        // Initialize variables to store counts
        $rolesCount = 0;
        $roleCounts = [];

        // Calculate the total count of users and counts for each role
        foreach ($rolesData as $role) {
            $rolesCount += $role->data;
            $roleCounts[$role->label] = $role->data;
        }


        $usersLabels = $rolesData->pluck('label')->toArray();
        $usersLabels = array_map(function ($label) {
            return str_replace('_', ' ', $label);
        }, $usersLabels);
        
        $usersData = $rolesData->pluck('data')->toArray();

        /** Item demo**/
        // Retrieve all products with their prices
        $products = Product::all();

        // Initialize arrays to store categorized products
        $fastProducts = [];
        $slowProducts = [];
        $nonMovingProducts = [];

        // Create an array to store product prices
        $productPrices = [];

        // Categorize products based on request and sales and store them in arrays with ranking
        foreach ($products as $product) {
            $totalRequestQuantity = Request_Form::where('product_id', $product->id)->sum('quantity');
            $totalSalesQuantity = Purchase::where('product_id', $product->id)->sum('quantity');

            if ($totalRequestQuantity > 0) {
                $fastProducts[] = [
                    'name' => $product->p_name,
                    'price' => $product->price,
                ];
            } elseif ($totalSalesQuantity > 0) {
                $slowProducts[] = [
                    'name' => $product->p_name,
                    'price' => $product->price,
                ];
            } else {
                $nonMovingProducts[] = [
                    'name' => $product->p_name,
                    'price' => $product->price,
                ];
            }

            // Store product price in the productPrices array
            $productPrices[$product->p_name] = $product->price;
        }

        // Sort the products within the "Fast" and "Slow" categories
        usort($fastProducts, function ($a, $b) {
            return $b['price'] - $a['price'];
        });

        usort($slowProducts, function ($a, $b) {
            return $b['price'] - $a['price'];
        });

        // Create an array with category names and counts
        $categories = ['Fast', 'Slow', 'Non-Moving'];
        $counts = [
            'Fast' => count($fastProducts),
            'Slow' => count($slowProducts),
            'Non-Moving' => count($nonMovingProducts),
        ];


        return view('super_admin_dashboard', compact('profile', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'admittedPatientsByMonth', 'outpatientPatientsByMonth', 'patientCount', 'diagnosisCount', 'rankedDiagnosis', 'diagnosesWithOccurrences', 'rank1Diagnosis', 'appointmentLabels', 'appointmentData', 'appointmentCount', 'rolesCount', 'usersLabels', 'usersData', 'rolesCount', 'fastProducts', 'slowProducts', 'nonMovingProducts', 'counts', 'categories'));
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

        return view('superadmin.profile.profile', compact('profile', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
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

        return view('superadmin.profile.profile_password', compact('profile', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
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
            }

            $user->password = Hash::make($request->input('password'));

            $user->save();

            return redirect()->back()->with('success', 'Password updated successfull.');
        }
    }

    public function superAdminLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
    // End SUper Admin

    // Doctor 
    public function doctor()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $users = User::where('role', 'doctor')->get();
        $doctors = Doctor::all();

        return view('superadmin.account.doctor', compact('users', 'profile', 'doctors', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
    }

    public function createDoctor(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->password),
            'role' => 'doctor',
        ]);

        if ($request->hasFile('image')) {
            $imageName = $request->image->getClientOriginalName();

            $request->image->move(public_path('images'), $imageName);

            $latestUser = User::latest()->first();

            doctor::create([
                'account_id' => $latestUser->id,
                'age' => $request->input('age'),
                'gender' => $request->input('gender'),
                'specialties' => $request->input('specialties'),
                'employment_date' => $request->input('employment_date'),
                'qualification' => $request->input('qualification'),
                'years_of_experience' => $request->input('years_of_experience'),
                'phone' => $request->input('phone'),
                'birthdate' => $request->input('birthdate'),
                'street' => $request->input('street'),
                'brgy' => $request->input('brgy'),
                'city' => $request->input('city'),
                'province' => $request->input('province'),
                'facebook_link' => $request->input('facebook'),
                'twitter_link' => $request->input('twitter'),
                'instagram_link' => $request->input('instagram'),
                'linkedin_link' => $request->input('linkedin'),
                'image_name' => $imageName,
                'image_data' => 'images/' . $imageName,
            ]);

            return back()->with('success', 'User added sucessfully.');
        } else {
            $imageName = 'noprofile.jpeg';

            $latestUser = User::latest()->first();

            doctor::create([
                'account_id' => $latestUser->id,
                'age' => $request->input('age'),
                'gender' => $request->input('gender'),
                'specialties' => $request->input('specialties'),
                'employment_date' => $request->input('employment_date'),
                'qualification' => $request->input('qualification'),
                'years_of_experience' => $request->input('years_of_experience'),
                'phone' => $request->input('phone'),
                'birthdate' => $request->input('birthdate'),
                'street' => $request->input('street'),
                'brgy' => $request->input('brgy'),
                'city' => $request->input('city'),
                'province' => $request->input('province'),
                'facebook_link' => $request->input('facebook'),
                'twitter_link' => $request->input('twitter'),
                'instagram_link' => $request->input('instagram'),
                'linkedin_link' => $request->input('linkedin'),
                'image_name' => $imageName,
                'image_data' => 'images/' . $imageName,
            ]);

            return back()->with('success', 'User added sucessfully.');
        }
    }

    public function updateDoctorPassword(Request $request)
    {

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::findOrFail($request->input('user_id'));

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

    public function updateDoctorInfo(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        $user = User::findOrFail($request->input('user_id'));
        $info = Doctor::where('account_id', $request->user_id)->first();

        $userUpdatedData = [
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
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
            'facebook_link' => $request->input('facebook'),
            'twitter_link' => $request->input('twitter'),
            'instagram_link' => $request->input('instagram'),
            'linked_link' => $request->input('linkedin'),
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

        $userChange = $this->hasChanges($user, $userUpdatedData);
        $infoChange = $this->hasChanges($info, $infoUpdatedData);


        if ($imageChange) {

            $imageName = $request->image->getClientOriginalName();

            $request->image->move(public_path('images'), $imageName);

            // Check if any changes were made to the form data
            if ($userChange || $infoChange || $imageChange) {

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
                    $info->facebook_link = $request->input('facebook');
                    $info->twitter_link = $request->input('twitter');
                    $info->instagram_link = $request->input('instagram');
                    $info->linkedin_link = $request->input('linked_link');
                    $info->image_name = $imageName;
                    $info->image_data = 'images/' . $imageName;

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
                    $info->facebook_link = $request->input('facebook');
                    $info->twitter_link = $request->input('twitter');
                    $info->instagram_link = $request->input('instagram');
                    $info->linkedin_link = $request->input('linked_link');
                    $info->image_name = $imageName;
                    $info->image_data = 'images/' . $imageName;

                    $user->save();
                    $info->save();

                    return redirect()->back()->with('success', 'Profile updated successfully.');
                }

            } else {
                return redirect()->back()->with('info', 'No changes were made.');

            }

        } else {
            // Check if any changes were made to the form data
            if ($userChange || $infoChange) {

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
                    $info->facebook_link = $request->input('facebook');
                    $info->twitter_link = $request->input('twitter');
                    $info->instagram_link = $request->input('instagram');
                    $info->linkedin_link = $request->input('linked_link');

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
                    $info->facebook_link = $request->input('facebook');
                    $info->twitter_link = $request->input('twitter');
                    $info->instagram_link = $request->input('instagram');
                    $info->linkedin_link = $request->input('linked_link');

                    $user->save();
                    $info->save();

                    return redirect()->back()->with('success', 'Profile updated successfully.');
                }

            } else {
                return redirect()->back()->with('info', 'No changes were made.');

            }
        }
    }
    // End Doctor 

    // Nurse
    public function nurse()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $users = User::where('role', 'nurse')->get();
        $nurses = Nurse::all();

        return view('superadmin.account.nurse', compact('users', 'profile', 'nurses', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
    }

    public function createNurse(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->password),
            'role' => 'nurse',
        ]);

        $latestUser = User::latest()->first();

        nurse::create([
            'account_id' => $latestUser->id,
            'age' => $request->input('age'),
            'shift' => $request->input('shift'),
            'gender' => $request->input('gender'),
            'employment_date' => $request->input('employment_date'),
            'qualification' => $request->input('qualification'),
            'years_of_experience' => $request->input('years_of_experience'),
            'phone' => $request->input('phone'),
            'birthdate' => $request->input('birthdate'),
            'street' => $request->input('street'),
            'brgy' => $request->input('brgy'),
            'city' => $request->input('city'),
            'province' => $request->input('province'),
        ]);

        return back()->with('success', 'User added sucessfully.');
    }

    public function updateNurseInfo(Request $request)
    {

        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        $user = User::findOrFail($request->input('user_id'));
        $info = Nurse::where('account_id', $request->user_id)->first();

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
            'shift' => $request->input('shift'),
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
                $info->shift = $request->input('shift');
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
                $info->shift = $request->input('shift');
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

    public function updateNursePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::findOrFail($request->input('user_id'));

        if (!Hash::check($request->input('current_password'), $user->password)) {

            return redirect()->back()->with('success', 'Current password is incorrect.');

        } else {

            if (Hash::check($request->input('password'), $user->password)) {

                return redirect()->back()->with('success', "Password doesn't change.");
            } else {
                $user->password = Hash::make($request->input('password'));

                $user->save();

                return redirect()->back()->with('success', 'Password updated successfull.');
            }

        }

    }

    public function updateNurseStatus(Request $request)
    {

        $user = User::findOrFail($request->input('user_id'));

        if ($request->input('status') == 'activated') {

            $user->status = 'deactivated';
            $user->save();
            return redirect()->back()->with('info', 'User status updated to deactivated.');
        } else {

            $user->status = 'activated';
            $user->save();
            return redirect()->back()->with('info', 'User status updated to activated.');
        }
    }
    // End Nurse

    // User
    public function user()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $users = User::where('role', 'user')->get();
        $users_info = User_info::all();

        return view('superadmin.account.user', compact('users', 'profile', 'users_info', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        $latestUser = User::latest()->first();

        User_info::create([
            'account_id' => $latestUser->id,
            'age' => $request->input('age'),
            'gender' => $request->input('gender'),
            'occupation' => $request->input('occupation'),
            'phone' => $request->input('phone'),
            'birthdate' => $request->input('birthdate'),
            'street' => $request->input('street'),
            'brgy' => $request->input('brgy'),
            'city' => $request->input('city'),
            'province' => $request->input('province'),
        ]);

        return back()->with('success', 'User added sucessfully.');
    }

    public function updateUserInfo(Request $request)
    {

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($request->input('user_id'));
        $info = User_info::where('account_id', $request->user_id)->first();

        $userUpdatedData = [
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
        ];

        $infoUpdatedData = [
            'age' => $request->input('age'),
            'gender' => $request->input('gender'),
            'birthdate' => $request->input('birthdate'),
            'occupation' => $request->input('occupation'),
            'phone' => $request->input('phone'),
            'street' => $request->input('street'),
            'brgy' => $request->input('brgy'),
            'city' => $request->input('city'),
            'province' => $request->input('province'),
        ];

        $userChange = $this->hasChanges($user, $userUpdatedData);
        $infoChange = $this->hasChanges($info, $infoUpdatedData);

        if ($userChange || $infoChange) {

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
                $info->occupation = $request->input('occupation');
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
                $info->occupation = $request->input('occupation');
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

    public function updateUserPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::findOrFail($request->input('user_id'));

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
    // End User 

    // Admin
    public function admin()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $users = User::where('role', 'admin')->get();
        $admins = Admin::all();

        return view('superadmin.account.admin', compact('users', 'profile', 'admins', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
    }

    public function createAdmin(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'access_level' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        $latestUser = User::latest()->first();

        Admin::create([
            'account_id' => $latestUser->id,
            'access_level' => $request->input('access_level'),
        ]);

        return back()->with('success', 'User added sucessfully.');
    }

    public function updateAdminInfo(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'access_level' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        $user = User::findOrFail($request->input('user_id'));
        $info = admin::where('account_id', $request->user_id)->first();

        $userUpdatedData = [
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
        ];

        $infoUpdatedData = [
            'access_level' => $request->input('access_level')
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
                $info->access_level = $request->input('access_level');

                $user->save();
                $info->save();

                return redirect()->back()->with('success', 'Profile updated successfully.');

            } else {

                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $user->middle_name = $request->input('middle_name');
                $user->email = $request->input('email');
                $info->access_level = $request->input('access_level');

                $user->save();
                $info->save();

                return redirect()->back()->with('success', 'Profile updated successfully.');
            }

        } else {
            return redirect()->back()->with('info', 'No changes were made.');

        }
    }

    public function updateAdminPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::findOrFail($request->input('user_id'));

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

    // Supply Officer
    public function supplyOfficer()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $users = User::where('role', 'supply_officer')->get();

        return view('superadmin.account.supply_officer', compact('users', 'profile', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
    }

    public function createSupplyOfficer(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->password),
            'role' => 'supply_officer',
        ]);

        return back()->with('success', 'User added sucessfully.');
    }

    public function updateSupplyOfficerInfo(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        $user = User::findOrFail($request->input('user_id'));

        $userUpdatedData = [
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
        ];

        $userChange = $this->hasChanges($user, $userUpdatedData);

        if ($userChange) {

            if ($request->input('email') !== $user->email) {

                $request->validate([
                    'email' => 'required|string|email|max:255|unique:users,email,',
                ]);

                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $user->middle_name = $request->input('middle_name');
                $user->email = $request->input('email');
                $user->save();

                return redirect()->back()->with('success', 'Profile updated successfully.');

            } else {

                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $user->middle_name = $request->input('middle_name');
                $user->email = $request->input('email');

                $user->save();

                return redirect()->back()->with('success', 'Profile updated successfully.');
            }

        } else {
            return redirect()->back()->with('info', 'No changes were made.');

        }
    }

    public function updateSupplyOfficerPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::findOrFail($request->input('user_id'));

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

    // Staff
    public function Staff()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $users = User::where('role', 'staff')->get();

        return view('superadmin.account.staff', compact('users', 'profile', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
    }

    public function createStaff(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->password),
            'role' => 'staff',
        ]);

        return back()->with('success', 'User added sucessfully.');
    }

    public function updateStaffInfo(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        $user = User::findOrFail($request->input('user_id'));
        $info = admin::where('account_id', $request->user_id)->first();

        $userUpdatedData = [
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
        ];

        $userChange = $this->hasChanges($user, $userUpdatedData);

        // Check if any changes were made to the form data
        if ($userChange == true) {

            if ($request->input('email') !== $user->email) {

                $request->validate([
                    'email' => 'required|string|email|max:255|unique:users,email,',
                ]);

                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $user->middle_name = $request->input('middle_name');
                $user->email = $request->input('email');
                $user->save();

                return redirect()->back()->with('success', 'Profile updated successfully.');

            } else {

                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $user->middle_name = $request->input('middle_name');
                $user->email = $request->input('email');

                $user->save();

                return redirect()->back()->with('success', 'Profile updated successfully.');
            }

        } else {
            return redirect()->back()->with('info', 'No changes were made.');

        }
    }

    public function updateStaffPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::findOrFail($request->input('user_id'));

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


    // Cashier
    public function Cashier()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $users = User::where('role', 'cashier')->get();

        return view('superadmin.account.cashier', compact('users', 'profile', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
    }

    public function createCashier(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->password),
            'role' => 'cashier',
        ]);

        return back()->with('success', 'User added sucessfully.');
    }

    public function updateCashierInfo(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        $user = User::findOrFail($request->input('user_id'));
        $info = admin::where('account_id', $request->user_id)->first();

        $userUpdatedData = [
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
        ];

        $userChange = $this->hasChanges($user, $userUpdatedData);

        // Check if any changes were made to the form data
        if ($userChange == true) {

            if ($request->input('email') !== $user->email) {

                $request->validate([
                    'email' => 'required|string|email|max:255|unique:users,email,',
                ]);

                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $user->middle_name = $request->input('middle_name');
                $user->email = $request->input('email');
                $user->save();

                return redirect()->back()->with('success', 'Profile updated successfully.');

            } else {

                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $user->middle_name = $request->input('middle_name');
                $user->email = $request->input('email');

                $user->save();

                return redirect()->back()->with('success', 'Profile updated successfully.');
            }

        } else {
            return redirect()->back()->with('info', 'No changes were made.');

        }
    }

    public function updateCashierPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::findOrFail($request->input('user_id'));

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

    // Cashier
    public function Pharmacist()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $users = User::where('role', 'pharmacist')->get();

        return view('superadmin.account.pharmacist', compact('users', 'profile', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
    }

    public function createPharmacist(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->password),
            'role' => 'pharmacist',
        ]);

        return back()->with('success', 'User added sucessfully.');
    }

    public function updatePharmacistInfo(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        $user = User::findOrFail($request->input('user_id'));
        $info = admin::where('account_id', $request->user_id)->first();

        $userUpdatedData = [
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
        ];

        $userChange = $this->hasChanges($user, $userUpdatedData);

        // Check if any changes were made to the form data
        if ($userChange == true) {

            if ($request->input('email') !== $user->email) {

                $request->validate([
                    'email' => 'required|string|email|max:255|unique:users,email,',
                ]);

                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $user->middle_name = $request->input('middle_name');
                $user->email = $request->input('email');
                $user->save();

                return redirect()->back()->with('success', 'Profile updated successfully.');

            } else {

                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $user->middle_name = $request->input('middle_name');
                $user->email = $request->input('email');

                $user->save();

                return redirect()->back()->with('success', 'Profile updated successfully.');
            }

        } else {
            return redirect()->back()->with('info', 'No changes were made.');

        }
    }

    public function updatePharmacistPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::findOrFail($request->input('user_id'));

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

    // Gender Analytics
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
        $title = 'Patient Gender Analytics';

        return view('superadmin.analytics.gender.gender', compact('profile', 'limitNotifications', 'count', 'genderCountsByMonth', 'year', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'totalGenderCounts', 'type', 'title'));
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
        $title = 'Admitted Patient Gender Analytics';

        return view('superadmin.analytics.gender.gender', compact('profile', 'limitNotifications', 'count', 'genderCountsByMonth', 'year', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'totalGenderCounts', 'type', 'title'));
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
        $title = 'Outpatient Gender Analytics';

        return view('superadmin.analytics.gender.gender', compact('profile', 'limitNotifications', 'count', 'genderCountsByMonth', 'year', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'totalGenderCounts', 'type', 'title'));
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

            $title = 'Patient Gender Analytics';

        } else if ($type == 'admitted') {

            $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
                ->distinct()
                ->whereNotNull('admitted_date')
                ->pluck('year')
                ->toArray();

            $uniqueCombinedYears = array_unique($admittedYears);

            $title = 'Admitted Patient Gender Analytics';


        } else if ($type == 'outpatient') {

            $admittedYears = Patient::select(DB::raw('YEAR(date) as year'))
                ->distinct()
                ->whereNotNull('date')
                ->pluck('year')
                ->toArray();

            $uniqueCombinedYears = array_unique($admittedYears);

            $title = 'Outpatient Gender Analytics';

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


        return view('superadmin.analytics.gender.gender_search', compact('profile', 'limitNotifications', 'count', 'genderCountsByMonth', 'year', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'totalGenderCounts', 'type', 'title'));
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

        // Initialize an array to store gender counts for each month
        $genderCountsByMonth = [];
        $totalMaleCount = 0;
        $totalFemaleCount = 0;
        $femaleCounts = [];
        $maleCounts = [];

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

                $title = 'Patient Gendar Analytics Report';
                $reference = 'PGAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

            } else if ($type == 'admitted') {
                $patients = Patient::select('gender')
                    ->whereBetween('admitted_date', [$startDate, $endDate])
                    ->get();

                $title = 'Admitted Patient Gendar Analytics Report';
                $reference = 'APGAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

            } else if ($type == 'outpatient') {
                $patients = Patient::select('gender')
                    ->whereBetween('date', [$startDate, $endDate])
                    ->get();

                $title = 'Outpatient Gendar Analytics Report';
                $reference = 'OGAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;
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

            $maleCounts[] = $maleCount;
            $femaleCounts[] = $maleCount;
        }

        $totalGenderCounts = $totalMaleCount + $totalFemaleCount;

        return view('superadmin.report.gender_report', compact('genderCountsByMonth', 'year', 'currentTime', 'currentDate', 'totalGenderCounts', 'reference', 'title'));
    }

    public function genderReportSave(Request $request)
    {
        $reference = $request->input('reference');
        $time = $request->input('time');
        $date = $request->input('date');
        $title = $request->input('title');
        $type = $request->input('type');
        $readableDate = date('F j, Y', strtotime($date));
        $profile = auth()->user();

        $content =
            '              ' . $title . '
            ------------------------

            Report Reference Number: ' . $reference . '
            Report Date and Time: ' . $readableDate . ' ' . $time . '

            Report Status: Finalized';

        Report::create([
            'reference_number' => $reference,
            'report_type' => $title,
            'date' => $date,
            'time' => $time,
            'user_id' => $profile->id,
            'author_type' => $profile->role,
            'content' => $content,
        ]);

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
        $title = 'Patient Gender Analytics';

        return redirect()->route('superadmin.analytics.patient.gender', compact('profile', 'limitNotifications', 'count', 'genderCountsByMonth', 'year', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'totalGenderCounts', 'type', 'title'));

    }

    // Age Analytics
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
        $title = 'Patient Age Analytics';

        return view('superadmin.analytics.age.age', compact('profile', 'limitNotifications', 'labels', 'datasets', 'year', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'totalPatientCount', 'count', 'type', 'title'));
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
        $title = 'Admitted Patient Age Analytics';


        return view('superadmin.analytics.age.age', compact('profile', 'limitNotifications', 'labels', 'datasets', 'year', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'totalPatientCount', 'count', 'type', 'title'));
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
        $title = 'Patient Age Analytics';

        return view('superadmin.analytics.age.age', compact('profile', 'limitNotifications', 'labels', 'datasets', 'year', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'totalPatientCount', 'count', 'type', 'title'));
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
            $title = 'Patient Age Analytics';

        } else if ($type == 'admitted') {

            $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
                ->distinct()
                ->whereNotNull('admitted_date')
                ->pluck('year')
                ->toArray();

            $uniqueCombinedYears = array_unique($admittedYears);
            $title = 'Admitted Patient Age Analytics';


        } else if ($type == 'outpatient') {

            $admittedYears = Patient::select(DB::raw('YEAR(date) as year'))
                ->distinct()
                ->whereNotNull('date')
                ->pluck('year')
                ->toArray();

            $uniqueCombinedYears = array_unique($admittedYears);
            $title = 'Outpatient Age Analytics';

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

        return view('superadmin.analytics.age.age_search', compact('profile', 'limitNotifications', 'count', 'labels', 'datasets', 'year', 'totalPatientCount', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'type', 'title'));
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

            $title = 'Patient Age Analytics Report';
            $reference = 'PAAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

        } else if ($type == 'admitted') {

            $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
                ->distinct()
                ->whereNotNull('admitted_date')
                ->pluck('year')
                ->toArray();

            $uniqueCombinedYears = array_unique($admittedYears);

            $title = 'Admitted Patient Age Analytics Report';
            $reference = 'APAAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

        } else if ($type == 'outpatient') {

            $admittedYears = Patient::select(DB::raw('YEAR(date) as year'))
                ->distinct()
                ->whereNotNull('date')
                ->pluck('year')
                ->toArray();

            $uniqueCombinedYears = array_unique($admittedYears);

            $title = 'Outpatient Age Analytics Report';
            $reference = 'OAAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

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

        return view('superadmin.report.age_report', compact('labels', 'datasets', 'year', 'currentTime', 'currentDate', 'totalPatientCount', 'reference', 'title'));

    }

    public function ageReportSave(Request $request)
    {
        $reference = $request->input('reference');
        $time = $request->input('time');
        $date = $request->input('date');
        $title = $request->input('title');
        $type = $request->input('type');
        $readableDate = date('F j, Y', strtotime($date));
        $profile = auth()->user();

        $content =
            '             ' . $title . '
            ------------------------

            Report Reference Number: ' . $reference . '
            Report Date and Time: ' . $readableDate . ' ' . $time . '

            Report Status: Finalized';

        Report::create([
            'reference_number' => $reference,
            'report_type' => $title,
            'date' => $date,
            'time' => $time,
            'user_id' => $profile->id,
            'author_type' => $profile->role,
            'content' => $content,
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
        $title = 'Patient Age Analytics';

        return redirect()->route('superadmin.analytics.patient.age', compact('profile', 'limitNotifications', 'labels', 'datasets', 'year', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'totalPatientCount', 'count', 'type', 'title'));

    }

    // Admitted Analytics
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

        return view('superadmin.analytics.admitted.admitted', compact('profile', 'limitNotifications', 'count', 'admitPatientCountsByMonth', 'totalAdmittedPatients', 'year', 'admittedYears', 'currentTime', 'currentDate'));
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

        return view('superadmin.analytics.admitted.admitted_search', compact('profile', 'limitNotifications', 'count', 'admitPatientCountsByMonth', 'totalAdmittedPatients', 'year', 'admittedYears', 'currentTime', 'currentDate'));
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

        $title = 'Admitted Patient Analytics Report';

        $totalAdmittedPatients = array_sum(array_column($admitPatientCountsByMonth, 'count'));

        return view('superadmin.report.admit_report', compact('admitPatientCountsByMonth', 'year', 'currentTime', 'currentDate', 'totalAdmittedPatients', 'reference', 'title'));
    }

    public function admittedDemoReportSave(Request $request)
    {
        $reference = $request->input('reference');
        $time = $request->input('time');
        $date = $request->input('date');
        $title = $request->input('title');
        $type = $request->input('type');
        $readableDate = date('F j, Y', strtotime($date));
        $profile = auth()->user();

        $content =
            '             ' . $title . '
                ------------------------
    
                Report Reference Number: ' . $reference . '
                Report Date and Time: ' . $readableDate . ' ' . $time . '
    
                Report Status: Finalized';

        Report::create([
            'reference_number' => $reference,
            'report_type' => $title,
            'date' => $date,
            'time' => $time,
            'user_id' => $profile->id,
            'author_type' => $profile->role,
            'content' => $content,
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

        return redirect()->route('superadmin.analytics.admitted', compact('profile', 'limitNotifications', 'count', 'admitPatientCountsByMonth', 'totalAdmittedPatients', 'year', 'admittedYears', 'currentTime', 'currentDate'));
    }

    // Outpatient Analytics
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

        return view('superadmin.analytics.outpatient.outpatient', compact('profile', 'limitNotifications', 'count', 'admitPatientCountsByMonth', 'totalAdmittedPatients', 'year', 'admittedYears', 'currentTime', 'currentDate'));
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

        return view('superadmin.analytics.outpatient.outpatient_search', compact('profile', 'limitNotifications', 'count', 'admitPatientCountsByMonth', 'totalAdmittedPatients', 'year', 'admittedYears', 'currentTime', 'currentDate'));
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
        $reference = 'OAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

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

        $title = 'Outpatient Analytics Report';

        return view('superadmin.report.outpatient_report', compact('admitPatientCountsByMonth', 'year', 'currentTime', 'currentDate', 'totalAdmittedPatients', 'reference', 'title'));

    }

    public function outpatientDemoReportSave(Request $request)
    {
        $reference = $request->input('reference');
        $time = $request->input('time');
        $date = $request->input('date');
        $title = $request->input('title');
        $type = $request->input('type');
        $readableDate = date('F j, Y', strtotime($date));
        $profile = auth()->user();

        $content =
            '             ' . $title . '
            ------------------------

            Report Reference Number: ' . $reference . '
            Report Date and Time: ' . $readableDate . ' ' . $time . '

            Report Status: Finalized';

        Report::create([
            'reference_number' => $reference,
            'report_type' => $title,
            'date' => $date,
            'time' => $time,
            'user_id' => $profile->id,
            'author_type' => $profile->role,
            'content' => $content,
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

        return redirect()->route('superadmin.analytics.outpatient', compact('profile', 'limitNotifications', 'count', 'admitPatientCountsByMonth', 'totalAdmittedPatients', 'year', 'admittedYears', 'currentTime', 'currentDate'));
    }

    public function patientDiagnoseDemo()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $currentYear = Carbon::now()->year;

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
        $type = 'patient';
        $title = 'Patient Diagnose Analytics';

        return view('superadmin.analytics.diagnose.diagnose', compact('profile', 'limitNotifications', 'count', 'AdmittedDiagnoseData', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'type', 'title'));
    }

    public function admittedDiagnoseDemo()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $currentYear = Carbon::now()->year;

        $AdmittedDiagnoseData = Diagnose::select('diagnose')
            ->distinct()
            ->where('patient_type', 'admitted_patient')
            ->whereNotNull('diagnose')
            ->pluck('diagnose')
            ->toArray();

        $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
            ->distinct()
            ->whereNotNull('admitted_date')
            ->pluck('year')
            ->toArray();

        $uniqueCombinedYears = $admittedYears;

        $type = 'admitted';
        $title = 'Admitted Patient Diagnose Analytics';

        return view('superadmin.analytics.diagnose.diagnose', compact('profile', 'limitNotifications', 'count', 'AdmittedDiagnoseData', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'type', 'title'));
    }

    public function outpatientDiagnoseDemo()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $currentYear = Carbon::now()->year;

        $AdmittedDiagnoseData = Diagnose::select('diagnose')
            ->distinct()
            ->where('patient_type', 'outpatient')
            ->whereNotNull('diagnose')
            ->pluck('diagnose')
            ->toArray();

        $outpatientYears = Patient::select(DB::raw('YEAR(date) as year'))
            ->distinct()
            ->whereNotNull('date')
            ->pluck('year')
            ->toArray();

        $uniqueCombinedYears = $outpatientYears;

        $type = 'outpatient';
        $title = 'Outpatient Diagnose Analytics';

        return view('superadmin.analytics.diagnose.diagnose', compact('profile', 'limitNotifications', 'count', 'AdmittedDiagnoseData', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'type', 'title'));
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
        $specificDiagnosis = $request->input('diagnose');
        $selectedYear = $request->input('year');
        $type = $request->input('type');

        if ($type == 'patient') {

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

            $title = 'Patient Diagnose Analytics';

        } else if ($type == 'admitted') {

            $AdmittedDiagnoseData = Diagnose::select('diagnose')
                ->distinct()
                ->where('patient_type', 'admitted_patient')
                ->whereNotNull('diagnose')
                ->pluck('diagnose')
                ->toArray();

            $admittedYears = Patient::select(DB::raw('YEAR(admitted_date) as year'))
                ->distinct()
                ->whereNotNull('admitted_date')
                ->pluck('year')
                ->toArray();

            $uniqueCombinedYears = $admittedYears;

            $title = 'Admitted Patient Diagnose Analytics';

        } else if ($type == 'outpatient') {

            $AdmittedDiagnoseData = Diagnose::select('diagnose')
                ->distinct()
                ->where('patient_type', 'outpatient')
                ->whereNotNull('diagnose')
                ->pluck('diagnose')
                ->toArray();

            $outpatientYears = Patient::select(DB::raw('YEAR(date) as year'))
                ->distinct()
                ->whereNotNull('date')
                ->pluck('year')
                ->toArray();

            $uniqueCombinedYears = $outpatientYears;

            $title = 'Outpatient Diagnose Analytics';

        }

        // Initialize an array to store diagnose patient counts for each month
        $diagnosePatientCountsByMonth = [];

        // Loop through each month of the current year
        for ($month = 1; $month <= 12; $month++) {
            // Get the start and end dates of the current month
            $startDate = Carbon::createFromDate($selectedYear, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            if ($type == 'patient') {

                $diagnosePatientCounts = Diagnose::whereBetween('date', [$startDate, $endDate])
                    ->where('diagnose', $specificDiagnosis)
                    ->count();

            } else if ($type == 'admitted') {

                $diagnosePatientCounts = Diagnose::whereBetween('date', [$startDate, $endDate])
                    ->where('patient_type', 'admitted_patient')
                    ->where('diagnose', $specificDiagnosis)
                    ->count();

            } else if ($type == 'outpatient') {

                $diagnosePatientCounts = Diagnose::whereBetween('date', [$startDate, $endDate])
                    ->where('patient_type', 'outpatient')
                    ->where('diagnose', $specificDiagnosis)
                    ->count();

            }

            // Store the diagnose patient count for the current month in the array
            $diagnosePatientCountsByMonth[] = [
                'month' => $startDate->format('F'),
                'count' => $diagnosePatientCounts,
            ];
        }


        return view('superadmin.analytics.diagnose.diagnose_search', compact('profile', 'limitNotifications', 'count', 'diagnosePatientCountsByMonth', 'AdmittedDiagnoseData', 'uniqueCombinedYears', 'selectedYear', 'specificDiagnosis', 'currentTime', 'currentDate', 'type', 'title'));
    }

    public function diagnoseReport(Request $request)
    {
        $year = $request->input('year');
        $type = $request->input('type');
        $specificDiagnosis = $request->input('diagnose');
        $currentYear = Carbon::now()->year; // Get current year
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $currentTime = $currentDateTime->format('h:i A');
        $randomNumber = mt_rand(100, 999);

        // Initialize an array to store diagnose patient counts for each month
        $diagnosePatientCountsByMonth = [];

        if ($type == 'patient') {
            $title = 'Patient Diagnose Analytics Report';
            $reference = 'PDAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;
        } else if ($type == 'admitted') {
            $title = 'Admitted Patient Diagnose Analytics Report';
            $reference = 'APDAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;
        } else if ($type == 'outpatient') {
            $title = 'Outpatient Diagnose Analytics Report';
            $reference = 'ODAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;
        }

        // Loop through each month of the current year
        for ($month = 1; $month <= 12; $month++) {
            // Get the start and end dates of the current month
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            if ($type == 'patient') {

                $diagnosePatientCounts = Diagnose::whereBetween('date', [$startDate, $endDate])
                    ->where('diagnose', $specificDiagnosis)
                    ->count();

            } else if ($type == 'admitted') {

                $diagnosePatientCounts = Diagnose::whereBetween('date', [$startDate, $endDate])
                    ->where('patient_type', 'admitted_patient')
                    ->where('diagnose', $specificDiagnosis)
                    ->count();

            } else if ($type == 'outpatient') {

                $diagnosePatientCounts = Diagnose::whereBetween('date', [$startDate, $endDate])
                    ->where('patient_type', 'outpatient')
                    ->where('diagnose', $specificDiagnosis)
                    ->count();
            }

            // Store the diagnose patient count for the current month in the array
            $diagnosePatientCountsByMonth[] = [
                'month' => $startDate->format('F'),
                'count' => $diagnosePatientCounts,
            ];
        }

        return view('superadmin.report.diagnose_report', compact('diagnosePatientCountsByMonth', 'year', 'currentTime', 'currentDate', 'specificDiagnosis', 'reference', 'title'));
    }

    public function diagnoseReportSave(Request $request)
    {
        $reference = $request->input('reference');
        $time = $request->input('time');
        $date = $request->input('date');
        $title = $request->input('title');
        $type = $request->input('type');
        $readableDate = date('F j, Y', strtotime($date));
        $profile = auth()->user();

        $content =
            '             ' . $title . '
            ------------------------

            Report Reference Number: ' . $reference . '
            Report Date and Time: ' . $readableDate . ' ' . $time . '

            Report Status: Finalized';

        Report::create([
            'reference_number' => $reference,
            'report_type' => $title,
            'date' => $date,
            'time' => $time,
            'user_id' => $profile->id,
            'author_type' => $profile->role,
            'content' => $content,
        ]);

        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $currentYear = Carbon::now()->year;

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
        $type = 'patient';
        $title = 'Patient Diagnose Analytics';

        return redirect()->route('superadmin.analytics.patient.diagnose', compact('profile', 'limitNotifications', 'count', 'AdmittedDiagnoseData', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'type', 'title'));
    }

    public function appointmentDemo()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

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

        $appointmentCountsByMonth = [];

        // Loop through each month of the current year
        for ($month = 1; $month <= 12; $month++) {
            // Get the start and end dates of the current month
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            // Retrieve admitted patient data for the current month
            $appoinmentCounts = Appointment::where('status', 'done')->whereBetween('appointment_date', [$startDate, $endDate])
                ->count();

            // Store the admit patient count for the current month in the array
            $appointmentCountsByMonth[] = [
                'month' => $startDate->format('F'),
                'count' => $appoinmentCounts,
            ];
        }

        $totalAppointment = array_sum(array_column($appointmentCountsByMonth, 'count'));

        return view('superadmin.patient-demo.appointment', compact('profile', 'limitNotifications', 'count', 'year', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'appointmentCountsByMonth', 'totalAppointment'));
    }

    public function appointmentDemoSearch(Request $request)
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

        $appointmentCountsByMonth = [];

        // Loop through each month of the current year
        for ($month = 1; $month <= 12; $month++) {
            // Get the start and end dates of the current month
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            // Retrieve admitted patient data for the current month
            $appoinmentCounts = Appointment::where('status', 'done')->whereBetween('appointment_date', [$startDate, $endDate])
                ->count();

            // Store the admit patient count for the current month in the array
            $appointmentCountsByMonth[] = [
                'month' => $startDate->format('F'),
                'count' => $appoinmentCounts,
            ];
        }

        $totalAppointment = array_sum(array_column($appointmentCountsByMonth, 'count'));


        return view('superadmin.patient-demo.appointment_search', compact('profile', 'limitNotifications', 'count', 'year', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'appointmentCountsByMonth', 'totalAppointment'));
    }

    public function appointmentReport(Request $request)
    {

        $profile = auth()->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentYear = Carbon::now()->year; // Get current year
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
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

        $appointmentCountsByMonth = [];

        // Loop through each month of the current year
        for ($month = 1; $month <= 12; $month++) {
            // Get the start and end dates of the current month
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            // Retrieve admitted patient data for the current month
            $appoinmentCounts = Appointment::where('status', 'done')->whereBetween('appointment_date', [$startDate, $endDate])
                ->count();

            // Store the admit patient count for the current month in the array
            $appointmentCountsByMonth[] = [
                'month' => $startDate->format('F'),
                'count' => $appoinmentCounts,
            ];
        }

        $totalAppointment = array_sum(array_column($appointmentCountsByMonth, 'count'));


        return view('superadmin.report.appointment_report', compact('profile', 'limitNotifications', 'count', 'year', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'appointmentCountsByMonth', 'totalAppointment'));
    }

    // Diagnose Trend Analytics
    public function patientDiagnoseTrend()
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

        $diagnoseData = Diagnose::select('diagnose')
            ->distinct()
            ->pluck('diagnose')
            ->toArray();

        $type = 'patient';
        $title = 'Patient Diagnose Trend Analytics';

        return view('superadmin.analytics.diagnose_trend.diagnose', compact('profile', 'limitNotifications', 'count', 'diagnoseData', 'limitDiagnosis', 'rankedDiagnosis', 'currentTime', 'currentDate', 'type', 'title'));
    }

    public function admittedDiagnoseTrend()
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
            ->where('patient_type', 'admitted_patient')
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

        $diagnoseData = Diagnose::select('diagnose')
            ->distinct()
            ->where('patient_type', 'admitted_patient')
            ->pluck('diagnose')
            ->toArray();

        $type = 'admitted';
        $title = 'Admitted Patient Diagnose Trend Analytics';

        return view('superadmin.analytics.diagnose_trend.diagnose', compact('profile', 'limitNotifications', 'count', 'diagnoseData', 'limitDiagnosis', 'rankedDiagnosis', 'currentTime', 'currentDate', 'type', 'title'));
    }

    public function outpatientDiagnoseTrend()
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
            ->where('patient_type', 'outpatient')
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

        $diagnoseData = Diagnose::select('diagnose')
            ->where('patient_type', 'outpatient')
            ->distinct()
            ->pluck('diagnose')
            ->toArray();

        $type = 'outpatient';

        $title = 'Outpatient Diagnose Trend Analytics';


        return view('superadmin.analytics.diagnose_trend.diagnose', compact('profile', 'limitNotifications', 'count', 'diagnoseData', 'limitDiagnosis', 'rankedDiagnosis', 'currentTime', 'currentDate', 'type', 'title'));
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
        $type = $request->input('type');
        $specificDiagnosis = $request->input('diagnose');

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

        if ($type == 'patient') {

            $diagnoseData = Diagnose::select('diagnose')
                ->distinct()
                ->pluck('diagnose')
                ->toArray();

            $patientYearData = DB::table('diagnoses')
                ->select(DB::raw('YEAR(date) as year'), DB::raw('COUNT(*) as count'))
                ->where('diagnose', $specificDiagnosis)
                ->groupBy(DB::raw('YEAR(date)'))
                ->get();

            // Query the database to get the monthly trend data for the specific diagnosis and year for both admitted_date and date
            $patientMonthData = DB::table('diagnoses')
                ->select(DB::raw('MONTH(date) as month'), DB::raw('COUNT(*) as count'))
                ->whereYear('date', $currentYear)
                ->where('diagnose', $specificDiagnosis)
                ->groupBy(DB::raw('MONTH(date)'))
                ->get();

            $title = 'Patient Diagnose Trend Analytics';

        } else if ($type == 'admitted') {

            $diagnoseData = Diagnose::select('diagnose')
                ->distinct()
                ->where('patient_type', 'admitted_patient')
                ->pluck('diagnose')
                ->toArray();

            $patientYearData = DB::table('diagnoses')
                ->select(DB::raw('YEAR(date) as year'), DB::raw('COUNT(*) as count'))
                ->where('patient_type', 'admitted_patient')
                ->where('diagnose', $specificDiagnosis)
                ->groupBy(DB::raw('YEAR(date)'))
                ->get();

            // Query the database to get the monthly trend data for the specific diagnosis and year for both admitted_date and date
            $patientMonthData = DB::table('diagnoses')
                ->select(DB::raw('MONTH(date) as month'), DB::raw('COUNT(*) as count'))
                ->where('patient_type', 'admitted_patient')
                ->whereYear('date', $currentYear)
                ->where('diagnose', $specificDiagnosis)
                ->groupBy(DB::raw('MONTH(date)'))
                ->get();

            $title = 'Admitted Patient Diagnose Trend Analytics';


        } else if ($type == 'outpatient') {

            $diagnoseData = Diagnose::select('diagnose')
                ->distinct()
                ->where('patient_type', 'outpatient')
                ->pluck('diagnose')
                ->toArray();

            $patientYearData = DB::table('diagnoses')
                ->select(DB::raw('YEAR(date) as year'), DB::raw('COUNT(*) as count'))
                ->where('patient_type', 'outpatient')
                ->where('diagnose', $specificDiagnosis)
                ->groupBy(DB::raw('YEAR(date)'))
                ->get();

            // Query the database to get the monthly trend data for the specific diagnosis and year for both admitted_date and date
            $patientMonthData = DB::table('diagnoses')
                ->select(DB::raw('MONTH(date) as month'), DB::raw('COUNT(*) as count'))
                ->where('patient_type', 'outpatient')
                ->whereYear('date', $currentYear)
                ->where('diagnose', $specificDiagnosis)
                ->groupBy(DB::raw('MONTH(date)'))
                ->get();

            $title = 'Outpatient Diagnose Trend Analytics';

        }

        // Create an array of years
        $years = [];
        $patientYearCounts = [];

        // Initialize counts for each year
        foreach (range(date('Y') - 1, date('Y')) as $year) {
            $years[] = $year;
            $patientYearCounts[] = 0;
        }

        // Fill in the data for the available years
        foreach ($patientYearData as $admitted) {
            $yearIndex = array_search($admitted->year, $years);
            if ($yearIndex !== false) {
                $patientYearCounts[$yearIndex] = $admitted->count;
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
        foreach ($patientMonthData as $admitted) {
            $month = date('F', mktime(0, 0, 0, $admitted->month, 1));
            $combinedData[$month]['admitted_count'] = $admitted->count;
        }

        // Prepare the data for the chart
        $months = array_keys($combinedData);
        $patientMonthCounts = array_column($combinedData, 'admitted_count');

        return view('superadmin.analytics.diagnose_trend.diagnose_search', compact('profile', 'limitNotifications', 'count', 'diagnoseData', 'limitDiagnosis', 'years', 'patientYearCounts', 'months', 'patientMonthCounts', 'specificDiagnosis', 'rankedDiagnosis', 'currentTime', 'currentDate', 'type', 'title'));
    }

    public function diagnoseTrendReport(Request $request)
    {
        $year = $request->input('year');
        $currentYear = Carbon::now()->year; // Get current year
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $currentTime = $currentDateTime->format('h:i A');
        $randomNumber = mt_rand(100, 999);

        // The specific diagnosis you want to analyze
        $specificDiagnosis = $request->input('diagnose');
        $type = $request->input('type');


        if ($type == 'patient') {

            $diagnoseData = Diagnose::select('diagnose')
                ->distinct()
                ->pluck('diagnose')
                ->toArray();

            $patientYearData = DB::table('diagnoses')
                ->select(DB::raw('YEAR(date) as year'), DB::raw('COUNT(*) as count'))
                ->where('diagnose', $specificDiagnosis)
                ->groupBy(DB::raw('YEAR(date)'))
                ->get();

            // Query the database to get the monthly trend data for the specific diagnosis and year for both admitted_date and date
            $patientMonthData = DB::table('diagnoses')
                ->select(DB::raw('MONTH(date) as month'), DB::raw('COUNT(*) as count'))
                ->whereYear('date', $currentYear)
                ->where('diagnose', $specificDiagnosis)
                ->groupBy(DB::raw('MONTH(date)'))
                ->get();

            $title = 'Patient Diagnose Trend Report';
            $reference = 'PDTAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

        } else if ($type == 'admitted') {

            $diagnoseData = Diagnose::select('diagnose')
                ->distinct()
                ->where('patient_type', 'admitted_patient')
                ->pluck('diagnose')
                ->toArray();

            $patientYearData = DB::table('diagnoses')
                ->select(DB::raw('YEAR(date) as year'), DB::raw('COUNT(*) as count'))
                ->where('patient_type', 'admitted_patient')
                ->where('diagnose', $specificDiagnosis)
                ->groupBy(DB::raw('YEAR(date)'))
                ->get();

            // Query the database to get the monthly trend data for the specific diagnosis and year for both admitted_date and date
            $patientMonthData = DB::table('diagnoses')
                ->select(DB::raw('MONTH(date) as month'), DB::raw('COUNT(*) as count'))
                ->where('patient_type', 'admitted_patient')
                ->whereYear('date', $currentYear)
                ->where('diagnose', $specificDiagnosis)
                ->groupBy(DB::raw('MONTH(date)'))
                ->get();

            $title = 'Admitted Patient Diagnose Trend Report';
            $reference = 'APDTAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

        } else if ($type == 'outpatient') {

            $diagnoseData = Diagnose::select('diagnose')
                ->distinct()
                ->where('patient_type', 'outpatient')
                ->pluck('diagnose')
                ->toArray();

            $patientYearData = DB::table('diagnoses')
                ->select(DB::raw('YEAR(date) as year'), DB::raw('COUNT(*) as count'))
                ->where('patient_type', 'outpatient')
                ->where('diagnose', $specificDiagnosis)
                ->groupBy(DB::raw('YEAR(date)'))
                ->get();

            // Query the database to get the monthly trend data for the specific diagnosis and year for both admitted_date and date
            $patientMonthData = DB::table('diagnoses')
                ->select(DB::raw('MONTH(date) as month'), DB::raw('COUNT(*) as count'))
                ->where('patient_type', 'outpatient')
                ->whereYear('date', $currentYear)
                ->where('diagnose', $specificDiagnosis)
                ->groupBy(DB::raw('MONTH(date)'))
                ->get();

            $title = 'Outpatient Diagnose Trend Report';
            $reference = 'ODTAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;
        }

        // Create an array of years
        $years = [];
        $patientYearCounts = [];

        // Initialize counts for each year
        foreach (range(date('Y') - 1, date('Y')) as $year) {
            $years[] = $year;
            $patientYearCounts[] = 0;
        }

        // Fill in the data for the available years
        foreach ($patientYearData as $admitted) {
            $yearIndex = array_search($admitted->year, $years);
            if ($yearIndex !== false) {
                $patientYearCounts[$yearIndex] = $admitted->count;
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
        foreach ($patientMonthData as $admitted) {
            $month = date('F', mktime(0, 0, 0, $admitted->month, 1));
            $combinedData[$month]['admitted_count'] = $admitted->count;
        }

        // Prepare the data for the chart
        $months = array_keys($combinedData);
        $patientMonthCounts = array_column($combinedData, 'admitted_count');

        return view('superadmin.report.diagnose_trend_report', compact('year', 'currentTime', 'currentDate', 'specificDiagnosis', 'years', 'patientYearCounts', 'months', 'patientMonthCounts', 'type', 'reference', 'title'));
    }

    public function diagnoseTrendReportSave(Request $request)
    {
        $reference = $request->input('reference');
        $time = $request->input('time');
        $date = $request->input('date');
        $title = $request->input('title');
        $type = $request->input('type');
        $readableDate = date('F j, Y', strtotime($date));
        $profile = auth()->user();

        $content =
            '             ' . $title . '
           ------------------------

           Report Reference Number: ' . $reference . '
           Report Date and Time: ' . $readableDate . ' ' . $time . '

           Report Status: Finalized';

        Report::create([
            'reference_number' => $reference,
            'report_type' => $title,
            'date' => $date,
            'time' => $time,
            'user_id' => $profile->id,
            'author_type' => $profile->role,
            'content' => $content,
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

        $diagnoseData = Diagnose::select('diagnose')
            ->distinct()
            ->pluck('diagnose')
            ->toArray();

        $type = 'patient';
        $title = 'Patient Diagnose Trend Analytics';

        return redirect()->route('superadmin.analytics.patient.diagnose_trend', compact('profile', 'limitNotifications', 'count', 'diagnoseData', 'limitDiagnosis', 'rankedDiagnosis', 'currentTime', 'currentDate', 'type', 'title'));
    }

    public function notification()
    {

        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)
            ->orWhere('type', 'admin')
            ->orWhere('type', 'supply_officer')
            ->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        return view('superadmin.notification.notification', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate'));

    }

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

        $profile = Auth::user();
        $notifications = Notification::where('type', 'admin')->orderBy('date', 'desc')->paginate(10);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $doctors = Doctor::all();
        $appointments = Appointment::whereNot('status', 'unvailable')->orderBy('appointment_date', 'desc')->paginate(5);
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        return view('superadmin.appointment.appointment', compact('profile', 'appointments', 'limitNotifications', 'amTime', 'pmTime', 'count', 'currentTime', 'currentDate', 'doctors'));

    }

    public function reportHistory()
    {
        $profile = Auth::user();
        $notifications = Notification::where('type', 'admin')->orderBy('date', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $users = User::all();
        $reports = Report::orderBy('date', 'desc')->get();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        return view('superadmin.reports.report', compact('profile', 'reports', 'users', 'limitNotifications', 'count', 'currentTime', 'currentDate'));

    }

    public function notificationRead(Request $request)
    {

        $notification = Notification::findOrFail($request->input('id'));

        if ($notification->is_read == 0) {
            $notification->is_read = 1;
            $notification->save();

            return redirect()->route('superadmin.notification');
        } else {
            return redirect()->route('superadmin.notification');
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
        $notifications = Notification::where('type', $profile->role)
            ->orWhere('type', 'admin')
            ->orWhere('type', 'supply_officer')
            ->get(); // Split the string into an array using a delimiter (e.g., comma)

        if ($notifications->isEmpty()) {
            return redirect()->back()->with('info', 'No notification to delete.');

        } else {

            foreach ($notifications as $notification) {
                $notification->delete();
            }

            return redirect()->back()->with('success', 'User deleted successfully');
        }
    }

    public function expirationproduct()
    {
        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        $categories = Product::paginate(5);

        $currentDate = Carbon::now();

        // Calculate the date three months from the current date
        $threeMonthsFromNow = $currentDate->copy()->addMonths(3);

        // Retrieve products with expiration dates within the date range
        $products = Product::where('expiration', '>', $currentDate)
            ->where('expiration', '<=', $threeMonthsFromNow)
            ->get();

        // Display the list of products
        return view('superadmin.inventory.expiring_soon', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'products'));

    }

    public function viewExpiryReport()
    {
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        $categories = Product::paginate(5);

        $currentDate = Carbon::now();

        // Calculate the date three month from the current date
        $threeMonthFromNow = $currentDate->copy()->addMonths(3);

        // Retrieve products with expiration dates within the date range
        $products = Product::where('expiration', '>', $currentDate)
            ->where('expiration', '<=', $threeMonthFromNow)
            ->orderBy('expiration', 'asc')
            ->get();

        // Display the list of products

        $data = [
            'categories' => $categories,
            'products' => $products,
            'currentTime' => $currentTime,
            'currentDate' => $currentDate,

        ];

        $pdf = app('dompdf.wrapper')->loadView('superadmin.report.expiry_report', $data);

        return $pdf->stream('expiry item report.pdf');
        //return view('supply_officer.report.expiry_report', compact('currentTime', 'currentDate', 'products'));
    }

    public function downloadExpiryReport()
    {
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        $categories = Product::paginate(5);

        $currentDate = Carbon::now();

        // Calculate the date three month from the current date
        $threeMonthFromNow = $currentDate->copy()->addMonths(3);

        // Retrieve products with expiration dates within the date range
        $products = Product::where('expiration', '>', $currentDate)
            ->where('expiration', '<=', $threeMonthFromNow)
            ->orderBy('expiration', 'asc')
            ->get();

        // Display the list of products

        $data = [
            'categories' => $categories,
            'products' => $products,
            'currentTime' => $currentTime,
            'currentDate' => $currentDate,

        ];

        $pdf = app('dompdf.wrapper')->loadView('superadmin.report.expiry_report', $data);

        return $pdf->download('expiry item report.pdf');
        //return view('supply_officer.report.expiry_report', compact('currentTime', 'currentDate', 'products'));
    }

    public function inventoryDemo()
    {
        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        $products = Product::all();


        return view('superadmin.inventory_demo.inventorydemo', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'products'));
    }

    public function inventorydemoSearch(Request $request)
    {
        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        $selectedOption = $request->input('select');
        $chartData = [];

        if ($selectedOption === 'Category') {
            $data = Product::join('categories', 'products.category_id', '=', 'categories.id')
                ->select('categories.category_name', DB::raw('COUNT(*) as count'))
                ->groupBy('categories.category_name')
                ->orderByDesc('count')
                ->get();
            $chartTitle = 'Category Data';

            // Transform data into chart format
            foreach ($data as $item) {
                $chartData[] = [
                    'label' => $item->category_name,
                    'count' => $item->count,
                ];
            }

        } elseif ($selectedOption === 'Brand') {

            $data = Product::select('brand', DB::raw('COUNT(*) as count'))
                ->groupBy('brand')
                ->orderByDesc('count')
                ->get();

            $chartTitle = 'Brand Data';

            // Transform data into chart format
            foreach ($data as $item) {
                $chartData[] = [
                    'label' => $item->brand,
                    'count' => $item->count,
                ];
            }
        } else {

            // Handle other options or show an error message if needed
            return redirect()->back()->with('error', 'Invalid selection.');
        }

        return view(
            'superadmin.inventory_demo.inventorydemo_search',
            compact(
                'profile',
                'notifications',
                'limitNotifications',
                'count',
                'currentTime',
                'currentDate',
                'chartData',
                'chartTitle',
                'selectedOption'
            )
        );
    }

    public function inventoryReport(Request $request)
    {
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        $selectedOption = $request->input('select');
        $chartData = [];

        if ($selectedOption === 'Category') {
            $data = Product::join('categories', 'products.category_id', '=', 'categories.id')
                ->select('categories.category_name', DB::raw('COUNT(*) as count'))
                ->groupBy('categories.category_name')
                ->orderByDesc('count')
                ->get();
            $chartTitle = 'category';

            // Transform data into chart format
            foreach ($data as $item) {
                $chartData[] = [
                    'label' => $item->category_name,
                    'count' => $item->count,
                ];
            }

        } elseif ($selectedOption === 'Brand') {

            $data = Product::select('brand', DB::raw('COUNT(*) as count'))
                ->groupBy('brand')
                ->orderByDesc('count')
                ->get();

            $chartTitle = 'brand';

            // Transform data into chart format
            foreach ($data as $item) {
                $chartData[] = [
                    'label' => $item->brand,
                    'count' => $item->count,
                ];
            }
        } else {

            // Handle other options or show an error message if needed
            return redirect()->back()->with('error', 'Invalid selection.');
        }

        return view(
            'superadmin.report.inventory_report',
            compact(
                'currentTime',
                'currentDate',
                'chartData',
                'chartTitle',
                'selectedOption'
            )
        );
    }

    public function requestDemo()
    {
        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        $requests = Request_Form::all();


        return view('superadmin.inventory_demo.requestdemo', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'requests'));
    }

    public function requestDemoSearch(Request $request)
    {
        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        $fromDate = $request->input('start');
        $formattedFromDate = date("M j, Y", strtotime($fromDate));
        $toDate = $request->input('end');
        $formattedToDate = date("M j, Y", strtotime($toDate));
        $selectedOption = $request->input('select');
        $range = $formattedFromDate . " - " . $formattedToDate;

        // Query your database to get the most requested products or departments based on the selected date range and category
        if ($selectedOption === 'Item') {
            // Get the most requested products
            $result = Request_Form::join('products', 'requests.product_id', '=', 'products.id')
                ->whereBetween('requests.date', [$fromDate, $toDate])
                ->groupBy('requests.product_id', 'products.p_name') // Group by product name
                ->selectRaw('products.p_name as label, COUNT(*) as data')
                ->orderByDesc('data')
                ->get();

        } elseif ($selectedOption === 'Department') {
            // Get the most requested departments
            $result = Request_Form::whereBetween('date', [$fromDate, $toDate])
                ->groupBy('department')
                ->selectRaw('department as label, COUNT(*) as data')
                ->orderByDesc('data')
                ->get();
        } else {
            // Invalid selection, handle accordingly
            return redirect()->back()->with('info', 'Invalid selection.');
        }



        $chartData = [

            'labels' => $result->pluck('label'),
            'data' => $result->pluck('data'),
        ];


        // Return the view with the chart data
        return view(
            'superadmin.inventory_demo.requestdemo_search',
            compact(
                'profile',
                'notifications',
                'limitNotifications',
                'count',
                'currentTime',
                'currentDate',
                'chartData',
                'range',
                'selectedOption',
                'fromDate',
                'toDate'
            )
        );
    }

    //Request
    public function requestReport(Request $request)
    {
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        $fromDate = $request->input('start');
        $formattedFromDate = date("M j, Y", strtotime($fromDate));
        $toDate = $request->input('end');
        $formattedToDate = date("M j, Y", strtotime($toDate));
        $selectedOption = $request->input('select');
        $range = $formattedFromDate . " - " . $formattedToDate;

        // Query your database to get the most requested products or departments based on the selected date range and category


        if ($selectedOption === 'Item') {
            // Get the most requested products with their creation dates
            $result = Request_Form::join('products', 'requests.product_id', '=', 'products.id')
                ->whereBetween('requests.created_at', [$fromDate, $toDate])
                ->groupBy('requests.product_id', 'products.p_name', 'requests.created_at') // Group by product ID and creation date
                ->selectRaw('products.p_name as label, requests.created_at as request_date, COUNT(*) as data')
                ->orderByDesc('data')
                ->get();
            $reportType = 'item'; // Set the report type to 'item'

        } elseif ($selectedOption === 'Department') {
            // Get the most requested departments with their creation dates
            $result = Request_Form::whereBetween('created_at', [$fromDate, $toDate])
                ->groupBy('department', 'created_at') // Group by department and creation date
                ->selectRaw('department as label, created_at as request_date, COUNT(*) as data')
                ->orderByDesc('data')
                ->get();
            $reportType = 'department'; // Set the report type to 'department'

        } else {
            // Invalid selection, handle accordingly
            return redirect()->back()->with('info', 'Invalid selection.');
        }

        // Modify the label generation in the controller
        $labels = $result->map(function ($item) {
            return date("M j, Y", strtotime($item->request_date)) . ' - ' . $item->label;
        });

        $chartData = [
            'labels' => $labels,
            'data' => $result->pluck('data'),
        ];

        // Return the view with the chart data
        return view('superadmin.report.request_report', compact('currentTime', 'currentDate', 'chartData', 'range', 'result', 'reportType'));

    }

    //Salaes Demo
    public function saleDemo()
    {
        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        $requests = Purchase::all();


        return view('superadmin.inventory_demo.saledemo', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'requests'));
    }

    public function saleDemoSearch(Request $request)
    {

        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        $fromDate = $request->input('start');
        $formattedFromDate = date("M j, Y", strtotime($fromDate));
        $toDate = $request->input('end');
        $formattedToDate = date("M j, Y", strtotime($toDate));
        $selectedOption = $request->input('select');
        $range = $formattedFromDate . " - " . $formattedToDate;

        // Create an array to store the date range
        $dateRange = [];
        $currentDate = $fromDate;

        while ($currentDate <= $toDate) {
            $dateRange[] = $currentDate;
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }

        // Fetch data from the purchases table for each product on each day
        $products = Purchase::select('product_id')
            ->distinct()
            ->get();

        $salesData = [];

        foreach ($products as $product) {
            $productId = $product->product_id;
            $productInfo = Product::find($productId); // Fetch product info from the products table

            if ($productInfo) {
                $productName = $productInfo->p_name;
                $salesData[$productName] = [];

                foreach ($dateRange as $date) {
                    $quantity = Purchase::where('product_id', $productId)
                        ->whereDate('created_at', $date)
                        ->sum('quantity');

                    $salesData[$productName][] = $quantity;
                }
            }
        }


        return view('superadmin.inventory_demo.saledemo_search', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'range', 'dateRange', 'salesData', 'fromDate', 'toDate', 'selectedOption'));
    }

    public function saleReport(Request $request)
    {
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        $fromDate = $request->input('start');
        $formattedFromDate = date("M, j Y", strtotime($fromDate));
        $toDate = $request->input('end');
        $formattedToDate = date("M, j Y", strtotime($toDate));
        $selectedOption = $request->input('select');
        $range = $formattedFromDate . " - " . $formattedToDate;

        // Create an array to store the date range
        $dateRange = [];
        $currentDate = $fromDate;

        while ($currentDate <= $toDate) {
            $dateRange[] = $currentDate;
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }

        // Fetch data from the purchases table for each product on each day
        $products = Purchase::select('product_id')
            ->distinct()
            ->get();

        $salesData = [];

        foreach ($products as $product) {
            $productId = $product->product_id;
            $productInfo = Product::find($productId); // Fetch product info from the products table

            if ($productInfo) {
                $productName = $productInfo->p_name;
                $salesData[$productName] = [];

                foreach ($dateRange as $date) {
                    $quantity = Purchase::where('product_id', $productId)
                        ->whereDate('created_at', $date)
                        ->sum('quantity');

                    $salesData[$productName][] = $quantity;
                }
            }
        }



        return view(
            'superadmin.report.sale_report',
            compact(
                'currentTime',
                'currentDate',
                'range',
                'dateRange',
                'salesData',
                'products'
            )
        );
    }

    //Medicine Demo
    public function medicineDemo()
    {
        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        // Fetch product prices from the product_price table
        $productPrices = Product_price::all();

        // Define price range thresholds for categorization
        $mostThreshold = 100; // Adjust as needed
        $mediumThreshold = 50; // Adjust as needed

        // Initialize arrays to store product names in each category
        $mostValuedProducts = [];
        $mediumValuedProducts = [];
        $lowValuedProducts = [];

        // Categorize product prices and collect product names
        foreach ($productPrices as $productPrice) {
            $product = $productPrice->product; // Access the related product

            if ($product) {
                $productInfo = [
                    'name' => $product->p_name,
                    // Use the product's name
                    'price' => $productPrice->price,
                    // Use the product's price
                ];

                if ($productPrice->price >= $mostThreshold) {
                    $mostValuedProducts[] = $productInfo;
                } elseif ($productPrice->price >= $mediumThreshold) {
                    $mediumValuedProducts[] = $productInfo;
                } else {
                    $lowValuedProducts[] = $productInfo;
                }
            }
        }


        // Calculate the percentages based on counts
        $totalCount = count($productPrices);
        $mostValuedCount = count($mostValuedProducts);
        $mediumValuedCount = count($mediumValuedProducts);
        $lowValuedCount = count($lowValuedProducts);

        $mostValuedPercentage = ($totalCount > 0) ? round(($mostValuedCount / $totalCount) * 100) : 0;
        $mediumValuedPercentage = ($totalCount > 0) ? round(($mediumValuedCount / $totalCount) * 100) : 0;
        $lowValuedPercentage = ($totalCount > 0) ? round(($lowValuedCount / $totalCount) * 100) : 0;


        return view(
            'superadmin.inventory_demo.medicinedemo',
            compact(
                'profile',
                'notifications',
                'limitNotifications',
                'count',
                'currentTime',
                'currentDate',
                'productPrices',
                'mostValuedPercentage',
                'mediumValuedPercentage',
                'lowValuedPercentage',
                'mostThreshold',
                'mediumThreshold',
                'mostValuedProducts',
                'mediumValuedProducts',
                'lowValuedProducts'
            )
        );
    }

    public function medicineReport(Request $request)
    {
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        $selectedOption = $request->input('select');
        $chartData = [];

        // Fetch product prices from the product_price table
        $productPrices = Product_price::all();

        // Define price range thresholds for categorization
        $mostThreshold = 100; // Adjust as needed
        $mediumThreshold = 50; // Adjust as needed

        // Initialize arrays to store product names in each category
        $mostValuedProducts = [];
        $mediumValuedProducts = [];
        $lowValuedProducts = [];

        // Categorize product prices and collect product names
        foreach ($productPrices as $productPrice) {
            $product = $productPrice->product; // Access the related product

            if ($product) {
                if ($productPrice->price >= $mostThreshold) {
                    $mostValuedProducts[] = $product->p_name; // Use the product's name
                } elseif ($productPrice->price >= $mediumThreshold) {
                    $mediumValuedProducts[] = $product->p_name; // Use the product's name
                } else {
                    $lowValuedProducts[] = $product->p_name; // Use the product's name
                }
            }
        }

        // Calculate the percentages based on counts
        $totalCount = count($productPrices);
        $mostValuedCount = count($mostValuedProducts);
        $mediumValuedCount = count($mediumValuedProducts);
        $lowValuedCount = count($lowValuedProducts);

        $mostValuedPercentage = ($totalCount > 0) ? round(($mostValuedCount / $totalCount) * 100) : 0;
        $mediumValuedPercentage = ($totalCount > 0) ? round(($mediumValuedCount / $totalCount) * 100) : 0;
        $lowValuedPercentage = ($totalCount > 0) ? round(($lowValuedCount / $totalCount) * 100) : 0;


        return view(
            'superadmin.report.medicines_report',
            compact(
                'chartData',
                'currentTime',
                'currentDate',
                'productPrices',
                'mostValuedPercentage',
                'mediumValuedPercentage',
                'lowValuedPercentage',
                'mostThreshold',
                'mediumThreshold',
                'mostValuedProducts',
                'mediumValuedProducts',
                'lowValuedProducts'
            )
        );
    }
    public function productDemo()
    {
        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        // Retrieve all products with their prices
        $products = Product::all();

        // Initialize arrays to store categorized products
        $fastProducts = [];
        $slowProducts = [];
        $nonMovingProducts = [];

        // Create an array to store product prices
        $productPrices = [];

        // Categorize products based on request and sales and store them in arrays with ranking
        foreach ($products as $product) {
            $totalRequestQuantity = Request_Form::where('product_id', $product->id)->sum('quantity');
            $totalSalesQuantity = Purchase::where('product_id', $product->id)->sum('quantity');

            if ($totalRequestQuantity > 0) {
                $fastProducts[] = [
                    'name' => $product->p_name,
                    'price' => $product->price,
                ];
            } elseif ($totalSalesQuantity > 0) {
                $slowProducts[] = [
                    'name' => $product->p_name,
                    'price' => $product->price,
                ];
            } else {
                $nonMovingProducts[] = [
                    'name' => $product->p_name,
                    'price' => $product->price,
                ];
            }

            // Store product price in the productPrices array
            $productPrices[$product->p_name] = $product->price;
        }

        // Sort the products within the "Fast" and "Slow" categories
        usort($fastProducts, function ($a, $b) {
            return $b['price'] - $a['price'];
        });

        usort($slowProducts, function ($a, $b) {
            return $b['price'] - $a['price'];
        });

        // Create an array with category names and counts
        $categories = ['Fast', 'Slow', 'Non-Moving'];
        $counts = [
            'Fast' => count($fastProducts),
            'Slow' => count($slowProducts),
            'Non-Moving' => count($nonMovingProducts),
        ];

        return view(
            'superadmin.inventory_demo.productdemo',
            compact(
                'profile',
                'notifications',
                'limitNotifications',
                'counts',
                'currentTime',
                'currentDate',
                'categories',
                'count',
                'fastProducts',
                'slowProducts',
                'nonMovingProducts',
                'productPrices' // Pass the product prices to the view
            )
        );
    }

    public function productsReport(Request $request)
    {
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        $selectedOption = $request->input('select');
        $chartData = [];


        // Retrieve all products
        $products = Product::all();

        // Initialize arrays to store categorized products
        $fastProducts = [];
        $slowProducts = [];
        $nonMovingProducts = [];

        // Categorize products based on request and sales and store them in arrays
        foreach ($products as $product) {
            $totalRequestQuantity = Request_Form::where('product_id', $product->id)->sum('quantity');
            $totalSalesQuantity = Purchase::where('product_id', $product->id)->sum('quantity');

            if ($totalRequestQuantity > 0) {
                $fastProducts[] = $product->p_name;
            } elseif ($totalSalesQuantity > 0) {
                $slowProducts[] = $product->p_name;
            } else {
                $nonMovingProducts[] = $product->p_name;
            }
        }

        // Create an array with category names and counts
        $categories = ['Fast', 'Slow', 'Non-Moving'];
        $counts = [
            'Fast' => count($fastProducts),
            'Slow' => count($slowProducts),
            'Non-Moving' => count($nonMovingProducts),
        ];

        return view(
            'superadmin.report.products_report',
            compact(
                'counts',
                'currentTime',
                'currentDate',
                'categories',
                'fastProducts',
                'slowProducts',
                'nonMovingProducts'

            )
        );
    }
    public function deleteUser(Request $request)
    {
        // Find the user by ID
        $user = User::where('id', $request->input('id'))->first();

        if ($user->role === 'doctor') {

            $info = Doctor::where('account_id', $user->id);
            $user->delete();
            $info->delete();

            return redirect()->back()->with('success', 'User deleted successfully');

        } elseif ($user->role === 'nurse') {

            $info = Nurse::where('account_id', $user->id);
            $user->delete();
            $info->delete();

            return redirect()->back()->with('success', 'User deleted successfully');
        } elseif ($user->role === 'user') {

            $info = User_info::where('account_id', $user->id);
            $user->delete();
            $info->delete();

            return redirect()->back()->with('success', 'User deleted successfully');
        } elseif ($user->role === 'admin') {

            $user->delete();

            return redirect()->back()->with('success', 'User deleted successfully');

        } elseif ($user->role === 'supply_officer') {

            $user->delete();

            return redirect()->back()->with('success', 'User deleted successfully');
        } elseif ($user->role === 'staff') {

            $user->delete();

            return redirect()->back()->with('success', 'User deleted successfully');
        } elseif ($user->role === 'cashier') {

            $user->delete();

            return redirect()->back()->with('success', 'User deleted successfully');
        } elseif ($user->role === 'pharmacist') {

            $user->delete();

            return redirect()->back()->with('success', 'User deleted successfully');
        }

        return redirect()->back()->with('Error', 'User deleted unsucessful.');

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