<?php

namespace App\Http\Middleware;

use App\Models\Product;
use Closure;
use Carbon\Carbon;
use App\Models\Patient;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class DiagnoseAlert
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        // Get unique years for which there are patient records
        $years = Patient::selectRaw('YEAR(created_at) as year')
            ->whereYear('created_at', '<', $currentYear)
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        // Get unique diagnoses
        $diagnoseData = Patient::select('diagnosis')
            ->whereNotNull('diagnosis')
            ->distinct()
            ->pluck('diagnosis')
            ->toArray();

        $notifications = Notification::where('type', 'supply_officer')->orderBy('date', 'desc')->get();

        foreach ($diagnoseData as $diagnose) {

            $currentYearCount = DB::table('patients')
                ->where('diagnosis', $diagnose)
                ->whereYear('created_at', $currentYear)
                ->count();

            foreach ($years as $year) {

                $previousYearCount = DB::table('patients')
                    ->where('diagnosis', $diagnose)
                    ->whereYear('created_at', $year)
                    ->count();

                if ($currentYearCount > $previousYearCount) {

                    $currentTime = Carbon::now()->toTimeString();
                    $currentDate = Carbon::now()->toDateString();
                    $title = "$diagnose yearly alert";
                    $message = "The diagnose: $diagnose is rising from $currentYear to previous year: $year->year, The recommended medicine are:";

                    if ($notifications->isEmpty()) {

                        $products = Product::where(function ($query) use ($diagnose) {
                            $query->orWhere('description', 'LIKE', '%' . $diagnose . '%');
                        })->get();
    
                        foreach ($products as $products) {
                            $message .= $products->p_name;
                        }

                        Notification::create([
                            'title' => $title,
                            'message' => $message,
                            'date' => $currentDate,
                            'time' => $currentTime,
                            'type' => 'supply_officer',
                            'diagnose' => $diagnose,
                        ]);

                    } else {
                        foreach ($notifications as $notification) {

                            $notificationDate = Carbon::parse($notification->date);
                            $year = $notificationDate->year;

                            if (($notification->title != $title && $year != $currentYear) || ($notification->title == $title && $year != $currentYear)) {

                                // Create a new notification if conditions are met'
                                $products = Product::where(function ($query) use ($diagnose) {
                                    $query->orWhere('description', 'LIKE', '%' . $diagnose . '%');
                                })->get();
            
                                foreach ($products as $products) {
                                    $message .= $products->p_name;
                                }

                                Notification::create([
                                    'title' => $title,
                                    'message' => $message,
                                    'date' => $currentDate,
                                    'time' => $currentTime,
                                    'type' => 'supply_officer',
                                    'diagnose' => $diagnose,
                                ]);
                            }
                        }

                    }

                }
            }

            // Initialize variables to track the highest count and month name
            $highestCount = 0;
            $highestMonth = '';

            // Loop through previous months and get counts
            for ($i = 1; $i < $currentMonth; $i++) {
                $month = Carbon::now()->subMonths($i);
                $monthName = $month->format('F Y');

                $count = Patient::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->where('diagnosis', $diagnose)
                    ->count();

                if ($count > $highestCount) {
                    $highestCount = $count;
                    $highestMonth = $monthName;
                }
            }

            // Get the count for the current month
            $currentMonthCount = Patient::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->where('diagnosis', $diagnose)
                ->count();

            // Compare the highest count with the current month count
            if ($currentMonthCount > $highestCount) {



                $currentTime = Carbon::now()->toTimeString();
                $currentDate = Carbon::now()->toDateString();

                $title = "$diagnose monthly alert";
                $message = "The diagnose: $diagnose is rising from previous month: $highestMonth, The recommended medicine are: ";

                if ($notifications->isEmpty()) {
                    // Create a new notification if no notifications exist

                    $products = Product::where(function ($query) use ($diagnose) {
                        $query->orWhere('description', 'LIKE', '%' . $diagnose . '%');
                    })->get();

                    foreach ($products as $products) {
                        $message .= $products->p_name;
                    }

                    Notification::create([
                        'title' => $title,
                        'message' => $message,
                        'date' => $currentDate,
                        'time' => $currentTime,
                        'type' => 'supply_officer',
                        'diagnose' => $diagnose,
                    ]);

                } else {
                    foreach ($notifications as $notification) {

                        $notificationDate = Carbon::parse($notification->date);
                        $month = $notificationDate->month;

                        if (($notification->title != $title && $month != $currentMonth) || ($notification->title == $title && $month != $currentMonth)) {
                            // Create a new notification if conditions are met

                            $products = Product::where(function ($query) use ($diagnose) {
                                $query->orWhere('description', 'LIKE', '%' . $diagnose . '%');
                            })->get();
        
                            foreach ($products as $products) {
                                $message .= $products->p_name;
                            }

                            Notification::create([
                                'title' => $title,
                                'message' => $message,
                                'date' => $currentDate,
                                'time' => $currentTime,
                                'type' => 'supply_officer',
                                'diagnose' => $diagnose,
                            ]);
                        }
                    }

                }
            }

        }

        return $next($request);
    }
}