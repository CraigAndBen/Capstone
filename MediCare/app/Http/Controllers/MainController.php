<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Request_Form;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function Mainindex()
    {
        $products = Product::with('category')->get();
        $categories = Category::with('products')->get();

        return view('main', compact('products', 'categories'));
    }

    /** Request Form**/

    public function Requestformindex()
    {
        $products = Product::all();

        return view('main.request_form', compact('products'));
    }
    public function Requeststore(Request $request)
    {
        $product = Product::find($request->input('product_id'));

        // Check if the product exists and has enough stock
        if ($product && $product->stock >= $request->input('quantity')) {
            // Reduce the stock of the product
            $product->stock -= $request->input('quantity');
            $product->save(); // Save the updated stock

            $prod_requests = new Request_Form();
            $prod_requests->name_requester = $request->input('name_requester');
            $prod_requests->department = $request->input('department');
            $prod_requests->date = $request->input('date');
            $prod_requests->product_id = $request->input('product_id');
            $prod_requests->brand = $request->input('brand');
            $prod_requests->quantity = $request->input('quantity');

            $prod_requests->save();

            return redirect('/main/request_form')->with('status', 'Request Sent');
        } else {
            return redirect('/main/request_form')->with('error', 'Not enough stock available');
        }
    }
    public function Pharmacistindex()
    {
        $products = Product::all();

        return view('main.pharmacist', compact('products'));

    }


    public function cashier()
    {
        return view('main.cashier');
    }

}