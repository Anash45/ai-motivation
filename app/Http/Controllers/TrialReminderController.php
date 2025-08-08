<?php

namespace App\Http\Controllers;

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
        
        Log::info("Starting trial ending reminders for {$today}");

        try {
            // Get users whose trial ends today and aren't subscribed
            $users = User::where('plan_type', 'trial')
                ->whereDate('trial_ends_at', $today)
                ->get();

            if ($users->isEmpty()) {
                Log::info("No trial users ending today found");
                return response()->json([
                    'status' => 'success',
                    'message' => 'No trial users ending today found',
                    'date' => $today
                ]);
            }

            Log::info("Found {$users->count()} users with trial ending today");

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

            Log::info("Completed trial ending reminders", $results);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Trial ending reminders processed',
                'results' => $results,
                'date' => $today
            ]);

        } catch (\Exception $e) {
            Log::error("Trial reminder job failed: " . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Job failed: ' . $e->getMessage(),
                'date' => $today
            ], 500);
        }
    }
}