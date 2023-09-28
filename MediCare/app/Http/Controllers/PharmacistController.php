<?php

namespace App\Http\Controllers;

use App\Models\Purchase_detail;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use Illuminate\View\View;
use Illuminate\Support\Str;
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

        return view('pharmacist.notification.notification', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate'));

    }

    public function notificationRead(Request $request)
    {

        $notification = Notification::findOrFail($request->input('id'));

        if ($notification->is_read == 0) {
            $notification->is_read = 1;
            $notification->save();

            return redirect()->route('pharmacist.notification');
        } else {
            return redirect()->route('pharmacist.notification');
        }

    }

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
        $products = Product::all();
        $categories = Category::where('category_name', 'pharmaceutical')->get();

        return view('pharmacist.product.product', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'products', 'categories', 'products_price'));
    }

    public function productCreate(Request $request)
    {
        $request->validate([
            'product' => 'required',
            'price' => 'required'
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
            return redirect()->route('pharmacist.product')->with('error', 'Category not found.');
        }

        // Update the category with new data
        $product->update($request->all());

        return redirect()->route('pharmacist.product')->with('success', 'Category updated successfully.');
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

    public function purchase()
    {
        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $prices = Product_price::all(); // Retrieve products from your database
        $products = Product::all();
        // Retrieve the cart items from the session
        $cart = session('cart', []);

        return view('pharmacist.product.purchase', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'products', 'cart', 'prices'));
    }

    public function purchaseadd(Request $request)
    {
        // Validate the request if necessary
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Retrieve the product from the database
        $product = Product::find($request->product_id);
        $price = Product_price::where('product_id', $request->product_id)->first();

        // Get the existing cart items from the session or initialize an empty array
        $cart = $request->session()->get('cart', []);

        // Add the selected product to the cart with quantity
        $cart[] = [
            'product_id' => $product->id,
            'name' => $product->p_name,
            'price' => $price->price,
            'quantity' => $request->quantity,
        ];

        // Store the updated cart in the session
        $request->session()->put('cart', $cart);

        return redirect()->route('pharmacist.product.purchase')->with('success', 'Product added to cart successfully');
    }

    public function removeProduct(Request $request, $key)
    {
        // Retrieve the cart items from the session
        $cart = $request->session()->get('cart', []);

        // Check if the key exists in the cart array
        if (array_key_exists($key, $cart)) {
            // Remove the item with the provided key from the cart
            unset($cart[$key]);

            // Update the cart in the session
            $request->session()->put('cart', $cart);
        }

        // Redirect back to the cashier page
        return redirect()->route('pharmacist.product.purchase')->with('success', 'Product removed from cart successfully');
    }

    public function receipt(Request $request)
    {
        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        // Retrieve the cart data from the session
        $cart = session('cart', []);
        // Initialize a variable to store the total price
        $totalPrice = 0;
        $amount = $request->input('amount');

        // Loop through the cart items and calculate the total price
        foreach ($cart as $item) {
            $quantity = $item['quantity'];
            $price = $item['price'];

            // Calculate the total price for the current item
            $itemTotalPrice = $quantity * $price;

            // Add the item's total price to the overall total price
            $totalPrice += $itemTotalPrice;
        }

        $min = 10000000; // Smallest 8-digit number
        $max = 99999999; // Largest 8-digit number
        $reference = mt_rand($min, $max);
        $change = $amount - $totalPrice;

        if ($change < 0) {
            return redirect()->route('pharmacist.product.purchase')->with('info', 'insufficient Amount');
        }

        return view('pharmacist.product.receipt', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'cart', 'reference', 'totalPrice', 'amount', 'change'));
    }

    public function purchaseConfirm(Request $request)
    {
        // Retrieve the cart data from the session
        $cart = $request->session()->get('cart', []);
        // Initialize variables to store totals
        $totalQuantity = 0;
        $totalPrice = 0;

        // Iterate through the cart items and store them in the database
        foreach ($cart as $cartItem) {
            // Create a new CartItem model instance
            $item = new Purchase();

            // Assign values to the model's properties based on the cart data
            $item->product_id = $cartItem['product_id'];
            $item->reference = $request->input('reference');
            $item->quantity = $cartItem['quantity'];
            $item->price = $cartItem['price'];

            // Calculate and update the totals
            $totalQuantity += $cartItem['quantity'];
            $totalPrice += $cartItem['quantity'] * $cartItem['price'];

            // Save the cart item to the database
            $item->save();
        }

        $totals = new Purchase_detail();
        $totals->reference = $request->input('reference');
        $totals->total_quantity = $totalQuantity;
        $totals->total_price = $totalPrice;
        $totals->amount = $request->input('amount');
        $totals->change = $request->input('change');
        $totals->save();
    

        // Clear the cart from the session
        $request->session()->forget('cart');

        return redirect()->route('pharmacist.product.purchase')->with('success', 'Payment confirmed and saved successfully');
    }
    public function pharmacistLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}