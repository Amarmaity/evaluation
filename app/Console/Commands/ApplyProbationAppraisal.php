<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;
use App\Models\SuperAddUser;
use App\Models\ApprisalTable;
use Carbon\Carbon;

class ApplyProbationAppraisal extends Command
{

    protected $signature = 'apply:probation-appraisal';

    // ✅ Description shown in artisan list
    protected $description = 'Automatically apply appraisal to users if probation date is today or earlier and within financial year';

    // public function handle()
    // {
    //     log::info('hello');
    //     $appraisal = ApprisalTable::latest()->first();

    //     if (!$appraisal || !$appraisal->company_percentage || !$appraisal->financial_year) {
    //         $this->info('No valid appraisal data found.');
    //         return Command::SUCCESS;
    //     }

    //     [$startYear, $endYear] = explode('-', $appraisal->financial_year);

    //     $startDate = Carbon::createFromDate($startYear, 4, 1)->startOfDay();
    //     $endDate = Carbon::createFromDate($endYear, 3, 31)->endOfDay();
    //     $today = Carbon::today();

    //     $users = SuperAddUser::whereDate('probation_date', '<=', $today)
    //         ->whereNull('company_percentage') // Avoid reapplying
    //         ->get();

    //     $appliedCount = 0;

    //     foreach ($users as $user) {
    //         $probationDate = Carbon::parse($user->probation_date);

    //         if ($probationDate->between($startDate, $endDate)) {
    //             $user->company_percentage = $appraisal->company_percentage;
    //             $user->financial_year = $appraisal->financial_year;
    //             $user->save();
    //             $appliedCount++;

    //              Log::info("✅ Applied appraisal to user ID {$user->id}");
    //         }else{

    //              Log::warning("⏭️ Skipped user ID {$user->id} — probation_date {$probationDate} not in range {$startDate->toDateString()} to {$endDate->toDateString()}");
    //         }
    //     }

    //     Log::info('🎯 The probation appraisal command ran at ' . now());

    //     $this->info("✅ Appraisal applied to {$appliedCount} users.");

    //     return Command::SUCCESS;
    // }





    public function handle()
{
    Log::info('🎯 Running ApplyProbationAppraisal at ' . now());

    $today = Carbon::today();

    // Determine the current financial year
    $currentYear = $today->year;
    if ($today->month < 4) {
        $startYear = $currentYear - 1;
        $endYear = $currentYear;
    } else {
        $startYear = $currentYear;
        $endYear = $currentYear + 1;
    }

    $financialYear = "{$startYear}-{$endYear}";
    Log::info("📅 Targeting financial year: {$financialYear}");

    // Pull appraisal only for current financial year
    $appraisal = ApprisalTable::where('financial_year', $financialYear)->first();

    if (!$appraisal) {
        $this->info("⚠️ No appraisal data found for financial year {$financialYear}.");
        return Command::SUCCESS;
    }

    $startDate = Carbon::createFromDate($startYear, 4, 1)->startOfDay();
    $endDate = Carbon::createFromDate($endYear, 3, 31)->endOfDay();

    $users = SuperAddUser::whereDate('probation_date', '<=', $today)
        ->whereNull('company_percentage')
        ->get();

    $appliedCount = 0;

    foreach ($users as $user) {
        $probationDate = Carbon::parse($user->probation_date);

        if ($probationDate->between($startDate, $endDate)) {
            $user->company_percentage = $appraisal->company_percentage;
            $user->financial_year = $financialYear;
            $user->save();

            Log::info("✅ Applied appraisal to user ID {$user->id}");
            $appliedCount++;
        } else {
            Log::warning("⏭️ Skipped user ID {$user->id} — probation_date {$probationDate->toDateString()} not in range {$startDate->toDateString()} to {$endDate->toDateString()}");
        }
    }

    $this->info("✅ Appraisal applied to {$appliedCount} user(s).");
    return Command::SUCCESS;
}





}
