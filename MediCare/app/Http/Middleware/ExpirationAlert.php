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

        $notifications = Notification::where('type', 'supply_officer')->orderBy('date', 'desc')->get();

        // Calculate the date three months from the current date
        $threeMonthsFromNow = Carbon::now()->addMonths(3)->format('Y-m-d');

        // Retrieve products with expiration dates exactly three months from now
        $productsToExpire = Product::whereDate('expiration', $threeMonthsFromNow)->get();

        foreach ($productsToExpire as $product) {
            $title = 'Expiration Alert';
            $message = "Product: $product->p_name will expire on: $product->expiration";

            foreach ($products as $product) {
                $title = 'Expiration Alert';
                $expirationDate = Carbon::parse($product->expiration);
                $message = '';

                foreach ($notifications as $notification) {
                    if ($notification->title === $title && Carbon::parse($notification->date)->month === $currentMonth) {
                        $notificationExists = true;
                        break;
                    }

                    foreach ($notifications as $notification) {
                        $notificationDate = Carbon::parse($notification->date);
                        $month = $notificationDate->month;

                        // Check if a notification with the same title and message exists
                        $existingNotification = Notification::where('title', $title)
                            ->where('message', $message)
                            ->first();

                        if (!$existingNotification) {
                            Notification::create([
                                'title' => $title,
                                'message' => $message,
                                'date' => $currentDate,
                                'time' => $currentTime,
                                'type' => 'supply_officer',
                            ]);
                        }
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
            }

            // Get products with stock below 100
            $lowStockProducts = Product::where('stock', '<', 100)->get();

            foreach ($lowStockProducts as $product) {
                $title = 'Low Stock Alert';
                $message = "Product: $product->p_name has low stock. Remaining stock: $product->stock";

                // Check if a similar notification already exists and if it's a new month
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
            }

            return $next($request);
        }

    }
}