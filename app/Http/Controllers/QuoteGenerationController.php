<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Quote;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailyQuoteMail;
use Illuminate\Http\Response;

class QuoteGenerationController extends Controller
{
    public function generate()
    {
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

        foreach ($users as $user) {
            try {
                $greeting = "Good morning {$user->name},";
                // Build optional context, but don’t always use it
                $contextParts = [];

                if (!empty($user->age_range)) {
                    $contextParts[] = "aged {$user->age_range}";
                }
                if (!empty($user->profession)) {
                    $contextParts[] = "a {$user->profession}";
                }
                if (!empty($user->interests)) {
                    $contextParts[] = "interested in {$user->interests}";
                }

                $contextString = $contextParts ? " (optional context: " . implode(', ', $contextParts) . ")" : "";

                // Final prompt
                $prompt = "Give a short, uplifting motivational quote to inspire someone to have a good day. Keep it under 25 words. Avoid being generic.{$contextString}";

                // 1. Generate Quote
                $openAiResponse = Http::withToken(env('OPENAI_API_KEY'))->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 100,
                ]);

                if ($openAiResponse->failed()) {
                    Log::warning("OpenAI Chat failed for user {$user->id}: " . $openAiResponse->body());
                    continue;
                }

                $quoteText = trim($openAiResponse['choices'][0]['message']['content'] ?? '');
                if (empty($quoteText)) {
                    Log::warning("No quote content returned for user {$user->id}");
                    continue;
                }

                $quote = "{$greeting} {$quoteText}";

                // 2. Generate Audio with OpenAI TTS
                $voiceId = $user->voice?->model_id ?? 'onyx'; // ← Use related Voice model's `model_id`

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

                // 3. Save Quote to DB
                $quoteModel = Quote::create([
                    'user_id' => $user->id,
                    'quote' => $quote,
                    'audio_path' => $relativePath,
                ]);

                // 4. Send Email
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
        return response()->json(['message' => 'Quote generation completed']);
    }
}
