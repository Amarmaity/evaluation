<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SuperAddUser;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProbationCompletedMail;

class CheckProbationStatus extends Command
{
    protected $signature = 'probation:check';
    protected $description = 'Check and update probation status for users, and send email if completed.';

    public function handle()
    {
        $users = SuperAddUser::where('employee_status', 'Employee')
        ->whereNull('probation_email_sent_at')
        ->get();

    foreach ($users as $user) {
        Mail::to($user->email)->send(new ProbationCompletedMail($user));
        $user->probation_email_sent_at = now(); // Mark as mailed
        $user->save();

        $this->info("✅ Mail sent to {$user->email}");
    }

    return Command::SUCCESS;
    }
}
