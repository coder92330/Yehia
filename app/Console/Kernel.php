<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Check if tourguide has appointment in this month and send notification
        $schedule->call(function () {
            $tourguides = \App\Models\Tourguide::all();
            foreach ($tourguides as $tourguide) {
                $appointments = $tourguide->appointments()->whereMonth('start_at', now()->month)->count();
                if ($appointments <= 0) {
                    $tourguide->notify(new \App\Notifications\TourguideAppointmentNotification());
                }
            }
        })->monthlyOn(1, '00:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
