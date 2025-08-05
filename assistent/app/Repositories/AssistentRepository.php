<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Http;

class AssistentRepository
{
   public function ask(string $prompt): string
{
    $type = $this->classifyPrompt($prompt);
    $fullPrompt =  $prompt;

    $response = Http::timeout(60)->post('http://localhost:11434/api/generate', [
        'model' => 'gemma:2b',
        'prompt' => $fullPrompt,
        'stream' => false
    ]);

    if ($response->failed()) {
        return 'No pude generar una respuesta en este momento.';
    }

    $text = $response->json('response') ?? 'Respuesta vacía.';

    $cleaned = preg_replace('/<think>.*?<\/think>/s', '', $text);
    return trim($cleaned);
}



    private function classifyPrompt(string $prompt): string
{
    $prompt = strtolower($prompt);

    if (preg_match('/(cómo|qué es|cuál es|para qué|por qué)/', $prompt)) {
        return 'informativa';
    }

    if (preg_match('/(me siento|estoy triste|feliz|emocionado|tengo miedo)/', $prompt)) {
        return 'emocional';
    }

    if (preg_match('/(configurar|instalar|error|código|programar|api|servidor)/', $prompt)) {
        return 'técnica';
    }

    return 'general';
}


private function getInstructionForType(string $type): string
{
    return match ($type) {
        'informativa' => 'Responde de forma clara y breve. Usa máximo tres oraciones. No excedas 50 caracteres. Sé directo.',
        'emocional'   => 'Responde con empatía y calidez. Sé breve pero cercano. No excedas 50 caracteres. Evita extenderte.',
        'técnica'     => 'Responde con precisión técnica. Sé conciso y directo. No excedas 50 caracteres. No des explicaciones largas.',
        default       => 'Responde de forma breve y útil. No excedas 50 caracteres. Sé concreto y directo.'
    };
}


}
