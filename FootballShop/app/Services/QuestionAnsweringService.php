<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\OpenAIService;

class QuestionAnsweringService
{
    protected OpenAIService $openai;

    // Konstruktor: injektáljuk az OpenAIService példányt
    public function __construct(OpenAIService $openai)
    {
        $this->openai = $openai;
    }

    /**
     * Kérdésre válaszol a PostgreSQL-ben tárolt embeddingek alapján.
     */
    public function answer(string $question): string
    {
        // 1. Lekérdező kérdés embedding generálása az OpenAI API-n keresztül
        $embedding = $this->openai->generateEmbedding($question);

        // 2. Az embedding tömböt átalakítjuk szöveggé, hogy SQL lekérdezésben használható legyen
        $embeddingStr = '[' . implode(',', $embedding) . ']';

        // 3. Hasonlóság alapján lekérjük a 3 legközelebbi dokumentumot a PostgreSQL-ből
        // Az <#> operátor a pgvector hasonlósági kereséshez használt cosine distance
        $results = DB::connection('pgsql')->select("
            SELECT *, embedding <#> CAST(? AS vector) AS distance
            FROM embeddings
            ORDER BY embedding <#> CAST(? AS vector)
            LIMIT 3;
        ", [$embeddingStr, $embeddingStr]);

        // 4. A 3 legrelevánsabb dokumentum tartalmát összefűzzük egy kontextus szöveggé
        $context = collect($results)->pluck('content')->implode("\n---\n");

        // 5. Egy promptot készítünk, amely tartalmazza a kérdést és a kontextust
        $prompt = "A következő kontextus alapján válaszolj a kérdésre: \"$question\"\n\nKontextus:\n$context";

        // 6. Meghívjuk az OpenAI chat API-t, hogy a modell válaszoljon
        return $this->openai->chat($prompt);
    }
}
