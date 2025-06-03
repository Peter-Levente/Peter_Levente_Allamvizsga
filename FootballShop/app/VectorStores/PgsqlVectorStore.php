<?php

namespace App\VectorStores;

use App\Models\Embedding;
use Illuminate\Support\Facades\Log;
use LLPhant\Embeddings\Document;
use LLPhant\Embeddings\VectorStores\VectorStoreBase;
use Illuminate\Support\Facades\DB;

// PostgreSQL-alapú VectorStore implementáció LLPhant számára (pgvector támogatással)
class PgsqlVectorStore extends VectorStoreBase
{
    /**
     * Egyetlen dokumentum mentése az embeddings táblába
     *
     * @param Document $document Az embeddinget és metaadatokat tartalmazó objektum
     * @return void
     */
    public function addDocument(Document $document): void
    {
        Embedding::create([
            'context' => $document->metadata['context'] ?? '',
            'content' => $document->content,
            'related_id' => $document->metadata['related_id'] ?? null,
            'embedding' => $document->embedding,
        ]);
    }

    /**
     * Több dokumentum mentése a vector store-ba
     *
     * @param array $documents Tömb dokumentumokból
     * @return void
     */
    public function addDocuments(array $documents): void
    {
        foreach ($documents as $document) {
            $this->addDocument($document);
        }
    }

    /**
     * Hasonlóság alapú keresés embedding vektor alapján PostgreSQL pgvector operátorral
     *
     * @param array $embedding A lekérdező vektor (pl. kérdés embedding)
     * @param int $k Legfeljebb hány dokumentumot adjunk vissza
     * @param array $additionalArguments További paraméterek (pl. küszöbérték)
     * @return iterable Dokumentumok listája, amik a feltételnek megfelelnek
     */
    public function similaritySearch(array $embedding, int $k = 3, array $additionalArguments = []): iterable
    {
        // Vektor szövegét alakítjuk PostgreSQL formátumban
        $embeddingStr = '[' . implode(',', $embedding) . ']';

        // Hasonlóság-küszöb: ennél nagyobb távolságú dokumentumokat kiszűrjük
        $similarityThreshold = $additionalArguments['threshold'] ?? -0.30;

        // SQL lekérdezés a legközelebbi dokumentumokra pgvector segítségével (<#> = cosine distance)
        // A CAST(? AS vector) SQL kifejezés a PostgreSQL-ben azt jelenti, hogy az adott értéket explicit módon vector típusra konvertáljuk.
        $results = DB::connection('pgsql')->select("
            SELECT *, embedding <#> CAST(? AS vector) AS distance
            FROM embeddings
            ORDER BY embedding <#> CAST(? AS vector)
            LIMIT ?
        ", [$embeddingStr, $embeddingStr, $k]);

        // Naplózás fejlesztési/tesztelési célra
        foreach ($results as $row) {
            Log::debug('Találat: ', [
                'id' => $row->id,
                'distance' => $row->distance,
                'content' => mb_substr($row->content, 0, 100) . '...'
            ]);
        }

        // Csak azokat tartjuk meg, amelyek távolsága kisebb a küszöbnél (magas hasonlóság)
        $filtered = array_filter($results, fn($row) => $row->distance < $similarityThreshold);

        // Átalakítjuk a sorokat LLPhant Document objektumokká
        return array_map(function ($row) {
            $document = new Document();
            $document->content = $row->content;
            $document->embedding = json_decode($row->embedding); // a json_decode(...) függvény visszaadja egy valódi PHP tömbként
            $document->sourceName = 'db';
            $document->chunkNumber = 0;
            $document->id = $row->id;

            return $document;
        }, $filtered);
    }
}
