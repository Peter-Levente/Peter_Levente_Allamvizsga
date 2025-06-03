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

// Embedding-generálást és tárolást végző szolgáltatás különféle szöveges bemenetekre
class EmbeddingService
{
    protected $client;

    /**
     * OpenAI kliens inicializálása
     */
    public function __construct()
    {
        $this->client = OpenAI::client(env('OPENAI_API_KEY'));
    }

    /**
     * Nagyobb fájl alapú embeddingek generálása LLPhant és VoyageAI segítségével
     *
     * @param string $filePath A bemeneti fájl elérési útja
     * @return array Embeddingek, adatbázisba mentéshez előkészített vektorok és lekérdezhető reprezentációk
     */
    public function generateEmbeddings(string $filePath)
    {
        // 1. Fájl beolvasása dokumentumokra bontva
        $reader = new FileDataReader($filePath);
        $documents = $reader->getDocuments();

        // 2. Dokumentumok feldarabolása és formázása
        $splitDocuments = DocumentSplitter::splitDocuments($documents, 800);
        $formattedDocuments = EmbeddingFormatter::formatEmbeddings($splitDocuments);

        // 3. Embedding generálása VoyageAI modellel
        $embeddingGenerator = new Voyage3LargeEmbeddingGenerator();
        $embeddedDocuments = $embeddingGenerator->embedDocuments($formattedDocuments);

        // 4. Adatbázisba mentéshez megfelelő vektorok (pl. pgvector formátumban)
        $vectorsForDb = $embeddingGenerator->forStorage()->embedDocuments($documents);

        // 5. Hasonlóságkereséshez szükséges reprezentáció (egy lekérdezési példa)
        $similarDocuments = $embeddingGenerator->forRetrieval()->embedText('What is the capital of France?');

        return [
            'embedded_documents' => $embeddedDocuments,
            'vectors_for_db' => $vectorsForDb,
            'similar_documents' => $similarDocuments
        ];
    }

    /**
     * Egy rövid szöveghez kis méretű embedding generálása OpenAI modellel (LLPhant)
     *
     * @param string $text A bemeneti szöveg
     * @return array A generált embedding vektor
     */
    public function generateSmallEmbedding(string $text)
    {
        $embeddingGenerator = new OpenAI3SmallEmbeddingGenerator();
        return $embeddingGenerator->embedText($text);
    }

    /**
     * Nyers OpenAI API-hívás egyetlen szöveg embedding generálására
     *
     * @param string $text A bemenetként kapott szöveg
     * @return array Embedding vektor tömb (float értékek)
     */
    public function generateEmbedding(string $text): array
    {
        $response = $this->client->embeddings()->create([
            'model' => 'text-embedding-3-small',
            'input' => $text,
        ]);

        return $response['data'][0]['embedding'];
    }

    /**
     * Embedding elmentése PostgreSQL-be
     *
     * @param string $context A szöveg típusa (pl. "product", "faq", stb.)
     * @param string $content A szöveg maga
     * @param int $relatedId Kapcsolódó rekord azonosítója (pl. product_id)
     * @return bool Sikeres beszúrás (true/false)
     */
    public function saveEmbedding(string $context, string $content, int $relatedId)
    {
        $vector = $this->generateEmbedding($content); // Vektor tömb
        $pgVector = '[' . implode(',', $vector) . ']'; // PostgreSQL kompatibilis formátum

        return DB::connection('pgsql')->table('embeddings')->insert([
            'context' => $context,
            'content' => $content,
            'embedding' => DB::raw("'$pgVector'"),
            'related_id' => $relatedId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Termékek embeddingjeinek újragenerálása és szinkronizálása PostgreSQL adatbázisba
     *
     * @return bool
     */
    public function syncProductEmbeddingsToPostgres()
    {
        $products = Product::all();

        foreach ($products as $product) {
            // Összeállítjuk a szöveget az embedding generálásához
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
