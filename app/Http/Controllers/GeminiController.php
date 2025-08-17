<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeminiController extends Controller
{
    public function query(Request $request)
    {
        $text = $request->input('text');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-goog-api-key' => env('GEMINI_API_KEY'),
        ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent', [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $text]
                    ]
                ]
            ]
        ]);

        return response()->json($response->json());
    }
}
