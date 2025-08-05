<?php
namespace App\Http\Controllers\Assistent;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\AssistentRepository;
use Illuminate\Support\Facades\Http;


class AssistentController extends Controller
{
    public function sendMessage(Request $request, AssistentRepository $assistent)
    {
        $prompt = $request->input('prompt');

        if (!$prompt) {
            return response()->json(['reply' => 'No se recibió ningún texto.'], 400);
        }

        $reply = $assistent->ask($prompt);

        return response()->json(['reply' => $reply]);
    }






 public function synthesize(Request $request)
{
    $text = $request->input('text');

    if (!$text) {
        return response('Texto vacío', 400);
    }

    $voiceId = 'EXAVITQu4vr4xnSDxMaL';
    $apiKey = 'sk_f434f97affcba9f297c81d057661079f27946f2a39c5899b';

    try {
        $response = Http::timeout(30)->withHeaders([
            'xi-api-key' => $apiKey,
            'Content-Type' => 'application/json'
        ])->post("https://api.elevenlabs.io/v1/text-to-speech/{$voiceId}", [
            'text' => $text,
            'voice_settings' => [
                'stability' => 0.6,
                'similarity_boost' => 0.8,
                'speed' => 1
            ],
            'output_format' => 'mp3'
        ]);

        if ($response->failed()) {
            return response()->json([
                'error' => 'Error al generar audio',
                'details' => $response->body()
            ], 500);
        }

        return response($response->body())->header('Content-Type', 'audio/mpeg');
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Excepción al generar audio',
            'message' => $e->getMessage()
        ], 500);
    }
}



public function speakLocal(Request $request)
{
    $text = $request->input('text');

    if (!$text) {
        return response('Texto vacío', 400);
    }

    $escapedText = escapeshellarg($text);
    $scriptPath = base_path('resources/python/speak.py');
    $outputFile = public_path('output.wav');

    try {
        exec("python3 $scriptPath $escapedText 2>&1", $output, $status);

        if ($status !== 0 || !file_exists($outputFile)) {
            return response()->json([
                'error' => 'Error al generar audio con TTS local',
                'details' => $output
            ], 500);
        }

        return response()->file($outputFile, [
            'Content-Type' => 'audio/wav'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Excepción al ejecutar TTS local',
            'message' => $e->getMessage()
        ], 500);
    }
}


}
