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
        $currentTime = Carbon::now()->toTimeString();
        $currentDate = date('M j, Y');
        $currentMonth = Carbon::now()->month;
    
        $processedNotifications = []; // Array to keep track of processed notifications
    
        $notifications = Notification::where('type', 'supply_officer')->orderBy('date', 'desc')->get();
    
        // Calculate the date one month from the current date
        $twoMonthsFromNow = Carbon::now()->addMonths(2)->format('Y-m-d');
    
        // Retrieve products with expiration dates exactly two months from now
        $productsToExpire = Product::whereDate('expiration', $twoMonthsFromNow)->get();
    
        foreach ($productsToExpire as $product) {
            $title = 'Expiration Alert';
            $message = "Product: $product->p_name will expire on: $product->expiration";
    
            // Check if a similar notification has been processed in this request
            if (in_array("$title:$currentMonth", $processedNotifications)) {
                continue;
            }
    
            // Check if a similar notification already exists in the database
            $notificationExists = false;
    
            foreach ($notifications as $notification) {
                if ($notification->title === $title && Carbon::parse($notification->date)->month === $currentMonth) {
                    $notificationExists = true;
                    break;
                }
            }
    
            if (!$notificationExists) {
                // Create a new notification
                Notification::create([
                    'title' => $title,
                    'message' => $message,
                    'date' => $currentDate,
                    'time' => $currentTime,
                    'type' => 'supply_officer',
                ]);
            }
    
            // Add this notification to the processed notifications array
            $processedNotifications[] = "$title:$currentMonth";
        }
    
        // Get products with stock below 100
        $lowStockProducts = Product::where('stock', '<', 100)->get();
    
        foreach ($lowStockProducts as $product) {
            $title = 'Low Stock Alert';
            $message = "Product: $product->p_name has low stock. Remaining stock: $product->stock";
    
            // Check if a similar notification has been processed in this request
            if (in_array("$title:$currentMonth", $processedNotifications)) {
                continue;
            }
    
            // Check if a similar notification already exists in the database
            $notificationExists = false;
    
            foreach ($notifications as $notification) {
                if ($notification->title === $title && Carbon::parse($notification->date)->month === $currentMonth) {
                    $notificationExists = true;
                    break;
                }
            }
    
            if (!$notificationExists) {
                // Create a new notification
                Notification::create([
                    'title' => $title,
                    'message' => $message,
                    'date' => $currentDate,
                    'time' => $currentTime,
                    'type' => 'supply_officer',
                ]);
            }
    
            // Add this notification to the processed notifications array
            $processedNotifications[] = "$title:$currentMonth";
        }
    
        return $next($request);
    }
    

}