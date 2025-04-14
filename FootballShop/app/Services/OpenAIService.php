<?php

namespace App\Services;

use OpenAI\Client;
use OpenAI;

class OpenAIService
{
    // OpenAI kliens példány
    protected Client $client;

    /**
     * Konstruktor - inicializálja az OpenAI klienst az .env fájlból származó API kulccsal
     */
    public function __construct()
    {
        $this->client = OpenAI::client(env('OPENAI_API_KEY'));
    }

    /**
     * Embedding vektor generálása egy adott szövegre
     *
     * @param string $text A szöveg, amelyre a beágyazást (embeddinget) szeretnénk létrehozni
     * @return array A beágyazott (vektoros) reprezentáció
     */
    public function generateEmbedding(string $text): array
    {
        $response = $this->client->embeddings()->create([
            'model' => 'text-embedding-3-small', // OpenAI beágyazó modell
            'input' => $text, // A szöveg, amelyet vektorosítunk
        ]);

        // Az első (és egyetlen) embedding vektor visszaadása
        return $response->embeddings[0]->embedding;
    }

    /**
     * Chat válasz generálása egy prompt alapján
     *
     * @param string $prompt A felhasználó által megadott kérdés vagy utasítás
     * @return string A nyelvi modell által generált válasz
     */
    public function chat(string $prompt): string
    {
        $response = $this->client->chat()->create([
            'model' => 'gpt-3.5-turbo', // ChatGPT modell
            'messages' => [
                ['role' => 'system', 'content' => 'Te egy segítőkész asszisztens vagy.'], // rendszer szerep
                ['role' => 'user', 'content' => $prompt], // felhasználói kérdés
            ],
        ]);

        // A modell által generált válasz szövegének visszaadása
        return $response->choices[0]->message->content;
    }
}
