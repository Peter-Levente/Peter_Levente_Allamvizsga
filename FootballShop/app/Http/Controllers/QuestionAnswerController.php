<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuestionAnsweringService;

// A felhasználói kérdések megválaszolásáért felelős kontroller
class QuestionAnswerController extends Controller
{
    /**
     * A felhasználó által feltett kérdés feldolgozása és megválaszolása
     *
     * Ez a metódus egy kérdést fogad a kliens felől (pl. űrlap POST vagy JSON),
     * továbbadja a háttérben működő kérdés-válasz szolgáltatásnak, majd visszaküldi a választ.
     *
     * @param Request $request A HTTP kérés objektuma
     * @param QuestionAnsweringService $qa A kérdés-válasz szolgáltatás (szolgáltatásként injektálva)
     * @return \Illuminate\Http\JsonResponse A válasz JSON formátumban visszaküldve
     */
    public function ask(Request $request, QuestionAnsweringService $qa)
    {
        // A kérdést kivesszük a bemeneti adatok közül
        $question = $request->input('question');

        // Meghívjuk a szolgáltatást, amely megkeresi a választ (pl. embedding + keresés alapján)
        $answer = $qa->answer($question);

        // A választ JSON formátumban visszaküldjük a kliensnek
        return response()->json(['answer' => $answer]);
    }
}
