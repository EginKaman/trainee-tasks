<?php

declare(strict_types=1);

namespace App\Console;

use App\Jobs\EmailReport;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->job(new EmailReport())
            ->daily()
            ->timezone('Europe/Kyiv');
        $schedule->command('images:clear')->dailyAt('00:15')
            ->timezone('Europe/Kyiv');
        $schedule->command('users:remove')->dailyAt('00:20')
            ->timezone('Europe/Kyiv')
            ->after(function (): void {
                Artisan::call('db:seed  --class=UserSeeder');
            });
        $schedule->command('products:remove')->dailyAt('00:25')
            ->timezone('Europe/Kyiv')
            ->after(function (): void {
                Artisan::call('db:seed  --class=ProductSeeder');
            });
        $schedule->command('subscriptions:remove')->dailyAt('00:30')
            ->timezone('Europe/Kyiv')
            ->after(function (): void {
                Artisan::call('db:seed  --class=ProductSeeder');
            });
        $schedule->command('backup:run')->daily()
            ->timezone('Europe/Kyiv');
        $schedule->command('telescope:prune --hours=24')->daily();

        $schedule->command('horizon:snapshot')->everyFiveMinutes();

//        $schedule->command('tournaments:users')->everyMinute()
//            ->withoutOverlapping(2);
        $schedule->command('tournaments:finish')->dailyAt('23:59')
            ->after(function (): void {
                Artisan::call('db:seed  --class=TournamentSeeder');
            });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
