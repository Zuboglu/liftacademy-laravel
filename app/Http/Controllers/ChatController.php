<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    private string $systemPrompt = <<<'PROMPT'
Sen LiftAcademy'nin yapay zeka asistanısın. LiftAcademy, vinç operatörlerini yetiştiren bir kurumsal LMS (öğrenme yönetim sistemi) platformudur.

Görevin:
- Kullanıcıların kurslar, sınavlar, sertifikalar ve platform kullanımı hakkındaki sorularını yanıtlamak
- Vinç operatörü eğitimi, İSG (iş sağlığı ve güvenliği), vinç tipleri ve güvenli operasyon hakkında bilgi vermek
- Kayıt, giriş ve platform gezintisi konularında yardımcı olmak
- Kısa, net ve yardımcı cevaplar vermek (maks. 3-4 cümle)
- Türkçe, İngilizce veya kullanıcının yazdığı dilde yanıt vermek

Platform özellikleri:
- HD video dersler, çoktan seçmeli sınavlar, otomatik sertifikasyon
- 5 kademeli sertifikasyon: Junior Operatör → Operatör → Senior Operatör → Supervisor → Trainer
- Kurslar: İSG & Güvenlik, Vinç Türleri, Operasyon, Teknik, Risk Yönetimi, Sertifikasyon
- Sınav geçme notu: %70-90

Platforma özel olmayan sorular için nazikçe konuyu platforma yönlendir.
PROMPT;

    public function send(Request $request)
    {
        $request->validate([
            'message'  => 'required|string|max:500',
            'history'  => 'array|max:20',
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
                    'content' => substr($h['content'], 0, 1000),
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
            'max_tokens'  => 300,
            'temperature' => 0.7,
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
