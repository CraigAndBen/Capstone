<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AppoitmentMonitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Set the timezone to Asia/Manila
        date_default_timezone_set('Asia/Manila');

        $appointments = DB::table('appointments')
            ->where('status', 'pending')
            ->get();

        foreach ($appointments as $appointment) {

            $dateToCombine = $appointment->appointment_date; // Format: YYYY-MM-DD
            $timeToCombine = $appointment->appointment_time; // Format: H:i A

            // Create Carbon instances for date and time
            $date = Carbon::parse($dateToCombine, 'Asia/Manila');
            $time = Carbon::parse($timeToCombine, 'Asia/Manila');

            $combinedDateTime = $date->setTime($time->hour, $time->minute, $time->second);
            $carbonDateTimeToCheck = Carbon::parse($combinedDateTime, 'Asia/Manila');

            if ($carbonDateTimeToCheck->isPast()) {

                $appoint = Appointment::findOrFail($appointment->id);

                $appoint->status = 'unavailable';
                $appoint->save();

                $currentTime = Carbon::now()->toTimeString();
                $currentDate = Carbon::now()->toDateString();
                $message = ' Your appointment that has a type of ' . $appointment->appointment_type . ' that dated ' . $appointment->appointment_date . ' and timed ' . $appointment->appointment_time . ' is unavailable.';

                Notification::create([
                    'account_id' => $appointment->account_id,
                    'title' => 'Appointment Unavailable',
                    'message' => $message,
                    'date' => $currentDate,
                    'time' => $currentTime,
                ]);
            }
        }

        return $next($request);
    }
}