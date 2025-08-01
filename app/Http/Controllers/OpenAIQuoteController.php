<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class OpenAIQuoteController extends Controller
{
    public function form()
    {
        return view('test-quote');
    }

    public function show($uuid)
    {
        $quote = Quote::where('uuid', $uuid)->firstOrFail();

        return view('quote_result', [
            'quote' => $quote->quote,
            'audioUrl' => asset("{$quote->audio_path}")
        ]);
    }

    public function generate($id)
    {
        $user = User::findOrFail($id);

        $prompt = "Write a motivational quote in 20 to 25 words. It should encourage a positive mindset and subtly relate to someone aged {$user->age_range}, working as a {$user->profession}, and interested in {$user->interests}.";

        // 1. Generate quote
        $openAiResponse = Http::withToken(env('OPENAI_API_KEY'))->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7,
            'max_tokens' => 100,
        ]);

        $quote = trim($openAiResponse['choices'][0]['message']['content'] ?? 'Stay motivated!');

        // 2. Generate audio
        $voiceId = 'uju3wxzG5OhpWcoi3SMy'; // your voice

        $ttsResponse = Http::withHeaders([
            'xi-api-key' => env('ELEVENLABS_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post("https://api.elevenlabs.io/v1/text-to-speech/{$voiceId}", [
                    'text' => $quote,
                    'voice_settings' => [
                        'stability' => 0.5,
                        'similarity_boost' => 0.5,
                        'style' => 0.5,
                        'speed' => 0.9
                    ]
                ]);
        if ($ttsResponse->failed()) {
            return response()->json(['error' => 'Audio generation failed'], 500);
        }

        // 3. Save audio in public/assets/quotes/
        $filename = "quote_{$user->id}_" . time() . ".mp3";
        $relativePath = "assets/quotes/{$filename}";
        $absolutePath = public_path($relativePath);

        // Ensure the directory exists
        if (!file_exists(public_path('assets/quotes'))) {
            mkdir(public_path('assets/quotes'), 0755, true);
        }

        file_put_contents($absolutePath, $ttsResponse->body());

        // 4. Save quote and audio path in DB
        $savedQuote = Quote::create([
            'user_id' => $user->id,
            'quote' => $quote,
            'audio_path' => $relativePath,
        ]);

        return view('quote_result', [
            'quote' => $savedQuote->quote,
            'audioUrl' => asset($relativePath),
        ]);
    }

}
