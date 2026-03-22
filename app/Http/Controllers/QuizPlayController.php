<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizPlayController extends Controller
{
    public function show(Quiz $quiz)
    {
        if (!$quiz->is_active) {
            abort(404);
        }

        $quiz->load(['questions' => fn($q) => $q->orderBy('quiz_question.question_order')]);

        if ($quiz->questions->isEmpty()) {
            return redirect()->back()->with('error', 'Ten quiz nie ma jeszcze pytań.');
        }

        return view('quiz-play', compact('quiz'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $request->validate([
            'answers' => 'required|array',
        ]);

        $quiz->load(['questions' => fn($q) => $q->orderBy('quiz_question.question_order')]);

        $scorePoints = 0;
        $maxPoints   = $quiz->questions->count();
        $results     = [];

        foreach ($quiz->questions as $question) {
            $userAnswer    = $request->input('answers.' . $question->id, []);
            $correctAnswer = $question->correct_answers ?? [];

            if (is_string($correctAnswer)) {
                $correctAnswer = json_decode($correctAnswer, true) ?? [];
            }

            if (!is_array($userAnswer)) {
                $userAnswer = [$userAnswer];
            }

            $userAnswer    = array_map('intval', $userAnswer);
            $correctAnswer = array_map('intval', $correctAnswer);

            sort($userAnswer);
            sort($correctAnswer);

            $isCorrect = array_map('strval', $userAnswer) === array_map('strval', $correctAnswer);

            if ($isCorrect) {
                $scorePoints++;
            }

            $answers = $question->answers;
            if (is_string($answers)) {
                $answers = json_decode($answers, true) ?? [];
            }

            $results[] = [
                'question'      => $question->content,
                'user_answer'   => $userAnswer,
                'correct'       => $correctAnswer,
                'is_correct'    => $isCorrect,
                'answers'       => $answers,
            ];
        }

        // Save attempt if logged in
        if (Auth::check()) {
            QuizAttempt::create([
                'quiz_id'      => $quiz->id,
                'user_id'      => Auth::id(),
                'score_points' => $scorePoints,
                'max_points'   => $maxPoints,
                'finished_at'  => now(),
            ]);
        }

        $percentage = $maxPoints > 0 ? round(($scorePoints / $maxPoints) * 100) : 0;

        return view('quiz-result', compact('quiz', 'results', 'scorePoints', 'maxPoints', 'percentage'));
    }
}