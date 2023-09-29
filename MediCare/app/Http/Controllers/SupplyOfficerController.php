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
        $notifications = Notification::where('type', 'admin')->orderBy('date', 'desc')->get();
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

        $products = Product::all();
        // Retrieve the unique years from the "admitted" column
        $uniqueYears = Product::select(DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->get()
            ->pluck('year')
            ->toArray();


        return view('supply_officer.demo.productdemo', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'products', 'uniqueYears'));
    }

    public function productdemoSearch(Request $request)
    {
        $request->validate([
            'product' => 'required',
            'year' => 'required',
        ]);
    
        // Retrieve the selected product
        $selectedProduct = Product::find($request->input('product'));
        $selectedYear = $request->input('year');
    
        // Get the current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
    
        // Check if the selected year is the current year
        $isCurrentYear = ($selectedYear == $currentYear);
    
        // Fetch data for the selected product, grouped by month for the selected year
        $productCounts = Product::selectRaw('MONTH(created_at) as month, SUM(stock) as total_stock_added')
            ->where('id', $selectedProduct->id)
            ->whereYear('created_at', $selectedYear)
            ->groupBy('month')
            ->get();
    
        // Calculate the total stock added for the current month or set it to zero if it's not the current year
        $totalStockAdded = $isCurrentYear ? $productCounts->where('month', $currentMonth)->sum('total_stock_added') : 0;
    
        // Calculate the total quantity of requested products for the selected product
        $totalRequestedProducts = Request_Form::where('product_id', $selectedProduct->id)
            ->sum('quantity');
    
        // Calculate the total quantity of purchased products for the selected product
        $totalPurchasedProducts = Purchase::where('product_id', $selectedProduct->id)
            ->sum('quantity');
    
        // Calculate the remaining stock in inventory for the selected product
        $remainingStock = $totalStockAdded - $totalRequestedProducts - $totalPurchasedProducts;
    
        // Retrieve all products, unique years for dropdowns, and the product count for the chart
        $products = Product::all();
        $uniqueYears = Product::select(DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->get()
            ->pluck('year')
            ->toArray();
    
        // Create an array to hold data for all months of the current year
        $months = range(1, 12);
        $productData = [];
    
        // Fill in the counts for each month
        foreach ($months as $month) {
            $monthStockAdded = $productCounts->where('month', $month)->first();
            $productData[] = [
                'month' => $month,
                'total_count' => $monthStockAdded ? $monthStockAdded->total_stock_added : 0,
            ];
        }

        
    
        return view('supply_officer.demo.productdemo_search', compact('products', 'uniqueYears', 'productData', 'selectedProduct', 'selectedYear', 'totalStockAdded', 'isCurrentYear', 'totalRequestedProducts', 'totalPurchasedProducts', 'remainingStock'));
    }
    

    public function supplyOfficerLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}