<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    private string $systemPrompt = 'Sen LiftAcademy asistanısın. Vinç operatörü LMS platformu: video dersler, sınavlar (%70+ geçme), 5 kademeli sertifikasyon. Kısa ve net yanıt ver (max 2-3 cümle). Kullanıcının dilinde yaz.';

    public function send(Request $request)
    {
        $request->validate([
            'message'  => 'required|string|max:300',
            'history'  => 'array|max:6',
        ]);

        $apiKey = config('services.deepseek.key');

        if (empty($apiKey)) {
            return response()->json([
                'reply' => 'Yapay zeka henüz aktif değil. Yakında hizmete girecek! 🚧',
            ]);
        }

        // Mesaj geçmişini oluştur
        $messages = [['role' => 'system', 'content' => $this->systemPrompt]];

        foreach ($request->input('history', []) as $h) {
            if (isset($h['role'], $h['content'])) {
                $messages[] = [
                    'role'    => in_array($h['role'], ['user', 'assistant']) ? $h['role'] : 'user',
                    'content' => substr($h['content'], 0, 500),
                ];
            }
        }

        $messages[] = ['role' => 'user', 'content' => $request->input('message')];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type'  => 'application/json',
        ])->timeout(30)->post('https://api.deepseek.com/v1/chat/completions', [
            'model'       => 'deepseek-chat',
            'messages'    => $messages,
            'max_tokens'  => 150,
            'temperature' => 0.5,
        ]);

        if ($response->failed()) {
            return response()->json([
                'reply' => 'Bir hata oluştu, lütfen tekrar deneyin.',
            ], 500);
        }

        $reply = $response->json('choices.0.message.content', 'Yanıt alınamadı.');

        return response()->json(['reply' => trim($reply)]);
    }
}
