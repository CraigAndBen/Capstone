<?php

namespace App\Http\Controllers;

use App\Models\Product_price;
use App\Models\Purchase;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Category;
use App\Models\Report;
use App\Models\Expiries;
use Illuminate\View\View;
use App\Models\Notification;
use App\Models\Request_Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class SupplyOfficerController extends Controller
{
    public function dashboard()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', 'supply_officer')->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentYear = Carbon::now()->year;
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        return view('supply_officer_dashboard', compact('profile', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
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

        return view('supply_officer.profile.profile', compact('profile', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
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

        return view('supply_officer.profile.profile_password', compact('profile', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
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

    public function notification()
    {

        $profile = Auth::user();
        $notifications = Notification::where('type', 'supply_officer')->orderBy('date', 'desc')->paginate(10);
        $limitNotifications = $notifications->take(10);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        return view('supply_officer.notification.notification', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate'));

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

    public function notificationRead(Request $request)
    {

        $notification = Notification::findOrFail($request->input('id'));

        if ($notification->is_read == 0) {
            $notification->is_read = 1;
            $notification->save();

            return redirect()->route('supply_officer.notification');
        } else {
            return redirect()->route('supply_officer.notification');
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

        return view('supply_officer.inventory.product', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'products', 'categories'));

    }

    public function viewProductReport()
    {
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $today = Carbon::now();
        $oneWeekFromToday = $today->addDays(7); // Calculate the date one week from today
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $randomNumber = mt_rand(100, 999);
        $reference = 'INVR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

        $products = Product::orderBy('expiration', 'asc')->get();
        $categories = Category::all();

        $data = [
            'products' => $products,
            'categories' => $categories,
            'currentTime' => $currentTime,
            'currentDate' => $currentDate,
            'reference' =>$reference,
        ];

        $pdf = app('dompdf.wrapper')->loadView('supply_officer.report.product_report', $data);

        return $pdf->stream('item_list_report.pdf');

        //return view('supply_officer.report.product_report', compact('currentTime', 'currentDate', 'products', 'categories'));
    }

    public function downloadProductReport()
    {
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $readableDate = date('M j, y');
        $today = Carbon::now();
        $profile = auth()->user();
        $oneWeekFromToday = $today->addDays(7); // Calculate the date one week from today
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $randomNumber = mt_rand(100, 999);
        $reference = 'INVR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

        $products = Product::orderBy('expiration', 'asc')->get();
        $categories = Category::all();

        $content =
            '              Inventory Report 
                ------------------------
    
                Report Reference Number: ' . $reference . '
                Report Date and Time: ' . $readableDate . ' ' . $currentTime . '
    
                Report Status: Finalized';


        Report::create([
            'reference_number' => $reference,
            'report_type' => 'Inventory Report',
            'date' => $currentDate,
            'time' => $currentTime,
            'user_id' => $profile->id,
            'author_type' => $profile->role,
            'content' => $content,
        ]);

        $data = [
            'products' => $products,
            'categories' => $categories,
            'currentTime' => $currentTime,
            'currentDate' => $currentDate,
            'reference' =>$reference,

        ];

        $pdf = app('dompdf.wrapper')->loadView('supply_officer.report.product_report', $data);

        return $pdf->download('item_list_report.pdf');

        //return view('supply_officer.report.product_report', compact('currentTime', 'currentDate', 'products', 'categories'));
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

        return view('supply_officer.inventory.product_details', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'product'));
    }

    public function productupdate(Request $request, $id)
    {
        $products = Product::find($id);
        $categories = Category::all();

        if (!$products) {
            return redirect()->route('supply_officer.product', compact('products', 'categories'))->with('error', 'Product not found');
        }

        $products->update($request->all());

        return redirect()->route('supply_officer.product')->with('success', 'Product updated successfully');
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
        $currentDateTime = Carbon::now()->setTimezone('Asia/Manila');
        $currentDate = $currentDateTime->format('Y-m-d');
        
        
        // Calculate the date three months from the current date
        $threeMonthsFromNow = $currentDateTime->copy()->addMonths(3)->format('Y-m-d');
        
        // Calculate the date six months from the current date
        $sixMonthsFromNow = $currentDateTime->copy()->addMonths(6)->format('Y-m-d');
        
        // Retrieve the pharmaceutical category ID
        $pharmaceuticalCategoryId = Category::where('category_name', 'pharmaceutical')->value('id');
        
        // Retrieve products with expiration dates within three months for non-pharmaceutical category
        $nonPharmaceuticalProductsToExpire = Product::where('expiration', '<=', $threeMonthsFromNow)
        ->where('category_id', '!=', $pharmaceuticalCategoryId)
        ->orderBy('created_at', 'asc')
        ->get();

        // Retrieve products with expiration dates within six months for pharmaceutical category
        $pharmaceuticalProductsToExpire = Product::where('expiration', '<=', $sixMonthsFromNow)
        ->where('category_id', '=', $pharmaceuticalCategoryId)
        ->orderBy('created_at', 'asc')
        ->get();

    
        // Combine the collections or pass them separately to your view as needed
        $products = $nonPharmaceuticalProductsToExpire->merge($pharmaceuticalProductsToExpire);
    
        foreach ($products as $product) {
            $existingExpiry = Expiries::where([
                'item_name' => $product->p_name,
                'exp_date' => $product->expiration,
            ])->first();
        
            if (!$existingExpiry) {
                Expiries::create([
                    'item_name' => $product->p_name,
                    'stock' => $product->stock,
                    'brand' => $product->brand,
                    'category' => $product->category->category_name,
                    'exp_date' => $product->expiration,
                ]);
            }
        }
        
    
        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentTime = $currentDateTime->format('h:i A');
    
        // Display the list of products
        return view('supply_officer.inventory.expiring_soon', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'products'));
    }
    
    
    public function viewExpiryReport()
    {
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $randomNumber = mt_rand(100, 999);
        $reference = 'EXPR-' . $currentDateWithoutHyphens . '-' . $randomNumber;
        $currentDate = Carbon::now();
        $categories = Product::get();
        
        // Calculate the date three months from the current date
        $threeMonthsFromNow = Carbon::now()->addMonths(3)->format('Y-m-d');
    
        // Calculate the date six months from the current date
        $sixMonthsFromNow = Carbon::now()->addMonths(6)->format('Y-m-d');
    
        // Retrieve the pharmaceutical category ID
        $pharmaceuticalCategoryId = Category::where('category_name', 'pharmaceutical')->value('id');
    
        // Retrieve products with expiration dates within three months for non-pharmaceutical category
        $nonPharmaceuticalProductsToExpire = Product::where('expiration', '<=', $threeMonthsFromNow)
            ->where('category_id', '!=', $pharmaceuticalCategoryId)
            ->get();
       
            // Retrieve products with expiration dates within six months for pharmaceutical category
       $pharmaceuticalProductsToExpire = Product::where('expiration', '<=', $sixMonthsFromNow)
            ->where('category_id', '=', $pharmaceuticalCategoryId)
            ->get();

            // Combine the collections or pass them separately to your view as needed
        $products = $nonPharmaceuticalProductsToExpire->merge($pharmaceuticalProductsToExpire);

        // Display the list of products

        $data = [
            'categories' => $categories,
            'products' => $products,
            'currentTime' => $currentTime,
            'currentDate' => $currentDate,
            'reference' =>$reference,

        ];

        $pdf = app('dompdf.wrapper')->loadView('supply_officer.report.expiry_report', $data);

        return $pdf->stream('expiry_item_report.pdf');
        //return view('supply_officer.report.expiry_report', compact('currentTime', 'currentDate', 'products'));
    }

    public function downloadExpiryReport()
    {
        $profile = auth()->user();
    
        $currentDateTime = Carbon::now()->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $currentDate = $currentDateTime->toDateString();
        $readableDate = date('M j, y');
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $randomNumber = mt_rand(100, 999);
        $reference = 'EXPR-' . $currentDateWithoutHyphens . '-' . $randomNumber;
    
        $categories = Product::get();
    
        // Calculate the date three months from the current date
        $threeMonthsFromNow = Carbon::now()->addMonths(3)->format('Y-m-d');
    
        // Calculate the date six months from the current date
        $sixMonthsFromNow = Carbon::now()->addMonths(6)->format('Y-m-d');
    
        // Retrieve the pharmaceutical category ID
        $pharmaceuticalCategoryId = Category::where('category_name', 'pharmaceutical')->value('id');
    
        // Retrieve products with expiration dates within three months for non-pharmaceutical category
        $nonPharmaceuticalProductsToExpire = Product::where('expiration', '<=', $threeMonthsFromNow)
            ->where('category_id', '!=', $pharmaceuticalCategoryId)
            ->get();
       
            // Retrieve products with expiration dates within six months for pharmaceutical category
       $pharmaceuticalProductsToExpire = Product::where('expiration', '<=', $sixMonthsFromNow)
       ->where('category_id', '=', $pharmaceuticalCategoryId)
       ->get();

            // Combine the collections or pass them separately to your view as needed
        $products = $nonPharmaceuticalProductsToExpire->merge($pharmaceuticalProductsToExpire);
    
            $content =
            '              Expiry Item Report 
                ------------------------
    
                Report Reference Number: ' . $reference . '
                Report Date and Time: ' . $readableDate . ' ' . $currentTime . '
    
                Report Status: Finalized';
                    
        Report::create([
            'reference_number' => $reference,
            'report_type' => 'Expiry Item report',
            'date' => $currentDate,
            'time' => $currentTime,
            'user_id' => $profile->id,
            'author_type' => $profile->role,
            'content' => $content,
        ]);
    
        $data = [
            'categories' => $categories,
            'products' => $products,
            'currentTime' => $currentTime,
            'currentDate' => $currentDate,
            'reference' => $reference
        ];

        $pdf = app('dompdf.wrapper')->loadView('supply_officer.report.expiry_report', $data);

        return $pdf->download('expiry_item_report.pdf');
        //return view('supply_officer.report.expiry_report', compact('currentTime', 'currentDate', 'products'));
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
        $categories = Category::with('products')->get();

        return view('supply_officer.inventory.category', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'products', 'categories'));

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

        return redirect()->route('supply_officer.category')->with('success', 'Category updated successfully.');
    }

    public function categorydelete($id)
    {
        $categories = Category::find($id);
        $categories->products()->delete();
        $categories->delete();

        return redirect('/supply_officer/category')->with('success', 'Category Deleted');
    }

    public function viewCategoryReport()
    {

        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $randomNumber = mt_rand(100, 999);
        $reference = 'CATLR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

        $products = Product::with('category')->get();
        $categories = Category::with('products')->get();


        $data = [
            'products' => $products,
            'categories' => $categories,
            'currentTime' => $currentTime,
            'currentDate' => $currentDate,
            'reference' => $reference,

        ];

        $pdf = app('dompdf.wrapper')->loadView('supply_officer.report.category_report', $data);

        return $pdf->stream('category_list_report.pdf');
        //return view('supply_officer.inventory.category', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'products', 'categories'));

    }

    public function downloadCategoryReport()
    {
        $profile = auth()->user();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $readableDate = date('M j, y');
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $randomNumber = mt_rand(100, 999);
        $reference = 'CATLR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

        $products = Product::with('category')->get();
        $categories = Category::with('products')->get();

        $content =
            '              Category Report 
                ------------------------
    
                Report Reference Number: ' . $reference . '
                Report Date and Time: ' . $readableDate . ' ' . $currentTime . '
    
                Report Status: Finalized';

        Report::create([
            'reference_number' => $reference,
            'report_type' => 'Category report',
            'date' => $currentDate,
            'time' => $currentTime,
            'user_id' => $profile->id,
            'author_type' => $profile->role,
            'content' => $content,
        ]);

        $data = [
            'products' => $products,
            'categories' => $categories,
            'currentTime' => $currentTime,
            'currentDate' => $currentDate,
            'reference' => $reference,
        ];

        $pdf = app('dompdf.wrapper')->loadView('supply_officer.report.category_report', $data);

        return $pdf->download('category_report.pdf');
        //return view('supply_officer.inventory.category', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'products', 'categories'));

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
        $requests = Request_Form::orderBy('created_at', 'desc')->get();

        return view('supply_officer.inventory.request', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'requests'));

    }


    public function viewRequestListReport()
    {
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $randomNumber = mt_rand(100, 999);
        $reference = 'REQLR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

        $requests = Request_Form::all();
        $products = Product::all();

        $data = [
            'requests' => $requests,
            'products' => $products,
            'currentTime' => $currentTime,
            'currentDate' => $currentDate,
            'reference' => $reference,

        ];

        $pdf = app('dompdf.wrapper')->loadView('supply_officer.report.request_list_report', $data);

        return $pdf->stream('request_list_report.pdf');

        //return view('supply_officer.report.request_list_report', compact( 'currentTime', 'currentDate', 'requests','products'));

    }

    public function downloadRequestListReport()
    {
        $profile = auth()->user();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $readableDate = date('M j, y');
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $randomNumber = mt_rand(100, 999);
        $reference = 'REQLR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

        $requests = Request_Form::all();
        $products = Product::all();

        $content =
            '              Request List Report 
                ------------------------
    
                Report Reference Number: ' . $reference . '
                Report Date and Time: ' . $readableDate . ' ' . $currentTime . '
    
                Report Status: Finalized';

        Report::create([
            'reference_number' => $reference,
            'report_type' => 'Request List report',
            'date' => $currentDate,
            'time' => $currentTime,
            'user_id' => $profile->id,
            'author_type' => $profile->role,
            'content' => $content,
        ]);

        $data = [
            'requests' => $requests,
            'products' => $products,
            'currentTime' => $currentTime,
            'currentDate' => $currentDate,
            'reference' => $reference,

        ];

        $pdf = app('dompdf.wrapper')->loadView('supply_officer.report.request_list_report', $data);

        return $pdf->download('request_list_report.pdf');

        //return view('supply_officer.report.request_list_report', compact( 'currentTime', 'currentDate', 'requests','products'));

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


        return view('supply_officer.inventory_demo.inventorydemo', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'products'));
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
            'supply_officer.inventory_demo.inventorydemo_search',
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
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $currentTime = $currentDateTime->format('h:i A');
        $randomNumber = mt_rand(100, 999);

        $selectedOption = $request->input('select');
        $chartData = [];

        if ($selectedOption === 'Category') {
            $data = Product::join('categories', 'products.category_id', '=', 'categories.id')
                ->select('categories.category_name', DB::raw('COUNT(*) as count'))
                ->groupBy('categories.category_name')
                ->orderByDesc('count')
                ->get();
            $chartTitle = 'category';
            $reference = 'CATAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;
            

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
            $reference = 'BRAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

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
            'supply_officer.report.inventory_report',
            compact(
                'currentTime',
                'currentDateTime',
                'chartData',
                'chartTitle',
                'selectedOption',
                'reference'
            )
        );
    }

    public function inventoryReportSave(Request $request)
    {
        // Common report reference generation
        $reference = $request->input('reference');
        $selectedOption = $request->input('select');
        $time = $request->input('time');
        $date = $request->input('date');

        $readableDate = date('F j, Y', strtotime($date));
        $profile = auth()->user();

        // Initialize variables
        $chartData = [];
        $chartTitle = '';
        $reportType = ''; // Initialize $reportType here

        // Process selected option
        if ($request->has('select')) {
            $selectedOption = $request->input('select');
            $currentDateWithoutHyphens = str_replace('-', '', date('Y-m-d'));
            $randomNumber = mt_rand(100, 999);

            if ($selectedOption === 'Category') {
                $chartTitle = 'category';
                $reference = 'CATAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

            } elseif ($selectedOption === 'Brand') {
                $chartTitle = 'brand';
                $reference = 'BRAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;
            } else {
                // Invalid selection, handle accordingly
                return redirect()->back()->with('error', 'Invalid selection.');
            }
        }

        // Common report content generation
        $content = '
            Brand/Category Analytics Report
            ------------------------
            Report Reference Number: ' . $reference . '
            Report Date and Time: ' . $readableDate . ' ' . $time . '

            Report Status: Finalized';

        // Common report creation
        Report::create([
            'reference_number' => $reference,
            'report_type' =>'Brand/Category Analytics Report',
            'date' => $date,
            'time' => $time,
            'user_id' => $profile->id,
            'author_type' => $profile->role,
            'content' => $content,
        ]);

        // Additional data for analytics report
        $currentDateTime = Carbon::now()->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        // Redirect with data
        return redirect()->route(
            'supply_officer.inventory.demo',
            compact(
                'profile',
                'currentTime',
                'currentDateTime',
                'chartData',
                'chartTitle',
                'reference',
                'reportType'
            )
        );
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


        return view('supply_officer.inventory_demo.saledemo', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'requests'));
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
            $productInfo = Product::find($productId);
    
            if ($productInfo) {
                $productName = $productInfo->p_name;
                $salesData[$productName] = [];
    
                foreach ($dateRange as $date) {
                    // Modify the query to get only the items that have sales within the date range
                    $quantity = Purchase::where('product_id', $productId)
                        ->whereBetween('created_at', [$date . ' 00:00:00', $date . ' 23:59:59'])
                        ->sum('quantity');
    
                    $salesData[$productName][] = $quantity;
                }
    
                // Remove products with no sales within the date range
                if (array_sum($salesData[$productName]) == 0) {
                    unset($salesData[$productName]);
                }
            }
        }


        return view('supply_officer.inventory_demo.saledemo_search', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'range', 'dateRange', 'salesData', 'fromDate', 'toDate', 'selectedOption'));
    }

    public function saleReport(Request $request)
    {
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $currentTime = $currentDateTime->format('h:i A');
        $randomNumber = mt_rand(100, 999);
        $reference = 'SAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

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
            $productInfo = Product::find($productId);
    
            if ($productInfo) {
                $productName = $productInfo->p_name;
                $salesData[$productName] = [];
    
                foreach ($dateRange as $date) {
                    // Modify the query to get only the items that have sales within the date range
                    $quantity = Purchase::where('product_id', $productId)
                        ->whereBetween('created_at', [$date . ' 00:00:00', $date . ' 23:59:59'])
                        ->sum('quantity');
    
                    $salesData[$productName][] = $quantity;
                }
    
                // Remove products with no sales within the date range
                if (array_sum($salesData[$productName]) == 0) {
                    unset($salesData[$productName]);
                }
            }
        }


        $datesWithSales = [];
        $itemCount = [];
        
        foreach ($salesData as $productName => $productSales) {
            // Filter out dates with sales (quantity > 0) for the current product
            $datesWithSales[$productName] = array_map(
                function ($index, $quantity) use ($dateRange) {
                    if ($quantity > 0 && isset($dateRange[$index])) {
                        return [
                            'date' => date("Y-m-d", strtotime($dateRange[$index])),
                            'quantity' => $quantity,
                        ];
                    }
                    return null;
                },
                array_keys($productSales),
                $productSales
            );
        
            // Remove null values
            $datesWithSales[$productName] = array_filter($datesWithSales[$productName]);
        
            // Store the count for the current item
            $itemCount[$productName] = count($datesWithSales[$productName]);
        }
        
        // Extract unique dates with sales across all products
        $uniqueDates = [];
        foreach ($datesWithSales as $productSales) {
            $uniqueDates = array_merge($uniqueDates, array_column($productSales, 'date'));
        }
        
        $uniqueDates = array_unique($uniqueDates);
        
        // Filter out dates with no sales across all products
        $filteredDates = array_values($uniqueDates);
        

        return view(
            'supply_officer.report.sale_report',
            compact(
                'currentDateTime',
                'currentTime',
                'range',
                'dateRange',
                'salesData',
                'products',
                'datesWithSales',
                'itemCount',
                'filteredDates',
                'reference'
            )
        );
    }

    public function saleReportSave(Request $request)
    {
        $reference = $request->input('reference');
        $time = $request->input('time');
        $date = $request->input('date');
        $type = $request->input('type');
        $readableDate = date('F j, Y', strtotime($date));
        $profile = auth()->user();

        $content =
            '             Sales Analytics Report
            ------------------------

            Report Reference Number: '.$reference.'
            Report Date and Time: '.$readableDate.' '. $time .'

            Report Status: Finalized';

        Report::create([
            'reference_number' => $reference,
            'report_type' => 'Sales Analytics Report',
            'date' => $date,
            'time' => $time,
            'user_id' => $profile->id,
            'author_type' => $profile->role,
            'content' => $content,
        ]);

        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $currentTime = $currentDateTime->format('h:i A');
        $randomNumber = mt_rand(100, 999);
        $reference = 'SAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

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
            $productInfo = Product::find($productId);
    
            if ($productInfo) {
                $productName = $productInfo->p_name;
                $salesData[$productName] = [];
    
                foreach ($dateRange as $date) {
                    // Modify the query to get only the items that have sales within the date range
                    $quantity = Purchase::where('product_id', $productId)
                        ->whereBetween('created_at', [$date . ' 00:00:00', $date . ' 23:59:59'])
                        ->sum('quantity');
    
                    $salesData[$productName][] = $quantity;
                }
    
                // Remove products with no sales within the date range
                if (array_sum($salesData[$productName]) == 0) {
                    unset($salesData[$productName]);
                }
            }
        }


        $datesWithSales = [];
        $itemCount = [];
        
        foreach ($salesData as $productName => $productSales) {
            // Filter out dates with sales (quantity > 0) for the current product
            $datesWithSales[$productName] = array_map(
                function ($index, $quantity) use ($dateRange) {
                    if ($quantity > 0 && isset($dateRange[$index])) {
                        return [
                            'date' => date("Y-m-d", strtotime($dateRange[$index])),
                            'quantity' => $quantity,
                        ];
                    }
                    return null;
                },
                array_keys($productSales),
                $productSales
            );
        
            // Remove null values
            $datesWithSales[$productName] = array_filter($datesWithSales[$productName]);
        
            // Store the count for the current item
            $itemCount[$productName] = count($datesWithSales[$productName]);
        }
        
        // Extract unique dates with sales across all products
        $uniqueDates = [];
        foreach ($datesWithSales as $productSales) {
            $uniqueDates = array_merge($uniqueDates, array_column($productSales, 'date'));
        }
        
        $uniqueDates = array_unique($uniqueDates);
        
        // Filter out dates with no sales across all products
        $filteredDates = array_values($uniqueDates);
        

        return redirect()->route(
            'supply_officer.sale.demo',
            compact(
                'currentDateTime',
                'currentTime',
                'range',
                'dateRange',
                'salesData',
                'products',
                'datesWithSales',
                'itemCount',
                'filteredDates',
                'reference'
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


        return view('supply_officer.inventory_demo.requestdemo', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'requests'));
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
        $formattedFromDate = date('"M j, Y"', strtotime($fromDate));
        $toDate = $request->input('end');
        $toDate = date("Y-m-d 23:59:59", strtotime($toDate));
        $formattedToDate = date("M j, Y ", strtotime($toDate));
        $selectedOption = $request->input('select');
        $range = $formattedFromDate . " - " . $formattedToDate;

        // Query your database to get the most requested products or departments based on the selected date range and category
        if ($selectedOption === 'Item') {
            // Get the most requested products with their creation dates
            $result = Request_Form::join('products', 'requests.product_id', '=', 'products.id')
                ->whereBetween('requests.created_at', [$fromDate, $toDate])
                ->groupBy('requests.product_id', 'products.p_name', 'requests.created_at') // Group by product ID and creation date
                ->selectRaw('products.p_name as label, requests.created_at as request_date, SUM(requests.quantity) as data')
                ->orderBy('request_date') // Order by created_at ascending
                ->get();
        } elseif ($selectedOption === 'Department') {
            // Get the most requested departments with their creation dates
            $result = Request_Form::whereBetween('created_at', [$fromDate, $toDate])
                ->groupBy('department', 'date') // Group by department and date
                ->selectRaw('department as label, department, date as request_date, COUNT(DISTINCT created_at, department) as data') // Use SUM(1) to get the count of occurrences
                ->orderBy('request_date', 'asc') // Order by request_date ascending
                ->orderBy('data', 'asc') // Then, order by the count of occurrences in ascending order
                ->get();
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
        return view(
            'supply_officer.inventory_demo.requestdemo_search', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'chartData', 'range',
 'selectedOption', 'fromDate', 'toDate' ));
    }

    //Request
    public function requestReport(Request $request)
    {
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $currentTime = $currentDateTime->format('h:i A');
        $randomNumber = mt_rand(100, 999);
        $reference = 'REQAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;


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
                ->selectRaw('products.p_name as label, requests.created_at as request_date, SUM(requests.quantity) as data')
                ->orderBy('request_date') // Order by created_at ascending
                ->get();
            $reportType = 'item'; // Set the report type to 'item'
            $reference = 'REQIAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

        } elseif ($selectedOption === 'Department') {
            // Get the most requested departments with their creation dates
            $result = Request_Form::whereBetween('created_at', [$fromDate, $toDate])
                ->groupBy('department', 'date') // Group by department and date
                ->selectRaw('department as label, department, date as request_date, COUNT(DISTINCT created_at, department) as data') // Use SUM(1) to get the count of occurrences
                ->orderBy('request_date', 'asc') // Order by request_date ascending
                ->orderBy('data', 'asc') // Then, order by the count of occurrences in ascending order
                ->get();
                $reportType = 'department';
                $reference = 'REQDAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

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
        return view('supply_officer.report.request_report', compact('currentTime', 'currentDateTime', 'chartData', 'range', 'result', 'reportType', 'reference'));
    }

    public function requestReportSave(Request $request)
    {
        $reference = $request->input('reference');
        $time = $request->input('time');
        $date = $request->input('date');
        $type = $request->input('type');
        $readableDate = date('F j, Y', strtotime($date));
        $profile = auth()->user();

        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $randomNumber = mt_rand(100, 999);

        $fromDate = $request->input('start');   
        $formattedFromDate = date("M j, Y", strtotime($fromDate));
        $toDate = $request->input('end');
        $formattedToDate = date("M j, Y", strtotime($toDate));
        $selectedOption = $request->input('select');
        $range = $formattedFromDate . " - " . $formattedToDate;

        // Query your database to get the most requested products or departments based on the selected date range and category
 
        if ($request->has('select')) {
            $currentDateWithoutHyphens = str_replace('-', '', date('Y-m-d'));
            $randomNumber = mt_rand(100, 999);
    
            if ($selectedOption === 'Item') {
                $reportType = 'Most Requested Item'; // Set the report type to 'Most Requested Item'
                $reference = 'REQIAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;
    
            } elseif ($selectedOption === 'Department') {
                $reportType = 'Most Requesting Department';
                $reference = 'REQDAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;
    
            } else {
                // Invalid selection, handle accordingly
                return redirect()->back()->with('info', 'Invalid selection.');
            }
        }
    
        $content =
            '             Most Requesting/Requested Analytics Report
            ------------------------
    
            Report Reference Number: '.$reference.'
            Report Date and Time: '.$readableDate.' '. $time .'  
    
            Report Status: Finalized';
    
        Report::create([
            'reference_number' => $reference,
            'report_type' => 'Most Requesting/Requested Analytics Report',
            'date' => $date,
            'time' => $time,
            'user_id' => $profile->id,
            'author_type' => $profile->role,
            'content' => $content,
        ]);
    
        // Return the view with the chart data
        return redirect()->route('supply_officer.request.demo', compact('currentTime', 'currentDateTime', 'range', 'reference', 'reportType'));
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
            'supply_officer.inventory_demo.medicinedemo',
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
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $currentTime = $currentDateTime->format('h:i A');
        $randomNumber = mt_rand(100, 999);
        $reference = 'MEDAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

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
            'supply_officer.report.medicines_report',
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
                'lowValuedProducts',
                'reference'
            )
        );
    }

    public function medicineReportSave(Request $request)
    {
        $reference = $request->input('reference');
        $time = $request->input('time');
        $date = $request->input('date');
        $type = $request->input('type');
        $readableDate = date('F j, Y', strtotime($date));
        $profile = auth()->user();

        $content =
            '             Medicine Analytics Report
            ------------------------

            Report Reference Number: '.$reference.'
            Report Date and Time: '.$readableDate.' '. $time .'

            Report Status: Finalized';

        Report::create([
            'reference_number' => $reference,
            'report_type' => 'Medicine Analytics Report',
            'date' => $date,
            'time' => $time,
            'user_id' => $profile->id,
            'author_type' => $profile->role,
            'content' => $content,
        ]);

        $profile = auth()->user();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
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


        return redirect()->route(
            'supply_officer.medicine.demo',
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
                'lowValuedProducts',
                'reference'
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
            'supply_officer.inventory_demo.productdemo',
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
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $currentTime = $currentDateTime->format('h:i A');
        $randomNumber = mt_rand(100, 999);
        $reference = 'IFSNAR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

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
            'supply_officer.report.products_report',
            compact(
                'counts',
                'currentDate',
                'currentTime',
                'categories',
                'fastProducts',
                'slowProducts',
                'nonMovingProducts',
                'reference'

            )
        );
    }

    public function productsReportSave(Request $request)
    {
        $reference = $request->input('reference');
        $time = $request->input('time');
        $date = $request->input('date');
        $type = $request->input('type');
        $readableDate = date('F j, Y', strtotime($date));
        $profile = auth()->user();

        $content =
            '             Item Analytics Report
            ------------------------

            Report Reference Number: '.$reference.'
            Report Date and Time: '.$readableDate.' '. $time .'

            Report Status: Finalized';

        Report::create([
            'reference_number' => $reference,
            'report_type' => 'Item Analytics Report',
            'date' => $date,
            'time' => $time,
            'user_id' => $profile->id,
            'author_type' => $profile->role,
            'content' => $content,
        ]);

        $profile = auth()->user();
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

        return redirect()->route(
            'supply_officer.product.demo',
            compact(
                'counts',
                'currentDate',
                'currentTime',
                'categories',
                'fastProducts',
                'slowProducts',
                'nonMovingProducts',
                'reference'

            )
        );
    }
    public function supplyOfficerLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}