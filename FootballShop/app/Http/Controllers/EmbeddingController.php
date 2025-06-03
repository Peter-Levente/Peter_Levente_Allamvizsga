<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EmbeddingService;

// Az embeddingek generálásáért, mentéséért és szinkronizálásáért felelős kontroller
class EmbeddingController extends Controller
{
    // Az embedding szolgáltatás példánya
    protected $embeddingService;

    /**
     * Konstruktor
     *
     * A függőséginjektálás során megkapja az EmbeddingService példányát,
     * amelyet az osztály többi metódusában használni fog.
     *
     * @param EmbeddingService $embeddingService
     */
    public function __construct(EmbeddingService $embeddingService)
    {
        $this->embeddingService = $embeddingService;
    }

    /**
     * Embedding generálása fájl alapján
     *
     * @param Request $request A HTTP kérés objektuma, amely tartalmazza a fájl elérési útját
     * @return \Illuminate\Http\JsonResponse A generált embedding(ek)
     */
    public function generate(Request $request)
    {
        $filePath = $request->input('file_path');
        $result = $this->embeddingService->generateEmbeddings($filePath);

        return response()->json($result);
    }

    /**
     * Rövid szöveg alapján egyetlen embedding generálása
     *
     * @param Request $request A HTTP kérés, amely tartalmaz egy 'text' mezőt
     * @return \Illuminate\Http\JsonResponse Egyetlen embedding vektor visszaadása
     */
    public function generateSmallEmbedding(Request $request)
    {
        $text = $request->input('text');
        $embedding = $this->embeddingService->generateSmallEmbedding($text);

        return response()->json(['embedding' => $embedding]);
    }

    /**
     * Embedding adat mentése PostgreSQL-be
     *
     * Validálja a bemeneti adatokat, majd elmenti az embeddinget az adatbázisba.
     *
     * @param Request $request A HTTP kérés (context, content, related_id)
     * @return \Illuminate\Http\JsonResponse Mentés eredménye
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'context' => 'required|string|max:50',
            'content' => 'required|string',
            'related_id' => 'required|integer',
        ]);

        $embedding = $this->embeddingService->saveEmbedding(
            $validated['context'],
            $validated['content'],
            $validated['related_id']
        );

        return response()->json(['message' => 'Embedding saved successfully', 'data' => $embedding]);
    }

    /**
     * Termékek embeddingjeinek szinkronizálása PostgreSQL adatbázisba
     *
     * Ez a metódus az összes termék embeddingjét újragenerálja és elmenti vagyis a termékek szövegeiből vektort csinál.
     *
     * @return \Illuminate\Http\JsonResponse Visszajelzés a szinkronizálásról
     */
    public function syncProductEmbeddingsToPostgres()
    {
        $this->embeddingService->syncProductEmbeddingsToPostgres();
        return response()->json(['message' => '✅ Embeddingek szinkronizálva a PostgreSQL-be.']);
    }
}
