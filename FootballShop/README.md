# ⚽ FootballShop – Laravel alapú futball webshop RAG-alapú mesterséges intelligenciával

Ez a projekt egy Laravel-alapú futball webshop rendszer, amely RAG (Retrieval-Augmented Generation) alapú mesterséges intelligencia integrációval rendelkezik az OpenAI és LLPhant segítségével. A rendszer lehetővé teszi termékek böngészését, kosárba helyezését, rendelés leadását, ajánlott termékek megjelenítését, valamint egy RAG-alapú chatbot asszisztens segítségével intelligens kérdés-válasz funkciót is biztosít.

---

## 🖥 Rendszerkövetelmények

- PHP 8.2+
- Composer
- Node.js + NPM
- PostgreSQL (pgvector kiterjesztéssel)
- OpenAI API kulcs

---

## 🐘 PostgreSQL + pgvector telepítése

A projekt PostgreSQL adatbázist használ, ezért először ezt kell telepítened.

### 🔗 PostgreSQL letöltése:

https://www.postgresql.org/download/

Töltsd le és telepítsd a saját operációs rendszerednek megfelelő verziót.

### 📹 pgvector kiterjesztés beállítása videó alapján:

Ez a projekt használja a vector típusú mezőket, amelyek csak akkor működnek, ha a pgvector kiterjesztés engedélyezve van. Ennek beállításához kövesd az alábbi videót.

[YouTube: pgvector PostgreSQL extension setup](https://www.youtube.com/watch?v=YoQZRKjgBkU&t=6s)

### 🛠 pgvector aktiválása adatbázisban:

Miután beléptél psql-be:

```sql
CREATE EXTENSION IF NOT EXISTS vector;
```

Ez szükséges az `embedding` típusú mezőkhöz (AI válaszrendszerhez).

---

## 🚀 Telepítés

### 1. Repository klónozása vagy kicsomagolása

```bash
git clone <repo-url>
cd FootballShop
```

### 2. Függőségek telepítése

```bash
composer install
npm install && npm run build
```

### 3. Környezeti fájl beállítása

```bash
cp .env.example .env
```

Töltsd ki a `.env` fájlban:

- PostgreSQL kapcsolat
- OpenAI API kulcs
- LLPhant beállítások

Példa:

```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=footballshop
DB_USERNAME=postgres
DB_PASSWORD=secret

OPENAI_API_KEY=az_openai_kulcsod
LLPHANT_DRIVER=pgsql
LLPHANT_TABLE_NAME=embeddings
```

---



## 🧠 Mesterséges intelligencia integráció

A projekt használja az LLPhant és OpenAI integrációt kérdés-válasz rendszerhez, vektoros kereséssel.

### 📦 Használt csomagok:

- `openai-php/client`
- `openai-php/laravel`
- `theodo-group/llphant`

---

## 📦 AI csomagok telepítése és használata

A mesterséges intelligencia funkciók használatához az alábbi csomagokat kell telepíteni:

```bash
composer require openai-php/client openai-php/laravel theodo-group/llphant
```

Ezután publikáld az OpenAI konfigurációt:

```bash
php artisan vendor:publish --tag=openai-config
```

A `.env` fájlban add meg az OpenAI kulcsodat:

```dotenv
OPENAI_API_KEY=az_openai_kulcsod
```

LLPhant PostgreSQL driver esetén a `LLPHANT_DRIVER=pgsql` érték legyen megadva.



## 🔁 Adatok betöltése – Seeder-ek és RAG rendszer inicializálása

A projekt valós adatokat tartalmaz, amelyeket előre definiált seeder fájlokkal lehet betölteni az adatbázisba.

Miután sikeresen beállítottad a környezetet, futtasd az alábbi parancsokat az adatok feltöltéséhez:

```bash
php artisan migrate
php artisan db:seed
```

Ez a következőket hajtja végre:

- Létrehozza az adatbázis táblákat
- Feltölti őket termékekkel, felhasználókkal, rendelések adataival stb.
- Betölti az `embeddings` és `product_embeddings` táblákat, amelyeket a RAG-alapú chatbot asszisztens használ

Ezek után a webshop azonnal használható!



## ▶️ Alkalmazás elindítása

Miután az adatbázist migráltad és az adatokat betöltötted, elindíthatod a Laravel szervert az alábbi paranccsal:

```bash
php artisan serve
```

Ezután megnyithatod a webshopot a böngészőben:

🔗 http://127.0.0.1:8000