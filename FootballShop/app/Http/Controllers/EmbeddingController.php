<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Services\EmbeddingService;


class EmbeddingController extends Controller
{
    protected $embeddingService;

    public function __construct(EmbeddingService $embeddingService)
    {
        $this->embeddingService = $embeddingService;
    }

    public function generate(Request $request)
    {
        $filePath = $request->input('file_path');
        $result = $this->embeddingService->generateEmbeddings($filePath);

        return response()->json($result);
    }

    public function generateSmallEmbedding(Request $request)
    {
        $text = $request->input('text');
        $embedding = $this->embeddingService->generateSmallEmbedding($text);

        return response()->json(['embedding' => $embedding]);
    }


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


    public function syncProductEmbeddingsToPostgres()
    {
        $this->embeddingService->syncProductEmbeddingsToPostgres();
        return response()->json(['message' => '✅ Embeddingek szinkronizálva a PostgreSQL-be.']);
    }
}
