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
        $oneMonthFromNow = Carbon::now()->addMonth();

        // Retrieve products with expiration dates exactly one month from now
        // $products = Product::whereDate('expiration', $oneMonthFromNow)->get();

        $products = Product::all();

        foreach ($products as $product) {
            $title = 'Expiration Alert';
            $expirationDate = Carbon::parse($product->expiration);

            if ($expirationDate->isPast()) {
                $message = "This product: $product->p_name is expired.";
            } elseif ($expirationDate->isToday()) {
                $message = "This product: $product->p_name expires today.";
            } elseif ($expirationDate->lte($oneMonthFromNow)) {
                $message = "This product: $product->p_name will expire within one month.";
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

        return $next($request);
    }
}