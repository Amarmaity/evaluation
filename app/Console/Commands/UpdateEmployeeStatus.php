<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SuperAddUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UpdateEmployeeStatus extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'employee:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
{
   

    Log::info('✅ Running UpdateEmployeeStatus at ' . now());

    $today = Carbon::today()->toDateString();
    Log::info("🔍 Checking for users with probation_date = {$today}");

    $users = SuperAddUser::whereDate('probation_date', '<=', $today)->get();

    Log::info("🧑‍💻 Found {$users->count()} users");

    foreach ($users as $user) {
        $user->employee_status = 'Employee';
        $user->save();

        Log::info("✅ Updated user ID {$user->id} to status 'Employee'");
    }

    $this->info("Updated {$users->count()} users.");
}

}
