<?php

namespace App\Http\Controllers;

use App\Models\CronJobLog;
use App\Models\User;
use App\Models\Quote;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailyQuoteMail;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class QuoteGenerationController extends Controller
{
    public function generate()
    {
        $jobKey = 'daily_quote_generation';

        // ✅ Check if job already ran today
        $alreadyRan = CronJobLog::where('job_key', $jobKey)
            ->where('status', 'success')
            ->whereDate('ran_at', Carbon::today())
            ->exists();

        if ($alreadyRan) {
            Log::info("{$jobKey} has already run today. Skipping execution.");
            return response()->json(['message' => 'Job already ran today. Skipping.']);
        }

        Log::warning("Starting daily quote generation (via web route)");

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

        $themes = [
            'resilience',
            'courage',
            'growth',
            'gratitude',
            'focus',
            'hope',
            'compassion',
            'kindness',
            'self-belief',
            'clarity',
            'perseverance',
            'determination',
            'mindfulness',
            'confidence',
            'balance',
            'strength',
            'calm',
            'positivity',
            'inner peace',
            'ambition',
            'patience',
            'discipline',
            'humility',
            'purpose',
            'vision',
            'love',
            'enthusiasm',
            'faith',
            'bravery',
            'energy'
        ];

        foreach ($users as $user) {
            try {
                $greeting = "Good morning {$user->name},";

                // Build age context only
                $contextParts = [];
                if (!empty($user->age_range)) {
                    $contextParts[] = "aged {$user->age_range}";
                }

                $contextString = $contextParts ? " (optional context: " . implode(', ', $contextParts) . ")" : "";

                // Pick a random theme for this user
                $userTheme = $themes[array_rand($themes)];

                $quoteText = null;
                $maxRetries = 3;

                for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                    // Prompt with random theme
                    $prompt = "Give a short, uplifting motivational quote about {$userTheme} to inspire someone to have a good day. Keep it under 25 words. Avoid being generic.{$contextString}";

                    $openAiResponse = Http::withToken(env('OPENAI_API_KEY'))->post('https://api.openai.com/v1/chat/completions', [
                        'model' => 'gpt-3.5-turbo',
                        'messages' => [
                            ['role' => 'user', 'content' => $prompt]
                        ],
                        'temperature' => 0.85,
                        'max_tokens' => 100,
                    ]);

                    if ($openAiResponse->failed()) {
                        Log::warning("OpenAI Chat failed for user {$user->id}: " . $openAiResponse->body());
                        continue 2; // go to next user
                    }

                    $quoteText = trim($openAiResponse['choices'][0]['message']['content'] ?? '');
                    if (empty($quoteText)) {
                        Log::warning("Empty quote returned for user {$user->id}");
                        continue 2;
                    }

                    // Check similarity with ALL quotes (not just this user’s)
                    $allQuotes = Quote::where('user_id', $user->id)
                        ->pluck('quote')
                        ->map(function ($q) {
                            return trim(Str::after($q, ','));
                        });

                    $isSimilar = $allQuotes->contains(function ($prev) use ($quoteText) {
                        similar_text($prev, $quoteText, $percent);
                        return $percent > 70;
                    });

                    if (!$isSimilar) {
                        break; // Found a good quote
                    }

                    Log::info("Attempt #{$attempt}: Quote too similar for user {$user->id}. Retrying...");
                    $quoteText = null;
                }

                if (!$quoteText) {
                    Log::warning("Max retries reached for user {$user->id}. Skipping.");
                    continue;
                }

                $quote = "{$greeting} {$quoteText}";

                // Generate Audio
                $voiceId = $user->voice?->model_id ?? 'onyx';
                $filename = "quote_{$user->id}_" . time() . ".mp3";
                $relativePath = "assets/quotes/{$filename}";
                $absolutePath = public_path($relativePath);

                if (!file_exists(dirname($absolutePath))) {
                    mkdir(dirname($absolutePath), 0755, true);
                }

                $ttsResponse = Http::withToken(env('OPENAI_API_KEY'))->withOptions([
                    'stream' => true,
                ])->post('https://api.openai.com/v1/audio/speech', [
                            'model' => 'tts-1',
                            'input' => $quote,
                            'voice' => $voiceId,
                            'speed' => 0.95,
                        ]);

                if ($ttsResponse->failed()) {
                    Log::error("OpenAI TTS failed for user {$user->id}: " . $ttsResponse->body());
                    continue;
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

                Log::info("Quote saved and email sent for user: {$user->id}");

            } catch (\Throwable $e) {
                Log::error("Error for user {$user->id}: {$e->getMessage()}", [
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }


        Log::warning("Daily quote generation complete.");

        // ✅ Log job run after completion
        CronJobLog::create([
            'job_key' => $jobKey,
            'status' => 'success',
            'ran_at' => now(),
        ]);

        return response()->json(['message' => 'Quote generation completed']);
    }
}
