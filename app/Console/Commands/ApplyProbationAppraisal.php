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

    public function handle()
    {
        $appraisal = ApprisalTable::latest()->first();

        if (!$appraisal || !$appraisal->company_percentage || !$appraisal->financial_year) {
            $this->info('No valid appraisal data found.');
            return Command::SUCCESS;
        }

        [$startYear, $endYear] = explode('-', $appraisal->financial_year);

        $startDate = Carbon::createFromDate($startYear, 4, 1)->startOfDay();
        $endDate = Carbon::createFromDate($endYear, 3, 31)->endOfDay();
        $today = Carbon::today();

        $users = SuperAddUser::whereDate('probation_date', '<=', $today)
            ->whereNull('company_percentage') // Avoid reapplying
            ->get();

        $appliedCount = 0;

        foreach ($users as $user) {
            $probationDate = Carbon::parse($user->probation_date);

            if ($probationDate->between($startDate, $endDate)) {
                $user->company_percentage = $appraisal->company_percentage;
                $user->financial_year = $appraisal->financial_year;
                $user->save();
                $appliedCount++;
            }
        }

        Log::info('🎯 The probation appraisal command ran at ' . now());

        $this->info("✅ Appraisal applied to {$appliedCount} users.");

        return Command::SUCCESS;
    }
}
