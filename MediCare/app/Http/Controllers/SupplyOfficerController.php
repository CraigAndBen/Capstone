<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Category;
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
        $notifications = Notification::where('type', 'supply_officer')->orderBy('date', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
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
        $products = Product::with('category')->paginate(5);
        $categories = Category::with('products')->get();

        return view('supply_officer.inventory.product', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'products', 'categories'));

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

        return view('supply_officer.report.product_report', compact('currentTime', 'currentDate', 'products', 'categories'));
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
        $oneMonthFromNow = $currentDate->copy()->addMonth();

        // Retrieve products with expiration dates within the date range
        $products = Product::where('expiration', '>', $currentDate)
            ->where('expiration', '<=', $oneMonthFromNow)
            ->get();

        // Display the list of products
        return view('supply_officer.inventory.expiring_soon', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'products'));

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
        $oneMonthFromNow = $currentDate->copy()->addMonth();

        // Retrieve products with expiration dates within the date range
        $products = Product::where('expiration', '>', $currentDate)
            ->where('expiration', '<=', $oneMonthFromNow)
            ->get();

        // Display the list of products
        return view('supply_officer.report.expiry_report', compact('currentTime', 'currentDate', 'products'));
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

        return view('supply_officer.inventory.request', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'requests'));

    }

    public function requestListReport()
    {
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $requests = Request_Form::all();
        $products = Product::all();

        return view('supply_officer.report.request_list_report', compact( 'currentTime', 'currentDate', 'requests','products'));

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
            'supply_officer.report.inventory_report',
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


        $fromDate = Carbon::parse($request->input('start'));
        $toDate = Carbon::parse($request->input('end'));
        $selectedOption = $request->input('select');

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
        return view('supply_officer.inventory_demo.requestdemo_search', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'chartData'));
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
        return view('supply_officer.report.request_report', compact('currentTime', 'currentDate', 'chartData', 'range', 'result'));
    }

     //Salaes Demo
     public function salesDemo()
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
 
 
         return view('supply_officer.inventory_demo.salesdemo', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'requests'));
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


        return view('supply_officer.inventory_demo.saledemo_search', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'range', 'dateRange', 'salesData', 'fromDate', 'toDate', 'selectedOption'));
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


        return view('supply_officer.report.sale_report', compact('currentTime', 'currentDate', 'range', 'dateRange', 'salesData', 'products'));
    }
    public function supplyOfficerLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}