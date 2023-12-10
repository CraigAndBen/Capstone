<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use TCPDF;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Purchase_detail;
use App\Models\Report;
use Illuminate\View\View;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\Product_price;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class CashierController extends Controller
{
    public function dashboard()
    {
        $profile = auth()->user();
        $notifications = Notification::where('type', 'admin')->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentYear = Carbon::now()->year;
        $currentDate = date('M j, Y');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        return view('cashier_dashboard', compact('profile', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
    }


    public function profile(Request $request): View
    {

        $profile = $request->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('M j, Y');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        return view('cashier.profile.profile', compact('profile', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
    }

    public function passwordProfile(Request $request): View
    {
        $profile = $request->user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->get();
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('M j, Y');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');

        return view('cashier.profile.profile_password', compact('profile', 'limitNotifications', 'count', 'currentTime', 'currentDate'));
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

        return view('cashier.notification.notification', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate'));

    }

    public function notificationRead(Request $request)
    {

        $notification = Notification::findOrFail($request->input('id'));

        if ($notification->is_read == 0) {
            $notification->is_read = 1;
            $notification->save();

            return redirect()->route('cashier.notification');
        } else {
            return redirect()->route('cashier.notification');
        }

    }

    public function purchaseList()
    {
        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('M j, Y');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $purchases = Purchase_detail::get(); // Retrieve products from your database
        $purchaseDetails = Purchase::get();




        return view('cashier.product.purchase_list', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'purchases', 'purchaseDetails'));
    }


    public function viewPurchaseReport()
    {
        $currentDate = date('Y-m-d');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $randomNumber = mt_rand(100, 999);
        $reference = 'PURLR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

        $purchases = Purchase_detail::all(); // Retrieve products from your database

        // return view('cashier.report.purchase_report', compact('currentTime', 'currentDate', 'purchases'));
        $data = [
            'purchases' => $purchases,
            'currentTime' => $currentTime,
            'currentDate' => $currentDate,
            'reference' =>$reference,

        ];

        $pdf = new TCPDF();
        // Add a page
        $pdf->AddPage();
        // Read HTML content from a file
        $htmlFilePath = resource_path('views/cashier/report/purchase_report.blade.php');
        $htmlContent = view()->file($htmlFilePath, $data)->render();
      
        $pdf->writeHTML($htmlContent);
        // Output PDF to browser
        $pdf->Output($reference . '.pdf', 'I');
    }

    public function downloadPurchaseReport()
    {
        $profile = auth()->user();
        $currentDate = date('Y-m-d');
        $readableDate = date('M j, y');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $currentDateWithoutHyphens = str_replace('-', '', $currentDate);
        $randomNumber = mt_rand(100, 999);
        $reference = 'PURLR-' . $currentDateWithoutHyphens . '-' . $randomNumber;

        $purchases = Purchase_detail::all(); // Retrieve products from your database

        $content =
        '              Purchase Transaction Report 
            ------------------------

            Report Reference Number: ' . $reference . '
            Report Date and Time: ' . $readableDate . ' ' . $currentTime . '

            Report Status: Finalized';

    Report::create([
        'reference_number' => $reference,
        'report_type' => 'Purchase Transaction Report',
        'date' => $currentDate,
        'time' => $currentTime,
        'user_id' => $profile->id,
        'author_type' => $profile->role,
        'content' => $content,
    ]);

        $data = [
            'purchases' => $purchases,
            'currentTime' => $currentTime,
            'currentDate' => $currentDate,
            'reference' => $reference,

        ];

         // Create new PDF document
         $pdf = new TCPDF();
         // Add a page
         $pdf->AddPage();
         $pdf->SetPrintHeader(false);
         // Read HTML content from a file
         $htmlFilePath = resource_path('views/cashier/report/purchase_report.blade.php');
         $htmlContent = view()->file($htmlFilePath, $data)->render();
       
         $pdf->writeHTML($htmlContent);
         // Output PDF to browser
         $pdf->Output($reference . '.pdf', 'D');
    }

    public function purchase()
    {
        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('M j, Y');
        $currentDateTime = Carbon::now();
        $currentDateTime->setTimezone('Asia/Manila');
        $currentTime = $currentDateTime->format('h:i A');
        $prices = Product_price::all(); // Retrieve products from your database
        $products = Product::all();
        // Retrieve the cart items from the session
        $cart = session('cart', []);

        return view('cashier.product.purchase', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'products', 'cart', 'prices'));
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

        // Check if the requested quantity exceeds available stock
        if ($product->stock < $request->quantity) {
            $remainingStock = $product->stock;
            return redirect()->route('cashier.product.purchase')->with('error', "Insufficient stock for this product. Available stock: $remainingStock");
        }

        // Reduce the stock of the product
        $product->stock -= $request->input('quantity');
        $product->save(); // Save the updated stock

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

        return redirect()->route('cashier.product.purchase')->with('success', 'Product added to cart successfully');
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
        return redirect()->route('cashier.product.purchase')->with('success', 'Product removed from cart successfully');
    }

    public function receiptPreview(Request $request)
    {
        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('M j, Y');
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

        // Increment the reference number by 1
        $reference += 1;

        if ($change < 0) {
            return redirect()->route('cashier.product.purchase')->with('info', 'Insufficient Amount');
        }

        session(['pdf_reference' => $reference]);

        return view('cashier.product.receipt_preview', compact('profile', 'notifications', 'limitNotifications', 'count', 'currentTime', 'currentDate', 'cart', 'reference', 'totalPrice', 'amount', 'change'));
    }

    public function receipt(Request $request)
    {
        $profile = Auth::user();
        $notifications = Notification::where('type', $profile->role)->orderBy('date', 'desc')->paginate(5);
        $limitNotifications = $notifications->take(5);
        $count = $notifications->count();
        $currentDate = date('M j, Y');
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

        // Increment the reference number by 1
        $reference += 1;

        if ($change < 0) {
            return redirect()->route('cashier.product.purchase')->with('info', 'Insufficient Amount');
        }

        $reference = session('pdf_reference');

        $data = [
            'currentTime' => $currentTime,
            'currentDate' => $currentDate,
            'cart' => $cart,
            'reference' => $reference,
            'totalPrice' => $totalPrice,
            'amount' => $amount,
            'change' => $change,

        ];

            
        $pdf = new TCPDF();
        // Add a page
     
        $pdf->AddPage('L', 'A6');
        // Read HTML content from a file
        $htmlFilePath = resource_path('views/cashier/product/receipt.blade.php');
        $htmlContent = view()->file($htmlFilePath, $data)->render();
      
        $pdf->writeHTML($htmlContent);
        // Output PDF to browser
        $pdf->Output($reference . '.pdf', 'I');
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

        return redirect()->route('cashier.product.purchase')->with('success', 'Payment confirmed and saved successfully');
    }


    public function cashierOfficerLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }



}