<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use LLPhant\Embeddings\DataReader\FileDataReader;
use LLPhant\Embeddings\DocumentSplitter\DocumentSplitter;
use LLPhant\Embeddings\EmbeddingFormatter\EmbeddingFormatter;
use LLPhant\Embeddings\EmbeddingGenerator\OpenAI\OpenAI3SmallEmbeddingGenerator;
use LLPhant\Embeddings\EmbeddingGenerator\VoyageAI\Voyage3LargeEmbeddingGenerator;
use OpenAI;

class EmbeddingService
{
    protected $client;

    public function __construct()
    {
        $this->client = OpenAI::client(env('OPENAI_API_KEY'));
    }

    public function generateEmbeddings(string $filePath)
    {
        // 1. Beolvassuk a fájlokat
        $reader = new FileDataReader($filePath);
        $documents = $reader->getDocuments();

        // 2. Dokumentumok előkészítése
        $splitDocuments = DocumentSplitter::splitDocuments($documents, 800);
        $formattedDocuments = EmbeddingFormatter::formatEmbeddings($splitDocuments);

        // 3. Embedding generálása Voyage3-al
        $embeddingGenerator = new Voyage3LargeEmbeddingGenerator();
        $embeddedDocuments = $embeddingGenerator->embedDocuments($formattedDocuments);

        // 4. Adatbázisba mentéshez
        $vectorsForDb = $embeddingGenerator->forStorage()->embedDocuments($documents);

        // 5. Hasonlóság-kereséshez
        $similarDocuments = $embeddingGenerator->forRetrieval()->embedText('What is the capital of France?');

        return [
            'embedded_documents' => $embeddedDocuments,
            'vectors_for_db' => $vectorsForDb,
            'similar_documents' => $similarDocuments
        ];
    }

    public function generateSmallEmbedding(string $text)
    {
        $embeddingGenerator = new OpenAI3SmallEmbeddingGenerator();
        return $embeddingGenerator->embedText($text);
    }

    public function generateEmbedding(string $text): array
    {
        $response = $this->client->embeddings()->create([
            'model' => 'text-embedding-3-small',
            'input' => $text,
        ]);

        return $response['data'][0]['embedding'];
    }

    public function saveEmbedding(string $context, string $content, int $relatedId)
    {
        $vector = $this->generateEmbedding($content); // ez egy float tömb
        $pgVector = '[' . implode(',', $vector) . ']';

        return DB::connection('pgsql')->table('embeddings')->insert([
            'context' => $context,
            'content' => $content,
            'embedding' => DB::raw("'$pgVector'"),
            'related_id' => $relatedId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function syncProductEmbeddingsToPostgres()
    {
        $products = Product::all();

        foreach ($products as $product) {
            $text = "{$product->name}. Kategória: {$product->category}. Leírás: {$product->description}";

            $response = $this->client->embeddings()->create([
                'model' => 'text-embedding-3-small',
                'input' => $text,
            ]);

            $vector = $response['data'][0]['embedding'];

            if (is_array($vector) && count($vector) > 0) {
                $pgVector = '[' . implode(',', $vector) . ']';

                DB::connection('pgsql')->table('product_embeddings')->updateOrInsert(
                    ['product_id' => $product->id],
                    [
                        'name' => $product->name,
                        'embedding' => DB::raw("'$pgVector'::vector"),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        return true;
    }
}
