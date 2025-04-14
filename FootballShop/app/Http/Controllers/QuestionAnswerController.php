<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuestionAnsweringService;

class QuestionAnswerController extends Controller
{
    /**
     * Ez a metódus egy kérdést fogad POST kérésben,
     * majd visszaadja a választ, amit a QuestionAnsweringService generál.
     */
    public function ask(Request $request, QuestionAnsweringService $qa)
    {
        // 1. Kivesszük a kérdést a kérésből
        $question = $request->input('question');

        // 2. Meghívjuk a szolgáltatást, hogy megválaszolja a kérdést
        $answer = $qa->answer($question);

        // 3. JSON válaszként visszaküldjük az eredményt
        return response()->json(['answer' => $answer]);
    }
}
