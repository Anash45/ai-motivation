<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Quote;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailyQuoteMail;

class GenerateDailyQuotes extends Command
{
    protected $signature = 'quotes:generate-daily';
    protected $description = 'Generate daily motivational messages and audio for subscribed/trial users';

    public function handle()
    {
        $this->info("Starting daily quote generation...");
        Log::warning("Starting daily quote generation...");


        $users = User::where(function ($query) {
            $query->where('is_subscribed', true)
                ->orWhere(function ($q) {
                    $q->where('plan_type', 'trial')
                        ->where('trial_ends_at', '>', now());
                })
                ->orWhere(function ($q) {
                    $q->where('is_subscribed', false)
                        ->whereNotNull('subscription_ends_at')
                        ->where('subscription_ends_at', '>', now());
                });
        })->get();

        foreach ($users as $user) {
            try {
                $greeting = "Good morning {$user->name},";
                $optionalDetails = [];

                if (!empty($user->age_range)) {
                    $optionalDetails[] = "someone aged {$user->age_range}";
                }
                if (!empty($user->profession)) {
                    $optionalDetails[] = "working as a {$user->profession}";
                }
                if (!empty($user->interests)) {
                    $optionalDetails[] = "interested in {$user->interests}";
                }

                $extra = $optionalDetails ? " This is for " . implode(', ', $optionalDetails) . "." : "";

                $prompt = "Here's your motivational quote for today. Keep it under 25 words.{$extra}";

                // Generate Quote
                $openAiResponse = Http::withToken(env('OPENAI_API_KEY'))->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 100,
                ]);

                $quote = trim($openAiResponse['choices'][0]['message']['content'] ?? null);
                if (!$quote) {
                    Log::warning("No quote returned for user {$user->id}");
                    continue;
                }

                $quote = $greeting . " " . $quote;

                // Generate Audio
                $voiceId = 'uju3wxzG5OhpWcoi3SMy';
                $ttsResponse = Http::withHeaders([
                    'xi-api-key' => env('ELEVENLABS_API_KEY'),
                    'Content-Type' => 'application/json',
                ])->post("https://api.elevenlabs.io/v1/text-to-speech/{$voiceId}", [
                            'text' => $quote,
                            'voice_settings' => [
                                'stability' => 0.5,
                                'similarity_boost' => 0.5,
                                'style' => 0.5,
                                'speed' => 0.9,
                            ]
                        ]);

                if ($ttsResponse->failed()) {
                    Log::error("Audio failed for user {$user->id}");
                    continue;
                }

                // Save File
                $filename = "quote_{$user->id}_" . time() . ".mp3";
                $relativePath = "assets/quotes/{$filename}";
                $absolutePath = public_path($relativePath);

                if (!file_exists(public_path('assets/quotes'))) {
                    mkdir(public_path('assets/quotes'), 0755, true);
                }

                file_put_contents($absolutePath, $ttsResponse->body());

                // Save to DB
                $quoteModel = Quote::create([
                    'user_id' => $user->id,
                    'quote' => $quote,
                    'audio_path' => $relativePath,
                ]);

                // Send Email
                Mail::to($user->email)->send(new DailyQuoteMail(
                    $user,
                    $quote,
                    url(env("APP_PROD_URL") . "/quote/{$quoteModel->uuid}"),
                    $absolutePath
                ));


                $this->info("Quote saved and email sent for user: {$user->id}");
                Log::warning("Quote saved and email sent for user: {$user->id}");

            } catch (\Throwable $e) {
                Log::error("Error for user {$user->id}: " . $e->getMessage());
            }
        }

        $this->info("Daily quote generation complete.");
        Log::warning("Daily quote generation complete.");
    }
}