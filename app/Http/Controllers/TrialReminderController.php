<?php

namespace App\Http\Controllers;

use App\Models\CronJobLog;
use App\Models\User;
use App\Mail\TrialEndingReminder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TrialReminderController extends Controller
{
    public function sendTrialEndingReminders()
    {
        $today = Carbon::today()->toDateString();
        $jobKey = 'trial_ending_reminders';

        // ✅ Skip if job already ran successfully today
        $alreadyRan = CronJobLog::where('job_key', $jobKey)
            ->where('status', 'success')
            ->whereDate('ran_at', $today)
            ->exists();

        if ($alreadyRan) {
            Log::info("{$jobKey} already ran successfully on {$today}, skipping.");
            return response()->json([
                'status' => 'skipped',
                'message' => 'Job already ran successfully today',
                'date' => $today
            ]);
        }

        Log::info("Starting trial ending reminders for {$today}");

        try {
            $users = User::where('plan_type', 'trial')
                ->whereDate('trial_ends_at', $today)
                ->get();

            if ($users->isEmpty()) {
                Log::info("No trial users ending today found");

                CronJobLog::create([
                    'job_key' => $jobKey,
                    'status' => 'success', // still success, just nothing to send
                    'ran_at' => now(),
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'No trial users ending today found',
                    'date' => $today
                ]);
            }

            $results = [
                'total' => $users->count(),
                'sent' => 0,
                'failed' => 0,
                'failed_emails' => []
            ];

            foreach ($users as $user) {
                try {
                    Mail::to($user->email)->send(new TrialEndingReminder($user));
                    $results['sent']++;
                    Log::info("Sent trial ending reminder to {$user->email} (User ID: {$user->id})");
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['failed_emails'][] = [
                        'email' => $user->email,
                        'error' => $e->getMessage()
                    ];
                    Log::error("Failed to send reminder to {$user->email}: " . $e->getMessage());
                }
            }

            // ✅ Log success/failure in CronJobLog
            CronJobLog::create([
                'job_key' => $jobKey,
                'status' => $results['failed'] > 0 ? 'partial' : 'success',
                'ran_at' => now(),
            ]);

            Log::info("Completed trial ending reminders", $results);

            return response()->json([
                'status' => 'success',
                'message' => 'Trial ending reminders processed',
                'results' => $results,
                'date' => $today
            ]);

        } catch (\Exception $e) {
            // ✅ Log error in CronJobLog
            CronJobLog::create([
                'job_key' => $jobKey,
                'status' => 'failed',
                'ran_at' => now(),
            ]);

            Log::error("Trial reminder job failed: " . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Job failed: ' . $e->getMessage(),
                'date' => $today
            ], 500);
        }
    }

}