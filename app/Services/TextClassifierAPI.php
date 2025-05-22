<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TextClassifierAPI
{
    public function classify(string $text): string
    {
        $response = Http::post('http://localhost:8000/classify', [
            'text' => $text
        ]);

        if ($response->successful()) {
            return $response->json()['category'] ?? 'Uncategorized';
        }

        return 'Uncategorized';
    }
}
