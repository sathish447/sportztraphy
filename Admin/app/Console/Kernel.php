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
        Commands\UpdateMatch::class,
        Commands\updateMatchPoint::class,
        Commands\updateCredit::class,
        Commands\updatePlayer::class,
        Commands\updateWithdrawRequest::class,
        Commands\updateWinnerPrice::class,
        Commands\UpdateSchedule::class,
        Commands\MatchPlayerUpdate::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('cron:updateSchedule')->dailyAt('01:00');; // Match schedule updated
        //   $schedule->command('cron:updatePlayer')->dailyAt('02:00'); // player's other details like name, participating teams etc.
        //   $schedule->command('cron:updateCredit')->dailyAt('03:00'); // Player's match credit points updated
        $schedule->command('cron:updatematch')->everyFiveMinutes(); // playing 11 and match status updated
        $schedule->command('cron:updateMatchPoint')->everyFiveMinutes(); // Fantasy points for each player updated for every ongoing matches.
        $schedule->command('queue:work')->cron('* * * * * *');
        //$schedule->command('cron:updateWithdrawRequest')->everyThirtyMinutes();
        //$schedule->command('cron:updateWinnerPrice')->everyThirtyMinutes();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}