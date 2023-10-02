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
        $currentDate = date('F j, Y');
        $date = Carbon::now();
        $currentMonth = Carbon::now()->month;



        $notifications = Notification::where('type', 'supply_officer')->orderBy('date', 'desc')->get();

        // Calculate the date one month from the current date
        $twoMonthsFromNow = $date->copy()->addMonths(2);

        // Retrieve products with expiration dates exactly two months from now
        $products = Product::whereDate('expiration', $twoMonthsFromNow)->get();

        foreach ($products as $product) {
            $title = 'Expiration Alert';
            $message = "This product: $product->p_name will expire: $product->expiration";

            if ($notifications->isEmpty()) {
                Notification::create([
                    'title' => $title,
                    'message' => $message,
                    'date' => $currentDate,
                    'time' => $currentTime,
                    'type' => 'supply_officer',
                ]);
    
            } else {
                foreach ($notifications as $notification) {
    
                    $notificationDate = Carbon::parse($notification->date);
                    $month = $notificationDate->month;

                    if ($notification->title != $title && $month != $currentMonth){
                        // Create a new notification if conditions are met
                        dd($product);
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
        }

        return $next($request);
    }
}