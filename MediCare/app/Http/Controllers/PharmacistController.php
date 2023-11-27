<?php

namespace App\Http\Controllers;

use App\Models\Purchase_detail;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Report;
use Illuminate\View\View;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\Product_price;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class PharmacistController extends Controller
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

        return view('pharmacist_dashboard', compact('profile', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
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

        return view('pharmacist.profile.profile', compact('profile', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
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

        return view('pharmacist.profile.profile_password', compact('profile', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
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

    //Medicine Inventory
    public function medicine()
    {
        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        
        $products = Product::with('category')->whereHas('category', function ($query) {
            $query->where('category_name', 'pharmaceutical');
        })->get();
        $categories = Category::where('category_name', 'pharmaceutical')->get();

        
    
        return view('pharmacist.product.inventory_medicine', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'products', 'categories'));
    }

    public function medicineStore(Request $request)
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

    public function medicineDetail($id)
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

        return view('pharmacist.product.medicine_details', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'product'));
    }

    public function medicineUpdate(Request $request, $id)
    {
        $products = Product::find($id);
        $categories = Category::all();

        if (!$products) {
            return redirect()->back()->with('error', 'Product not found');
        }

        $products->update($request->all());

        return redirect()->back()->with('success', 'Product updated successfully');
    }

    public function medicineDelete($id)
    {
        $products = Product::find($id);
        $products->requests()->delete();
        $products->delete();

        return redirect()->back()->with('success', 'Product Deleted.');

    }

    public function viewMedicineReport()
    {
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $today = Carbon::now();
        $oneWeekFromToday = $today->addDays(7); // Calculate the date one week from today

        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $randomNumber = mt_rand(100, 999);
        $reference = 'MINVR-' . $currentDateWithoutHyphens . '-' . $randomNumber;
        
        $products = Product::orderBy('expiration', 'asc')->get();
        $categories = Category::all();

        $data = [
            'products' => $products,
            'categories' => $categories,
            'currentTime' => $currentTime,
            'currentDate' => $currentDate,
            'reference' => $reference,
        ];

        $pdf = app('dompdf.wrapper')->loadView('pharmacist.report.medicine_report', $data);

        return $pdf->stream('medicine report.pdf');
    
       // return view('pharmacist.report.medicine_report', compact('currentTime', 'currentDate', 'products', 'categories'));
    }

    public function downloadMedicineReport()
    {
        $profile = auth()->user();
        $currentDate = date('Y-m-d');
        $today = Carbon::now();
        $readableDate = date('M j, y');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $oneWeekFromToday = $today->addDays(7); // Calculate the date one week from today
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $randomNumber = mt_rand(100, 999);
        $reference = 'MINVR-' . $currentDateWithoutHyphens . '-' . $randomNumber;
        
        $products = Product::orderBy('expiration', 'asc')->get();
        $categories = Category::all();

        $content =
            '              Medicine Inventory Report 
                ------------------------
    
                Report Reference Number: ' . $reference . '
                Report Date and Time: ' . $readableDate . ' ' . $currentTime . '
    
                Report Status: Finalized';

        Report::create([
            'reference_number' => $reference,
            'report_type' => 'Medicine Inventory',
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

        $pdf = app('dompdf.wrapper');
        $pdf->setBasePath(public_path());
        $pdf = app('dompdf.wrapper')->loadView('pharmacist.report.medicine_report', $data);

        return $pdf->download('medicine report.pdf');
    
       // return view('pharmacist.report.medicine_report', compact('currentTime', 'currentDate', 'products', 'categories'));
    }

    //Product Price
    public function product()
    {
        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $products_price = Product_price::all();
        $products = Product::with('category')->get();
        $categories = Category::where('category_name', 'pharmaceutical')->get();

        return view('pharmacist.product.product', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'products', 'categories', 'products_price'));
    }
    
    public function viewProductReport()
    {
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $randomNumber = mt_rand(100, 999);
        $reference = 'MEDPR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

        $products_price = Product_price::whereNotNull('price')->get();
        $products = Product::all();
        $categories = Category::where('category_name', 'pharmaceutical')->get();

        $data = [
            'products_price' => $products_price,
            'products' => $products,
            'categories' => $categories,
            'currentTime' => $currentTime,
            'currentDate' => $currentDate,
            'reference' => $reference,
        ];

        $pdf = app('dompdf.wrapper')->loadView('pharmacist.report.product_report', $data);

        return $pdf->stream('medicine price report.pdf');
    

        //return view('pharmacist.report.product_report', compact('currentTime', 'currentDate', 'products', 'categories', 'products_price'));
    }

    public function downloadProductReport()
    {
        $profile = auth()->user();
        $currentDate = date('Y-m-d');
        $readableDate = date('M j, y');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $randomNumber = mt_rand(100, 999);
        $reference = 'MEDPR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

        $products_price = Product_price::whereNotNull('price')->get();
        $products = Product::all();
        $categories = Category::where('category_name', 'pharmaceutical')->get();

        $content =
            '              Medicine Price Report 
                ------------------------
    
                Report Reference Number: ' . $reference . '
                Report Date and Time: ' . $readableDate . ' ' . $currentTime . '
    
                Report Status: Finalized';

        Report::create([
            'reference_number' => $reference,
            'report_type' => 'Medicine Price report',
            'date' => $currentDate,
            'time' => $currentTime,
            'user_id' => $profile->id,
            'author_type' => $profile->role,
            'content' => $content,
        ]);

        $data = [
            'products_price' => $products_price,
            'products' => $products,
            'categories' => $categories,
            'currentTime' => $currentTime,
            'currentDate' => $currentDate,
            'reference' => $reference,
        ];


        $pdf = app('dompdf.wrapper')->loadView('pharmacist.report.product_report', $data);

        return $pdf->download('medicine price report.pdf');
    

        //return view('pharmacist.report.product_report', compact('currentTime', 'currentDate', 'products', 'categories', 'products_price'));
    }

    public function productCreate(Request $request)
    {
        $request->validate([
            'product' => 'required',
            'price' => 'required|numeric|min:0.01'
        ]);
        
        
        $product_price = new Product_price;
        $product_price->product_id = $request->input('product');
        $product_price->price = $request->input('price');

        $product_price->save();

        return redirect()->back()->with('success', 'Data Saved');
    }

    public function productUpdate(Request $request)
    {
        // Find the category by ID
        $product = Product_price::find($request->input('id'));

        if (!$product) {
            return redirect()->route('pharmacist.product')->with('error', 'Item not found.');
        }

        // Update the category with new data
        $product->update($request->all());

        return redirect()->route('pharmacist.product')->with('success', 'Item updated successfully.');
    }

    public function productDelete($id)
    {
        // Find the record by ID
        $product = Product_price::find($id);

        // Check if the record exists
        if ($product) {
            // Delete the record
            $product->delete();

            // Optionally, you can return a response or redirect to a page.
            // For example, to redirect back to a list page:
            return redirect()->route('pharmacist.product');
        }
    }

    
    public function pharmacistLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}