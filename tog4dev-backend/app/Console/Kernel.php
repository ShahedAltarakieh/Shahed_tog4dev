<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {        
        $schedule->command('app:process-send-prof-emails')->withoutOverlapping()->everyMinute();
        $schedule->command('app:process-send-reminder-subscriptions')->withoutOverlapping()->daily();
        $schedule->command('app:process-renew-subscriptions')->withoutOverlapping()->daily();
        // $schedule->command("app:process-fetar-campaign")->hourlyAt(15);
        $schedule->command('app:retry-odoo-payments')->withoutOverlapping()->twiceDaily(0, 12);
        $schedule->command('app:check-network-payments')->withoutOverlapping()->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
