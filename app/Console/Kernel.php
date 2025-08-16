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
        Commands\FixDatabase::class,
        Commands\BackupDatabase::class,
        Commands\BackupFiles::class,
        Commands\RestoreDatabase::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Daily database backup at 2:00 AM
        $schedule->command('backup:database --type=full --compress')
                 ->daily()
                 ->at('02:00')
                 ->withoutOverlapping()
                 ->runInBackground();
        
        // Weekly files backup on Sunday at 3:00 AM
        $schedule->command('backup:files --include-uploads')
                 ->weekly()
                 ->sundays()
                 ->at('03:00')
                 ->withoutOverlapping()
                 ->runInBackground();
        
        // Monthly full backup (files + logs) on 1st of month at 4:00 AM
        $schedule->command('backup:files --include-uploads --include-logs')
                 ->monthly()
                 ->at('04:00')
                 ->withoutOverlapping()
                 ->runInBackground();
        
        // Log scheduled task executions
        $schedule->command('backup:database --type=schema')
                 ->hourly()
                 ->between('8:00', '18:00') // Only during work hours
                 ->weekdays()
                 ->when(function () {
                     // Only run if there were recent changes
                     return \DB::table('loa_requests')
                               ->where('updated_at', '>=', now()->subHour())
                               ->exists();
                 });
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
