<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function showQuiz(int $id, Request $request)
    {
        $page = $request->get('page', 1);
        $questionCount = 20;

        if ($page < 1 || $page > $questionCount) {
            $page = 1;
        }

        return view('quiz.quiz', ['id' => $id, 'page' => $page, 'questionCount' => $questionCount]);
    }
}
