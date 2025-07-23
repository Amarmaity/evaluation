<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SuperAddUser;
use Illuminate\Support\Facades\Mail;
use App\Mail\AnniversaryEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendAnniversaryEmails extends Command
{
    protected $signature = 'email:send-anniversaries';
    protected $description = 'Send anniversary emails to employees who completed 1 year';

    public function handle()
    {
        $today = Carbon::today();

        // $users = SuperAddUser::whereDate('probation_date', $today->copy()->subYear())->get();
        //   $users = SuperAddUser::whereDate('dob', $today->copy()->subYear())->get();
            $users = SuperAddUser::where('status', 1)->whereRaw("DATE(dob + INTERVAL 1 YEAR) = ?", [$today->toDateString()])->get();

        if ($users->isEmpty()) {
            $this->info("No anniversaries today.");
            Log::info('AnniversaryEmail: No anniversaries found on ' . $today->toDateString());
            return;
        }

        foreach ($users as $user) {
            try {
                Mail::to($user->email)->send(new AnniversaryEmail($user));
                $this->info("Email sent to: {$user->email}");
                Log::info("AnniversaryEmail: Sent to user ID {$user->id}, Email: {$user->email}");
            } catch (\Exception $e) {
                $this->error("Failed to send email to: {$user->email}");
                Log::error("AnniversaryEmail: Failed for user ID {$user->id}, Email: {$user->email}, Error: " . $e->getMessage());
            }
        }
    }
}
