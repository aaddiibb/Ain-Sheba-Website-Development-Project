<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $systemPrompt = "You are a legal awareness assistant for Ain Sheba, a government-inspired civic
rights education platform in Bangladesh. Your job is to help ordinary citizens
understand their legal rights in plain, simple language.

You are knowledgeable about:
- Constitutional Rights of Bangladesh (Articles 27-44)
- Bangladesh Labor Act 2006 (worker rights, termination, wages, overtime)
- Tenant Rights (rent agreements, eviction, landlord disputes)
- Consumer Rights Protection Act 2009
- Women's Rights (property, divorce, domestic violence, harassment laws)
- Environmental Law and citizen advocacy

Rules you must follow:
1. Always answer in the same language the user writes in. If they write in Bengali, answer in Bengali. If English, answer in English.
2. Keep answers clear, plain, and easy for a non-lawyer citizen to understand. Avoid heavy legal jargon.
3. Never give specific legal advice for an individual's personal case. Always clarify you are providing general legal awareness only.
4. If the question is completely unrelated to law or legal rights, politely say you can only help with legal awareness topics.
5. Keep answers concise — ideally 3 to 5 sentences. Do not write essays.
6. Always end every single response with this exact sentence on a new line: 'For personal legal advice tailored to your situation, we recommend booking a consultation with one of our certified lawyers on Ain Sheba.'
7. Never recommend external legal services, other websites, or third-party platforms.";

        $apiKey = env('GEMINI_API_KEY');
        $model = env('GEMINI_MODEL', 'gemini-flash-latest');

        if (empty($apiKey)) {
            return response()->json(['reply' => 'The chatbot is not configured yet. Please contact the administrator.'], 503);
        }

        $client = new Client(['timeout' => 15]);
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        try {
            $response = $client->post($url, [
                'json' => [
                    'system_instruction' => [
                        'parts' => [['text' => $systemPrompt]],
                    ],
                    'contents' => [
                        ['parts' => [['text' => $request->message]]],
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => 600,
                        'temperature' => 0.4,
                        'topP' => 0.9,
                        'thinkingConfig' => ['thinkingBudget' => 0],
                    ],
                    'safetySettings' => [
                        ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                        ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                    ],
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (empty($reply)) {
                return response()->json(['reply' => 'I could not generate a response. Please try rephrasing your question.']);
            }

            return response()->json(['reply' => $reply]);
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            Log::warning('Gemini chatbot connect failed: ' . $e->getMessage());
            return response()->json(['reply' => 'Could not connect to the AI service. Please check your internet connection and try again.'], 503);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            Log::warning('Gemini chatbot client error: ' . $e->getResponse()->getBody());

            if ($e->getResponse()->getStatusCode() === 429) {
                return response()->json(['reply' => 'The assistant is receiving too many requests right now. Please wait a moment and try again.']);
            }

            return response()->json(['reply' => 'The AI service returned an error. Please try again shortly.']);
        } catch (Exception $e) {
            Log::warning('Gemini chatbot unexpected error: ' . $e->getMessage());
            return response()->json(['reply' => 'Something went wrong. Please try again.'], 500);
        }
    }
}
