<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\User;
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

        $twelveHoursAgo = Carbon::now('Asia/Manila')->subHours(12);
        $appointments = DB::table('appointments')
            ->where('status', 'pending')
            ->where('appointment_date', '<', $twelveHoursAgo)
            ->get();

        foreach ($appointments as $appointment) {
            $appoint = Appointment::findOrFail($appointment->id);

            $appoint->status = 'unavailable';
            $appoint->save();

            $currentTime = Carbon::now()->toTimeString();
            $currentDate = Carbon::now()->toDateString();
            $user = User::where('id', $appointment->account_id)->first();

            $message = ' Your appointment that has a type of ' . $appointment->appointment_type . ' that dated ' . $appointment->appointment_date . ' and timed ' . $appointment->appointment_time . ' is unavailable due to non-confirmation by the doctor within 12 hours.';

            Notification::create([
                'account_id' => $appointment->account_id,
                'title' => 'Appointment Unavailable',
                'message' => $message,
                'date' => $currentDate,
                'time' => $currentTime,
                'type' => 'user'
            ]);

            $message = ucwords($user->first_name) . ' ' . ucwords($user->last_name) . ' appointment that has a type of ' . $appointment->appointment_type . ' that dated ' . $appointment->appointment_date . ' and timed ' . $appointment->appointment_time . ' is unavailable due to non-confirmation by the doctor within 12 hours.';

            Notification::create([
                'account_id' => $appointment->account_id,
                'title' => 'Appointment Unavailable',
                'message' => $message,
                'date' => $currentDate,
                'time' => $currentTime,
                'type' => 'doctor'
            ]);
        }

        return $next($request);
    }
}