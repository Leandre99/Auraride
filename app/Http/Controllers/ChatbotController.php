<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function message(Request $request)
    {
        $apiKey = config('services.gemini.key');
        $systemPrompt = "Tu es l'assistant virtuel d'ATLAS AND CO, service VTC premium basé à Toulouse. Tu réponds uniquement aux questions liées aux services de transport, location de véhicules, tarifs, réservations et informations pratiques. Tu es courtois, concis et professionnel. Si la question sort de ce périmètre, redirige poliment vers contact@atlasandco.fr ou le 0758279237.";
        
        $response = Http::timeout(30)->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $apiKey, [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $request->input('message')]
                    ]
                ]
            ],
            'system_instruction' => [
                'parts' => [
                    ['text' => $systemPrompt]
                ]
            ]
        ]);



        $reply = $response->json('candidates.0.content.parts.0.text');

        return response()->json([
            'reply' => $reply ?? 'Je suis momentanément indisponible, contactez-nous au 0758279237.'
        ]);
    }
}
