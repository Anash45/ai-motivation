<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\TestEmail;
use Exception;

class TestEmailController extends Controller
{
    public function testEmail()
    {
        $recipient = "f4futuretech@gmail.com";
        $startTime = microtime(true);
        
        try {
            Log::channel('emails')->info('Starting email test', [
                'recipient' => $recipient,
                'environment' => app()->environment(),
                'mail_driver' => config('mail.default')
            ]);

            // Test basic email sending
            Mail::raw('This is a test email from the system', function ($message) use ($recipient) {
                $message->to($recipient)
                        ->subject('System Email Test - Plain Text');
            });

            // Test Mailable class
            Mail::to($recipient)->send(new TestEmail());

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::channel('emails')->info('Email test completed successfully', [
                'execution_time_ms' => $executionTime,
                'mail_config' => [
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'encryption' => config('mail.mailers.smtp.encryption'),
                    'from_address' => config('mail.from.address')
                ]
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Test emails sent successfully',
                'details' => [
                    'recipient' => $recipient,
                    'time_taken' => $executionTime . 'ms',
                    'mail_driver' => config('mail.default')
                ]
            ]);

        } catch (Exception $e) {
            $errorTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::channel('emails')->error('Email test failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'time_taken' => $errorTime . 'ms',
                'mail_config' => config('mail'),
                'php_version' => PHP_VERSION
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Email test failed',
                'error' => $e->getMessage(),
                'details' => [
                    'mail_driver' => config('mail.default'),
                    'host' => config('mail.mailers.smtp.host'),
                    'php_version' => PHP_VERSION
                ]
            ], 500);
        }
    }
}