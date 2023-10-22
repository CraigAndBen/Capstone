<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Notification;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExpirationAlert
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Calculate the date three months from the current date
        $threeMonthsFromNow = Carbon::now()->addMonths(3)->format('Y-m-d');

        // Retrieve products with expiration dates within three months
        $productsToExpire = Product::where('expiration', '<=', $threeMonthsFromNow)->get();

        foreach ($productsToExpire as $product) {
            $title = 'Product Expiration Alert';
            $message = "Product: $product->p_name will expire on: " . date("M j, Y", strtotime($product->expiration));


            // Check if a similar notification already exists for this product
            $notificationExists = Notification::where('title', $title)
                ->where('message', $message)
                ->where('date', $product->expiration)
                ->exists();

                if (!$notificationExists) {
                    // Set the Manila time zone
                    $manilaTime = Carbon::now('Asia/Manila');
                
                    // Create a new notification for product expiration
                    Notification::create([
                        'title' => $title,
                        'message' => $message,
                        'date' => $product->expiration,
                        'time' => $manilaTime->format('g:i A'),
                        'type' => 'supply_officer',
                    ]);
                }
                
        }

        // Retrieve products with stock less than 100
        $productsLowStock = Product::where('stock', '<', 100)->get();

        foreach ($productsLowStock as $product) {
            $title = 'Low Stock Alert';
            $message = "Product: $product->p_name has low stock. The Remaining stock: $product->stock";

            // Check if a similar notification already exists for this product
            $notificationExists = Notification::where('title', $title)
                ->where('message', $message)
                ->exists();

                if (!$notificationExists) {
                    // Set the Manila time zone
                    $manilaTime = Carbon::now('Asia/Manila');
                
                    // Create a new notification for product expiration
                    Notification::create([
                        'title' => $title,
                        'message' => $message,
                        'date' => $product->expiration,
                        'time' => $manilaTime->format('g:i A'),
                        'type' => 'supply_officer',
                    ]);
                }
                
        }

        return $next($request);
    }
}