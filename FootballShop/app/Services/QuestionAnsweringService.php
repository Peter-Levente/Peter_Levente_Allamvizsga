<?php

namespace App\Services;

use App\VectorStores\PgsqlVectorStore;
use LLPhant\Embeddings\EmbeddingGenerator\OpenAI\OpenAI3SmallEmbeddingGenerator;

// Kérdés-válasz szolgáltatás, amely embedding alapú hasonlóságkeresést és generatív választ használ
class QuestionAnsweringService
{
    protected OpenAIService $openai;

    /**
     * Konstruktor — OpenAIService példány injektálása
     *
     * @param OpenAIService $openai A chat és embedding szolgáltatás
     */
    public function __construct(OpenAIService $openai)
    {
        $this->openai = $openai;
    }

    /**
     * Válasz generálása egy vásárlói kérdésre releváns dokumentumok alapján
     *
     * 1. Embedding készül a kérdésből
     * 2. Vektoros keresés hasonló szövegekre (pl. termékleírások)
     * 3. A találatokból kontextus készül
     * 4. Prompt összeállítása, majd válasz generálása az OpenAI segítségével
     *
     * @param string $question A felhasználó által feltett kérdés
     * @return string A generált válasz szöveg
     */
    public function answer(string $question): string
    {
        // Embedding generálása a kérdésből
        $embeddingGenerator = new OpenAI3SmallEmbeddingGenerator();
        $embedding = $embeddingGenerator->embedText($question);

        // Hasonló dokumentumok keresése a vektor-adatbázisból (pl. PostgreSQL + pgvector)
        $vectorStore = new PgsqlVectorStore();
        $similarDocs = $vectorStore->similaritySearch($embedding);

        // Ha nincs találat, visszatérünk egy alapértelmezett válasszal
        if (empty($similarDocs)) {
            return "Sajnálom, nem találtam releváns információt a kérdésedhez.";
        }

        // A releváns dokumentumok tartalmának összefűzése kontextusnak
        $context = collect($similarDocs)->pluck('content')->implode("\n---\n");

        // Vásárlóbarát, barátságos válasz stílusú prompt generálása
        $prompt = <<<PROMPT
Kérlek, válaszolj a vásárlói kérdésre az alábbi termékleírás vagy szöveg alapján. A válaszod legyen segítőkész, érthető és barátságos, mintha egy webshop ügyfélszolgálata válaszolna. Ha a kérdésre nem található egyértelmű válasz, írd azt, hogy "Sajnálom, erről nem áll rendelkezésemre információ."

Kérdés: "{$question}"

Termékleírás / szöveg:
{$context}

Fontos: A válasz a megadott szövegre épüljön, lehetőleg idézve belőle. Kiegészítheted rövid magyarázattal vagy új információval is, amennyiben az szorosan kapcsolódik a szöveg témájához, és nem mond ellent annak tartalmának. A hangnem maradjon vásárlóbarát és segítőkész.
PROMPT;

        // A válasz generálása OpenAI segítségével
        return $this->openai->chat($prompt);
    }
}
