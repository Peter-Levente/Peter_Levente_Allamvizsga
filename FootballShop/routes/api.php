<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmbeddingController;
use App\Http\Controllers\QuestionAnswerController;

Route::middleware('api')->group(function () {
    Route::post('/generate-embedding', [EmbeddingController::class, 'generate']);
    Route::post('/generate-small-embedding', [EmbeddingController::class, 'generateSmallEmbedding']);
    Route::post('/store_embedding', [EmbeddingController::class, 'store']);
    Route::post('/ask-question', [QuestionAnswerController::class, 'ask']);
    Route::get('/sync-embeddings', [EmbeddingController::class, 'syncProductEmbeddingsToPostgres']);


});
