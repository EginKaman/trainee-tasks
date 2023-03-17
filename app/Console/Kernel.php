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
        $schedule->command('images:clear')->daily()
            ->timezone('Europe/Kyiv');
        $schedule->command('users:remove')->dailyAt('01:00')
            ->timezone('Europe/Kyiv')
            ->after(function (): void {
                Artisan::call('db:seed  --class=UserSeeder');
            });
        $schedule->command('products:remove')->dailyAt('01:00')
            ->timezone('Europe/Kyiv')
            ->after(function (): void {
                Artisan::call('db:seed  --class=ProductSeeder');
            });
        $schedule->command('backup:run')->daily()
            ->timezone('Europe/Kyiv');
        $schedule->command('telescope:prune --hours=24')->daily();
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
