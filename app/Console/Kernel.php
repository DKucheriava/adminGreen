<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CronRecommendForDay::class,
        Commands\CronRecommendForWeek::class,
        Commands\CronRecommendForMonth::class,
        Commands\LoadPythonFunctions::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('cron:recommend:for:day')->everyMinute();
        $schedule->command('cron:recommend:for:day')->daily();
        $schedule->command('cron:recommend:for:week')->weekly();
        $schedule->command('cron:recommend:for:month')->monthly();
        $schedule->command('python:load-functions')->hourly();
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
