<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Category;
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
        // Set the Manila time zone
        $manilaTime = Carbon::now('Asia/Manila');
    
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
    
        foreach ($nonPharmaceuticalProductsToExpire as $product) {
            $title = 'Product Expiration Alert';
            $message = "Item: $product->p_name will expire in 3 months on: " . date("M j, Y", strtotime($product->expiration));
    
            // Check if a similar notification already exists for this product
            $notificationExists = Notification::where('title', $title)
                ->where('message', $message)
                ->where('date', $product->expiration)
                ->exists();
    
            if (!$notificationExists) {
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
    
        // Retrieve products with expiration dates within six months for pharmaceutical category
        $pharmaceuticalProductsToExpire = Product::where('expiration', '<=', $sixMonthsFromNow)
            ->where('category_id', '=', $pharmaceuticalCategoryId)
            ->get();
    
        foreach ($pharmaceuticalProductsToExpire as $product) {
            $title = 'Pharmaceutical Item Expiration Alert';
            $message = "Item: $product->p_name will expire in 6 months on: " . date("M j, Y", strtotime($product->expiration));
    
            // Check if a similar notification already exists for this product
            $notificationExists = Notification::where('title', $title)
                ->where('message', $message)
                ->where('date', $product->expiration)
                ->exists();
    
            if (!$notificationExists) {
                // Create a new notification for pharmaceutical product expiration
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
            $message = "Item: $product->p_name has low stock. The Remaining stock: $product->stock";
    
            // Check if a similar notification already exists for this product
            $notificationExists = Notification::where('title', $title)
                ->where('message', $message)
                ->exists();
    
            if (!$notificationExists) {
                // Create a new notification for low stock
                Notification::create([
                    'title' => $title,
                    'message' => $message,
                    'date' => $product->expiration, // Note: You may want to adjust this field based on your logic
                    'time' => $manilaTime->format('g:i A'),
                    'type' => 'supply_officer',
                ]);
            }
        }
    
        return $next($request);
    }
    
}