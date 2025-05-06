<?php

namespace App\VectorStores;

use App\Models\Embedding;
use Illuminate\Support\Facades\Log;
use LLPhant\Embeddings\Document;
use LLPhant\Embeddings\VectorStores\VectorStoreBase;
use Illuminate\Support\Facades\DB;

class PgsqlVectorStore extends VectorStoreBase
{
    public function addDocument(Document $document): void
    {
        // Egyetlen dokumentum mentése
        Embedding::create([
            'context' => $document->metadata['context'] ?? '',
            'content' => $document->content,
            'related_id' => $document->metadata['related_id'] ?? null,
            'embedding' => $document->embedding,
        ]);
    }

    public function addDocuments(array $documents): void
    {
        foreach ($documents as $document) {
            $this->addDocument($document); // újrahasznosítjuk az előző függvényt
        }
    }

    public function similaritySearch(array $embedding, int $k = 1, array $additionalArguments = []): iterable
    {
        $embeddingStr = '[' . implode(',', $embedding) . ']';
        $similarityThreshold = $additionalArguments['threshold'] ?? -0.30; // alapértelmezett érték

        $results = DB::connection('pgsql')->select("
        SELECT *, embedding <#> CAST(? AS vector) AS distance
        FROM embeddings
        ORDER BY embedding <#> CAST(? AS vector)
        LIMIT ?
    ", [$embeddingStr, $embeddingStr, $k]);

        // Debug log
        foreach ($results as $row) {
            Log::debug('Találat: ', [
                'id' => $row->id,
                'distance' => $row->distance,
                'content' => mb_substr($row->content, 0, 100) . '...'
            ]);
        }

        // Szűrés: csak akkor térünk vissza, ha elég hasonló
        $filtered = array_filter($results, fn($row) => $row->distance < $similarityThreshold);

        return array_map(function ($row) {
            $document = new Document();
            $document->content = $row->content;
            $document->embedding = json_decode($row->embedding);
            $document->sourceName = 'db';
            $document->chunkNumber = 0;
            $document->id = $row->id;

            return $document;
        }, $filtered);
    }
}
