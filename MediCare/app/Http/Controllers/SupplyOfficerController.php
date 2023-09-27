<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Category;
use App\Models\Request_Form;
use Illuminate\View\View;
use App\Models\Notification;
use Illuminate\Http\Request;
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

        return view('supply_officer.notification.notification', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate'));

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

    public function supplyOfficerLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}