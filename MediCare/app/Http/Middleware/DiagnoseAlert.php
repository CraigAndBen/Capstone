<?php

namespace App\Http\Middleware;

use App\Models\Product;
use Closure;
use Carbon\Carbon;
use App\Models\Diagnose;
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
        $years = Diagnose::selectRaw('YEAR(date) as year')
            ->whereYear('date', '<', $currentYear)
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        // Get unique diagnoses
        $diagnoseData = Diagnose::select('diagnose')
            ->distinct()
            ->pluck('diagnose')
            ->toArray();

        $notifications = Notification::where('type', 'supply_officer')->orderBy('date', 'desc')->get();

        foreach ($diagnoseData as $diagnose) {

            $currentYearCount = DB::table('diagnoses')
                ->where('diagnose', $diagnose)
                ->whereYear('date', $currentYear)
                ->count();

            foreach ($years as $year) {

                $previousYearCount = DB::table('diagnoses')
                    ->where('diagnose', $diagnose)
                    ->whereYear('date', $year)
                    ->count();

                if ($currentYearCount > $previousYearCount) {

                    $currentTime = Carbon::now()->toTimeString();
                    $currentDate = Carbon::now()->toDateString();
                    $title = "$diagnose yearly alert";
                    $message = "The diagnose: $diagnose is rising from $currentYear to previous year: $year->year, The recommended medicine are:";

                    if ($notifications->isEmpty() || !$this->isNotificationDuplicate($notifications, $title, $currentDate)) {

                        $products = Product::where(function ($query) use ($diagnose) {
                            $query->orWhere('description', 'LIKE', '%' . $diagnose . '%');
                        })->get();

                        foreach ($products as $product) {
                            $message .= $product->p_name;
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

            $highestCount = 0;
            $highestMonth = '';

            for ($i = 1; $i < $currentMonth; $i++) {
                $month = Carbon::now()->subMonths($i);
                $monthName = $month->format('F Y');

                $count = Diagnose::whereMonth('date', $month->month)
                    ->whereYear('date', $month->year)
                    ->where('diagnose', $diagnose)
                    ->count();

                if ($count > $highestCount) {
                    $highestCount = $count;
                    $highestMonth = $monthName;
                }
            }

            $currentMonthCount = Diagnose::whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->where('diagnose', $diagnose)
                ->count();

            if ($currentMonthCount > $highestCount) {
                $currentTime = Carbon::now()->toTimeString();
                $currentDate = Carbon::now()->toDateString();

                $title = "$diagnose monthly alert";
                $message = "The diagnose: $diagnose is rising from previous month: $highestMonth, The recommended medicine are: ";

                if ($notifications->isEmpty() || !$this->isNotificationDuplicate($notifications, $title, $currentDate)) {
                    $products = Product::where(function ($query) use ($diagnose) {
                        $query->orWhere('description', 'LIKE', '%' . $diagnose . '%');
                    })->get();

                    foreach ($products as $product) {
                        $message .= $product->p_name;
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

        return $next($request);
    }

    private function isNotificationDuplicate($notifications, $title, $date)
    {
        return $notifications->contains(function ($notification) use ($title, $date) {
            return $notification->title === $title && $notification->date === $date;
        });
    }
}
