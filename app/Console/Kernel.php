<?php

namespace App\Console;

use App\Console\Commands\SendAnniversaryEmails;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\ApplyProbationAppraisal;
use App\Console\Commands\CheckProbationStatus;
use App\Console\Commands\UpdateEmployeeStatus;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ApplyProbationAppraisal::class,
        UpdateEmployeeStatus::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('apply:probation-appraisal')
            ->everyMinute();

        $schedule->command('employee:update-status')
            ->everyMinute()
            ->then(function () {
                Log::info('employee:update-status was triggered by scheduler.');
            });

        $schedule->call(function () {
            Log::info('✅ Laravel scheduler is running at ' . now());
        })->everyMinute();
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
