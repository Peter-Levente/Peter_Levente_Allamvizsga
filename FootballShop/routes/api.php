<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmbeddingController;
use App\Http\Controllers\QuestionAnswerController;

// Az alábbi útvonalak az 'api' middleware csoport alatt érhetők el
Route::middleware('api')->group(function () {

    /**
     * Embedding generálása egy fájl alapján (pl. PDF, TXT)
     * POST /generate-embedding
     */
    Route::post('/generate-embedding', [EmbeddingController::class, 'generate']);

    /**
     * Egyetlen szöveges bekezdésre kis méretű embedding generálása
     * POST /generate-small-embedding
     */
    Route::post('/generate-small-embedding', [EmbeddingController::class, 'generateSmallEmbedding']);

    /**
     * Embedding mentése az adatbázisba (pl. termékhez, kérdéshez)
     * POST /store_embedding
     */
    Route::post('/store_embedding', [EmbeddingController::class, 'store']);

    /**
     * Kérdés-válasz funkció: kérdés fogadása és generált válasz visszaküldése
     * POST /ask-question
     */
    Route::post('/ask-question', [QuestionAnswerController::class, 'ask']);

    /**
     * Termékek embeddingjeinek szinkronizálása PostgreSQL adatbázissal
     * GET /sync-embeddings
     */
    Route::get('/sync-embeddings', [EmbeddingController::class, 'syncProductEmbeddingsToPostgres']);
});
