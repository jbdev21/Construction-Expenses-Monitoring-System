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
        if (config('app.env') == "production") {
            $schedule->command('queue:work --tries=3 --stop-when-empty')
                ->everyMinute()
                ->withoutOverlapping();
        }

        if (config('app.env') == "local") {
            $schedule->command('queue:prune-failed --hours=48')
                ->withoutOverlapping()
                ->everyMinute();

            $schedule->command('command:update-server-with-new-daily-data')
                ->withoutOverlapping()
                ->twiceDaily(13, 18); // create sql file eveny 1pm and 6pm

            $schedule->command('command:check-sql-json-and-send-to-server')
                ->withoutOverlapping()
                ->everyTwoHours(); // check sql file every 2 hours and send it to server
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
