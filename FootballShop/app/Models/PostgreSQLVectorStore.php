<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use LLPhant\VectorStore\VectorStore;
use LLPhant\Document\Document;

class PostgreSQLVectorStore implements VectorStore
{
    public function addDocuments(array $documents): void
    {
        foreach ($documents as $document) {
            DB::table('embeddings')->insert([
                'context' => $document->metadata['context'] ?? '',
                'content' => $document->content,
                'embedding' => $this->arrayToVectorString($document->embedding),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function similaritySearch(string $queryEmbedding, int $k = 5): array
    {
        $query = "
            SELECT id, content, context
            FROM embeddings
            ORDER BY embedding <-> :embedding::vector
            LIMIT :limit
        ";

        $results = DB::select($query, [
            'embedding' => $queryEmbedding,
            'limit' => $k
        ]);

        return array_map(function ($row) {
            return new Document(
                content: $row->content,
                metadata: ['context' => $row->context]
            );
        }, $results);
    }

    private function arrayToVectorString(array $embedding): string
    {
        return '[' . implode(',', $embedding) . ']';
    }
}

