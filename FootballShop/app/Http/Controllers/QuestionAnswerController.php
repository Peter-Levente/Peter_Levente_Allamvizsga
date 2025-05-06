<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuestionAnsweringService;

class QuestionAnswerController extends Controller
{
    // A felhasználó által feltett kérdés feldolgozása
    public function ask(Request $request, QuestionAnsweringService $qa)
    {
        // Kivesszük a kérdést a HTTP kérésből (pl. POST űrlapból vagy JSON-ből)
        $question = $request->input('question');

        // Meghívjuk a szolgáltatást, amely válaszol a kérdésre
        $answer = $qa->answer($question);

        // Visszaküldjük a választ JSON formátumban a kliensnek
        return response()->json(['answer' => $answer]);
    }
}
