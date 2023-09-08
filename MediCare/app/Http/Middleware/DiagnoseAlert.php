<?php

namespace App\Http\Middleware;

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

        $years = Patient::selectRaw('YEAR(created_at) as year')
            ->whereYear('created_at', '<', $currentYear)
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        $diagnoseData = Patient::select('diagnosis')
            ->whereNotNull('diagnosis')
            ->distinct()
            ->pluck('diagnosis')
            ->toArray();

        $notifications = Notification::where('type', 'admin')->orderBy('date', 'desc')->get();

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
                    $title = $diagnose . ' is rising.';
                    $message = 'The diagnose: ' . $diagnose . ' is rising from previous year: ' . $year->year;

                    if ($notifications->isEmpty()) {
                        Notification::create([
                            'title' => $title,
                            'message' => $message,
                            'date' => $currentDate,
                            'time' => $currentTime,
                            'type' => 'admin',
                            'diagnose' => $diagnose,
                        ]);

                    } else {
                        foreach ($notifications as $notification) {

                            if ($notification->date != $currentDate && $notification->diagnose != $diagnose) {

                                Notification::create([
                                    'title' => $title,
                                    'message' => $message,
                                    'date' => $currentDate,
                                    'time' => $currentTime,
                                    'type' => 'admin',
                                    'diagnose' => $diagnose,
                                ]);
                            }
                        }

                    }

                }
            }

            $currentMonthCount = Patient::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('diagnosis', $diagnose)
            ->count();

            // Loop through each month of the year
            for ($month = 1; $month < $currentMonth; $month++) {
                // Retrieve diagnosis data for the current month
                $previousMonthCount = Patient::whereMonth('created_at', $month)
                    ->whereYear('created_at', $currentYear)
                    ->where('diagnosis', $diagnose)
                    ->count();

                if($currentMonthCount > $previousMonthCount) {

                    $monthName = Carbon::createFromDate($currentYear, $month)->monthName;
                    $currentTime = Carbon::now()->toTimeString();
                    $currentDate = Carbon::now()->toDateString();
                    $title = $diagnose . ' is rising.';
                    $message = 'The diagnose: ' . $diagnose . ' is rising from previous month: ' . $monthName;

                    if ($notifications->isEmpty()) {
                        Notification::create([
                            'title' => $title,
                            'message' => $message,
                            'date' => $currentDate,
                            'time' => $currentTime,
                            'type' => 'admin',
                            'diagnose' => $diagnose,
                        ]);

                    } else {
                        foreach ($notifications as $notification) {

                            if ($notification->date != $currentDate && $notification->diagnose != $diagnose) {

                                Notification::create([
                                    'title' => $title,
                                    'message' => $message,
                                    'date' => $currentDate,
                                    'time' => $currentTime,
                                    'type' => 'admin',
                                    'diagnose' => $diagnose,
                                ]);
                            }
                        }

                    }
                }
            }

        }

        return $next($request);
    }
}