<?php

namespace App\Services;

use OpenAI\Client;
use OpenAI;

// Az OpenAI API-val való kommunikációt kezelő szolgáltatás
class OpenAIService
{
    // OpenAI kliens példány
    protected Client $client;

    /**
     * Konstruktor — inicializálja az OpenAI klienst az .env fájlból származó API kulccsal
     */
    public function __construct()
    {
        $this->client = OpenAI::client(env('OPENAI_API_KEY'));
    }

    /**
     * Chat-válasz generálása egy szöveges prompt alapján
     *
     * Ez a metódus egy rövid üzenetváltást szimulál a felhasználó és a modell között,
     * és visszaadja a válaszként generált szöveget.
     *
     * @param string $prompt A felhasználó által megadott kérdés vagy utasítás
     * @return string A modell által generált válasz szöveg
     */
    public function chat(string $prompt): string
    {
        $response = $this->client->chat()->create([
            'model' => 'gpt-3.5-turbo', // Az OpenAI ChatGPT modell
            'messages' => [
                ['role' => 'system', 'content' => 'Te egy segítőkész asszisztens vagy.'],
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        // A válasz első változatának szöveges tartalma
        return $response->choices[0]->message->content;
    }
}
