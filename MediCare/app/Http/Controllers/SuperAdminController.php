<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Admin;
use App\Models\Nurse;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\User_info;
use Illuminate\View\View;
use App\Models\Appointment;
use App\Models\Notification;
use App\Models\Request_Form;
use App\Models\Product_price;
use Illuminate\Http\Request;
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
        $patients = Patient::where(function ($query) use ($currentYear) {
            $query->whereYear('admitted_date', $currentYear)
                ->orWhereYear('date', $currentYear);
        })->get();
        $patientCount = $patients->count();

        // $rankedDiagnosis = Patient::select('diagnosis', DB::raw('MONTH(admitted_date) as month'))
        //     ->selectRaw('COUNT(*) as total_occurrences')
        //     ->whereYear('admitted_date', $currentYear)
        //     ->groupBy(DB::raw('LOWER(diagnosis)'), 'month')
        //     ->groupBy('diagnosis', 'month')
        //     ->orderByDesc('total_occurrences')
        //     ->get();

        // $diagnosesWithOccurrences = Patient::select('diagnosis')
        //     ->selectRaw('COUNT(*) as total_occurrences')
        //     ->whereYear('admitted_date', $currentYear)
        //     ->groupBy('diagnosis')
        //     ->orderBy('total_occurrences', 'desc') // Order by occurrences in descending order
        //     ->take(3) // Limit the result to the top 5 diagnoses
        //     ->get();

        // $diagnosisCount = $diagnosesWithOccurrences->count();

        // $rank1Diagnosis = $rankedDiagnosis->firstWhere('month', Carbon::now()->month);

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
            ->select('role', DB::raw('COUNT(*) as count'))
            ->groupBy('role')
            ->get();

        $rolesCount = $rolesData->count();

        $usersLabels = [];
        $usersData = [];

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



        return view('super_admin_dashboard', compact('profile', 'limitNotifications', 'count','currentTime', 'currentDate','patientCount', 'appointmentLabels', 'appointmentData', 'appointmentCount', 'rolesCount', 'usersLabels', 'usersData', 'rolesCount', 'productPrices', 'mostValuedProducts', 'mediumValuedProducts', 'lowValuedProducts','totalCount', 'mostValuedPercentage', 'mediumValuedPercentage', 'lowValuedPercentage', ));
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
            'middle_name' => 'required|string|max:255',
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


        return view('superadmin.patient.patient', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count', 'currentTime', 'currentDate'));

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

        return view('superadmin.patient.patient_search', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
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

        return view('superadmin.patient.patient_outpatient', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
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

        return view('superadmin.patient.patient_outpatient_search', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
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

        return view('superadmin.patient.patient_admitted', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
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

        return view('superadmin.patient.patient_admitted_search', compact('patients', 'profile', 'doctors', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
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
        $maleCounts = [];
        $femaleCounts = [];

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

            // Add counts to arrays for additional statistics
            $maleCounts[] = $maleCount;
            $femaleCounts[] = $femaleCount;
        }


        $totalGenderCounts = $totalMaleCount + $totalFemaleCount;

        return view('superadmin.patient-demo.gender', compact('profile', 'limitNotifications', 'count', 'genderCountsByMonth', 'year', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'totalGenderCounts'));
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


        return view('superadmin.patient-demo.gender_search', compact('profile', 'limitNotifications', 'count', 'genderCountsByMonth', 'year', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'totalGenderCounts'));
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
        $maleCounts = [];
        $femaleCounts = [];

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

            // Add counts to arrays for additional statistics
            $maleCounts[] = $maleCount;
            $femaleCounts[] = $femaleCount;
        }

        $totalGenderCounts = $totalMaleCount + $totalFemaleCount;

        return view('superadmin.report.gender_report', compact('genderCountsByMonth', 'year', 'currentTime', 'currentDate', 'totalGenderCounts'));

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

        return view('superadmin.patient-demo.age', compact('profile', 'limitNotifications', 'count', 'labels', 'datasets', 'year', 'uniqueCombinedYears', 'currentTime', 'currentDate', 'totalPatientCount'));
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

        return view('superadmin.patient-demo.age_search', compact('profile', 'limitNotifications', 'count', 'labels', 'datasets', 'year', 'totalPatientCount', 'uniqueCombinedYears', 'currentTime', 'currentDate'));
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

        return view('superadmin.report.age_report', compact('labels', 'datasets', 'year', 'currentTime', 'currentDate', 'totalPatientCount', 'ageGroupsByMonth'));

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

        return view('superadmin.patient-demo.admitted', compact('profile', 'limitNotifications', 'count', 'admitPatientCountsByMonth', 'totalAdmittedPatients', 'year', 'admittedYears', 'currentTime', 'currentDate'));
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

        return view('superadmin.patient-demo.admitted_search', compact('profile', 'limitNotifications', 'count', 'admitPatientCountsByMonth', 'totalAdmittedPatients', 'year', 'admittedYears', 'currentTime', 'currentDate'));
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

        return view('superadmin.report.admit_report', compact('admitPatientCountsByMonth', 'year', 'currentTime', 'currentDate', 'totalAdmittedPatients'));
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

        return view('superadmin.patient-demo.outpatient', compact('profile', 'limitNotifications', 'count', 'admitPatientCountsByMonth', 'totalAdmittedPatients', 'year', 'admittedYears', 'currentTime', 'currentDate'));
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

        return view('superadmin.patient-demo.outpatient_search', compact('profile', 'limitNotifications', 'count', 'admitPatientCountsByMonth', 'totalAdmittedPatients', 'year', 'admittedYears', 'currentTime', 'currentDate'));
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

        return view('superadmin.report.outpatient_report', compact('admitPatientCountsByMonth', 'year', 'currentTime', 'currentDate', 'totalAdmittedPatients'));

    }


    public function patientReport(Request $request)
    {
        $profile = auth()->user();
        $patient = Patient::where('id', $request->input('patient_id'))->first();
        $currentYear = Carbon::now()->year; // Get current year
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $doctor = User::where('id', $patient->physician)->first();

        return view('superadmin.report.patient_report', compact('patient', 'currentTime', 'currentDate', 'doctor', 'profile'));
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

        return view('superadmin.patient-demo.diagnose', compact('profile', 'limitNotifications', 'count', 'AdmittedDiagnoseData', 'uniqueCombinedYears', 'currentTime', 'currentDate'));
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

        return view('superadmin.patient-demo.diagnose_search', compact('profile', 'limitNotifications', 'count', 'diagnosePatientCountsByMonth', 'AdmittedDiagnoseData', 'uniqueCombinedYears', 'selectedYear', 'specificDiagnosis', 'currentTime', 'currentDate', 'specificDiagnosis'));
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

        return view('superadmin.report.diagnose_report', compact('diagnosePatientCountsByMonth', 'year', 'currentTime', 'currentDate', 'specificDiagnosis'));
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

        return view('superadmin.trend.diagnose_trend', compact('profile', 'limitNotifications', 'count', 'diagnoseData', 'limitDiagnosis', 'countUniqueYears', 'rankedDiagnosis', 'currentTime', 'currentDate'));
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


        return view('superadmin.trend.diagnose_trend_search', compact('profile', 'limitNotifications', 'count', 'diagnoseData', 'limitDiagnosis', 'years', 'admittedYearCounts', 'outpatientYearCounts', 'specificDiagnosis', 'months', 'admittedMonthCounts', 'outpatientMonthCounts', 'rankedDiagnosis', 'currentTime', 'currentDate'));
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

        return view('superadmin.report.diagnose_trend_report', compact('years', 'admittedYearCounts', 'outpatientYearCounts', 'months', 'admittedMonthCounts', 'outpatientMonthCounts', 'currentTime', 'currentDate', 'specificDiagnosis', 'year'));
    }

    public function notification()
    {

        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)
        ->orWhere('type', 'admin')
        ->orWhere('type', 'supply_officer')
        ->orderBy('date', 'desc')->paginate(10);
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
        $appointments = Appointment::whereNot('status', 'unvailable')->orderBy('appointment_date', 'desc')->paginate(10);
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        return view('superadmin.appointment.appointment', compact('profile', 'appointments', 'limitNotifications', 'amTime', 'pmTime', 'count', 'currentTime', 'currentDate', 'doctors'));

    }

    public function appointmentSearch(Request $request)
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
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $doctors = Doctor::all();
        $searchTerm = $request->input('search');
        $appointments = Appointment::where(function ($query) use ($searchTerm) {
            $query->orWhere('first_name', 'LIKE', '%' . $searchTerm . '%');
            $query->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%');
        })->paginate(10);


        return view('superadmin.appointment.appointment_search', compact('profile', 'appointments', 'limitNotifications', 'amTime', 'pmTime', 'count', 'currentTime', 'currentDate', 'doctors'));

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
        $notifications = Notification::where('type',$profile->role)
        ->orWhere('type','admin')
        ->orWhere('type','supply_officer')
        ->get(); // Split the string into an array using a delimiter (e.g., comma)

        if($notifications->isEmpty()){
            return redirect()->back()->with('info', 'No notification to delete.');
            
        } else {

            foreach ($notifications as $notification) {
                $notification->delete();
            }

            return redirect()->back()->with('success', 'User deleted successfully');
        }
    }

    public function productList()
    {
        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $products = Product::with('category')->get();
        $categories = Category::with('products')->get();

        return view('superadmin.inventory.product', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'products', 'categories'));

    }

    public function productReport(Request $request)
    {
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $today = Carbon::now();
        $oneWeekFromToday = $today->addDays(7); // Calculate the date one week from today
        
        $products = Product::orderBy('expiration', 'asc')->get();
        $categories = Category::all();

        return view('superadmin.report.product_report', compact('currentTime', 'currentDate', 'products', 'categories'));
    }

    public function productStore(Request $request)
    {
        // Validation rules
        $request->validate([
            'p_name' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'stock' => 'required|integer',
            'brand' => 'required|string|max:255',
            'expiration' => 'required|date',
            'description' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        // Find an existing product by name
        $existingProduct = Product::where('p_name', $request->input('p_name'))->first();

        if ($existingProduct) {
            // If the product exists, update its stock by adding the new stock quantity
            $existingProduct->stock += $request->input('stock');
            $existingProduct->save();

            return redirect()->back()->with('success', 'Product Stock Updated.');
        } else {
            // If the product doesn't exist, create a new one

            $products = new Product();
            $products->p_name = $request->input('p_name');
            $products->category_id = $request->input('category_id');
            $products->stock = $request->input('stock');
            $products->brand = $request->input('brand');
            $products->expiration = $request->input('expiration');
            $products->description = $request->input('description');
            $products->status = $request->input('status');

            $products->save();

            return redirect()->back()->with('success', 'Data Saved');
        }

    }

    public function productdetail($id)
    {
        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $product = Product::find($id);

        return view('superadmin.inventory.product_details', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'product'));
    }

    public function productupdate(Request $request, $id)
    {
        $products = Product::find($id);
        $categories = Category::all();

        if (!$products) {
            return redirect()->route('superadmin.product', compact('products', 'categories'))->with('error', 'Product not found');
        }

        $products->update($request->all());

        return redirect()->route('superadmin.product')->with('success', 'Product updated successfully');
    }

    public function productdelete($id)
    {
        $products = Product::find($id);
        $products->requests()->delete();
        $products->delete();

        return redirect()->back()->with('success', 'Product Deleted.');

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

        // Calculate the date one month from the current date
        $threeFromNow = $currentDate->copy()->addMonths(3);

        // Retrieve products with expiration dates within the date range
        $products = Product::where('expiration', '>', $currentDate)
            ->where('expiration', '<=', $threeFromNow)
            ->get();

        // Display the list of products
        return view('superadmin.inventory.expiring_soon', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'products'));

    }

    public function expiryReport()
    {
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        $categories = Product::paginate(5);

        $currentDate = Carbon::now();

        // Calculate the date one month from the current date
        $threeMonthFromNow = $currentDate->copy()->addMonths(3);

        // Retrieve products with expiration dates within the date range
        $products = Product::where('expiration', '>', $currentDate)
            ->where('expiration', '<=', $threeMonthFromNow)
            ->orderBy('expiration', 'asc')
            ->get();

        // Display the list of products
        return view('superadmin.report.expiry_report', compact('currentTime', 'currentDate', 'products'));
    }
    //Category
    public function categoryList()
    {
        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $products = Product::with('category')->get();
        $categories = Category::with('products')->paginate(5);

        return view('superadmin.inventory.category', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'products', 'categories'));

    }

    public function categoryStore(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|unique:categories',
            'category_code' => 'required|integer|unique:categories'
        ]);

        $categories = Category::find('category_id');

        $categories = new Category;
        $categories->category_name = $request->input('category_name');
        $categories->category_code = $request->input('category_code');

        $categories->save();

        return redirect()->back()->with('success', 'Data Saved');

    }

    public function categoryupdate(Request $request, $id)
    {

        // Find the category by ID
        $category = Category::find($id);

        if (!$category) {
            return redirect()->route('category')->with('error', 'Category not found.');
        }

        // Update the category with new data
        $category->update($request->all());

        return redirect()->route('superadmin.category')->with('success', 'Category updated successfully.');
    }

    public function categorydelete($id)
    {
        $categories = Category::find($id);
        $categories->products()->delete();
        $categories->delete();

        return redirect('/superadmin/category')->with('success', 'Category Deleted');
    }

    public function requestlist()
    {

        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $requests = Request_Form::paginate(5);

        return view('superadmin.inventory.request', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'requests'));

    }

    public function requestListReport()
    {
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $requests = Request_Form::all();
        $products = Product::all();

        return view('superadmin.report.request_list_report', compact( 'currentTime', 'currentDate', 'requests','products'));

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
        $formattedFromDate = date("F j, Y", strtotime($fromDate));
        $toDate = $request->input('end');
        $formattedToDate = date("F j, Y", strtotime($toDate));
        $selectedOption = $request->input('select');
        $range = $formattedFromDate . " - " . $formattedToDate;

        // Query your database to get the most requested products or departments based on the selected date range and category
        if ($selectedOption === 'Product') {
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


        // Prepare the data for the chart

        $chartData = [

            'labels' => $result->pluck('label'),
            'data' => $result->pluck('data'),
        ];

        // Return the view with the chart data
        return view('superadmin.inventory_demo.requestdemo_search', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'chartData', 'range','selectedOption','fromDate','toDate'));
    }

    //Request
    public function requestReport(Request $request)
    {
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        $fromDate = $request->input('start');
        $formattedFromDate = date("F j, Y", strtotime($fromDate));
        $toDate = $request->input('end');
        $formattedToDate = date("F j, Y", strtotime($toDate));
        $selectedOption = $request->input('select');
        $range = $formattedFromDate . " - " . $formattedToDate;

        // Query your database to get the most requested products or departments based on the selected date range and category
        if ($selectedOption === 'Product') {
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
        return view('superadmin.report.request_report', compact('currentTime', 'currentDate', 'chartData', 'range', 'result'));
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
         $formattedFromDate = date("F j, Y", strtotime($fromDate));
         $toDate = $request->input('end');
         $formattedToDate = date("F j, Y", strtotime($toDate));
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
         $formattedFromDate = date("F j, Y", strtotime($fromDate));
         $toDate = $request->input('end');
         $formattedToDate = date("F j, Y", strtotime($toDate));
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
 
 
         return view('superadmin.report.sale_report', compact(
            'currentTime',
            'currentDate',
            'range', 
            'dateRange', 
            'salesData', 
            'products'));
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

        $products = Product::all();

        // Define price range thresholds for categorization
        $mostThreshold = 100; // Adjust as needed
        $mediumThreshold = 50; // Adjust as needed

        // Initialize arrays to store product names in each category
        $mostValuedProducts = [];
        $mediumValuedProducts = [];
        $lowValuedProducts = [];

        // Categorize product prices and collect product names
        foreach ($productPrices as $productPrice) {
            if ($productPrice->price >= $mostThreshold) {
                $mostValuedProducts[] = $productPrice->product->p_name; // Use product relationship to get the name
            } elseif ($productPrice->price >= $mediumThreshold) {
                $mediumValuedProducts[] = $productPrice->product->p_name; // Use product relationship to get the name
            } else {
                $lowValuedProducts[] = $productPrice->product->p_name; // Use product relationship to get the name
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

        return view('superadmin.inventory_demo.medicinedemo', compact(
            'profile',
            'notifications',
            'limitNotifications',
            'count',
            'currentTime',
            'currentDate',
            'productPrices',
            'products',
            'mostValuedPercentage',
            'mediumValuedPercentage',
            'lowValuedPercentage',
            'mostThreshold',
            'mediumThreshold',
            'mostValuedProducts',
            'mediumValuedProducts',
            'lowValuedProducts'
        ));
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

        $products = Product::all();

        // Define price range thresholds for categorization
        $mostThreshold = 100; // Adjust as needed
        $mediumThreshold = 50; // Adjust as needed

        // Initialize arrays to store product names in each category
        $mostValuedProducts = [];
        $mediumValuedProducts = [];
        $lowValuedProducts = [];

        // Categorize product prices and collect product names
        foreach ($productPrices as $productPrice) {
            if ($productPrice->price >= $mostThreshold) {
                $mostValuedProducts[] = $productPrice->product->p_name; // Use product relationship to get the name
            } elseif ($productPrice->price >= $mediumThreshold) {
                $mediumValuedProducts[] = $productPrice->product->p_name; // Use product relationship to get the name
            } else {
                $lowValuedProducts[] = $productPrice->product->p_name; // Use product relationship to get the name
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

        return view('superadmin.report.medicines_report', compact(
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
        ));
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

        return view('superadmin.inventory_demo.productdemo', compact(
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
            'nonMovingProducts'
        ));
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

        return view('superadmin.report.products_report', compact(
            'counts',
            'currentTime',
            'currentDate',
            'categories',
            'fastProducts',
            'slowProducts',
            'nonMovingProducts'

        ));
    }

    public function deleteUser(Request $request)
    {
         // Find the user by ID
         $user = User::where('id', $request->input('id'))->first();

        if($user->role === 'doctor'){

            $info = Doctor::where('account_id',$user->id);
            $user->delete();
            $info->delete();

            return redirect()->back()->with('success', 'User deleted successfully');

        } elseif ($user->role === 'nurse'){

            $info = Nurse::where('account_id',$user->id);
            $user->delete();
            $info->delete();

            return redirect()->back()->with('success', 'User deleted successfully');
        } elseif ($user->role === 'user'){

            $info = User_info::where('account_id',$user->id);
            $user->delete();
            $info->delete();

            return redirect()->back()->with('success', 'User deleted successfully');
        } elseif ($user->role === 'admin'){

            $user->delete();

            return redirect()->back()->with('success', 'User deleted successfully');

        } elseif ($user->role === 'supply_officer'){
            
            $user->delete();

            return redirect()->back()->with('success', 'User deleted successfully');
        } elseif ($user->role === 'staff'){
            
            $user->delete();

            return redirect()->back()->with('success', 'User deleted successfully');
        } elseif ($user->role === 'cashier'){
            
            $user->delete();

            return redirect()->back()->with('success', 'User deleted successfully');
        } elseif ($user->role === 'pharmacist'){
            
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