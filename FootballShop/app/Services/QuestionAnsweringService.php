<?php

namespace App\Services;

use App\VectorStores\PgsqlVectorStore;
use LLPhant\Embeddings\EmbeddingGenerator\OpenAI\OpenAI3SmallEmbeddingGenerator;

class QuestionAnsweringService
{
    protected OpenAIService $openai;

    // Konstruktor: injektáljuk az OpenAIService példányt
    public function __construct(OpenAIService $openai)
    {
        $this->openai = $openai;
    }

    public function answer(string $question): string
    {
        $embeddingGenerator = new OpenAI3SmallEmbeddingGenerator();
        $embedding = $embeddingGenerator->embedText($question);

        $vectorStore = new PgsqlVectorStore();
        $similarDocs = $vectorStore->similaritySearch($embedding);

        if (empty($similarDocs)) {
            return "Sajnálom, nem találtam releváns információt a kérdésedhez.";
        }

        $context = collect($similarDocs)->pluck('content')->implode("\n---\n");

//        Hivatalosabb valasz

//        $prompt = <<<PROMPT
//Kérlek, válaszolj a vásárlói kérdésre az alábbi termékleírás vagy szöveg alapján. A válasz legyen pontos, és épüljön a szöveg tartalmára. Ha a kérdésre nem található egyértelmű válasz, írd azt, hogy "Sajnálom, erről nem áll rendelkezésemre információ."
//
//Kérdés: "{$question}"
//
//Termékleírás / szöveg:
//{$context}
//
//Fontos: A választ a megadott szövegre alapozd, lehetőleg idézd is. Kérlek, egészítsd ki a választ rövid magyarázattal vagy kiegészítéssel, akár új információval is, de csak akkor, ha az szorosan kapcsolódik a szöveg témájához, és nem mond ellent annak tartalmának.
//PROMPT;


//        Chatbotos vasarloknak szant valasz

        $prompt = <<<PROMPT
Kérlek, válaszolj a vásárlói kérdésre az alábbi termékleírás vagy szöveg alapján. A válaszod legyen segítőkész, érthető és barátságos, mintha egy webshop ügyfélszolgálata válaszolna. Ha a kérdésre nem található egyértelmű válasz, írd azt, hogy "Sajnálom, erről nem áll rendelkezésemre információ."

Kérdés: "{$question}"

Termékleírás / szöveg:
{$context}

Fontos: A válasz a megadott szövegre épüljön, lehetőleg idézve belőle. Kiegészítheted rövid magyarázattal vagy új információval is, amennyiben az szorosan kapcsolódik a szöveg témájához, és nem mond ellent annak tartalmának. A hangnem maradjon vásárlóbarát és segítőkész.
PROMPT;



        return $this->openai->chat($prompt);
    }
}
