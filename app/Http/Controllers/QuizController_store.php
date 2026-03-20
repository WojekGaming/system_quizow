<?php
// Podmień metodę store() w QuizController.php

public function store(Request $request)
{
    $request->validate([
        'title'          => 'required|string|max:150',
        'description'    => 'nullable|string',
        'category_id'    => 'nullable|exists:categories,id',
        'is_premium'     => 'nullable',
        'is_active'      => 'nullable',
        'questions_json' => 'required|string',
    ]);

    $quiz = \App\Models\Quiz::create([
        'user_id'     => \Illuminate\Support\Facades\Auth::id(),
        'title'       => $request->title,
        'description' => $request->description,
        'category_id' => $request->category_id ?: null,
        'is_premium'  => $request->is_premium === '1',
        'is_active'   => $request->is_active !== '0',
    ]);

    $questionsData = json_decode($request->questions_json, true);
    $order = 1;

    foreach ($questionsData as $qData) {
        $question = \App\Models\Question::create([
            'creator_id'    => \Illuminate\Support\Facades\Auth::id(),
            'category_id'   => $request->category_id ?: null,
            'content'       => $qData['text'],
            'question_type' => $qData['type'],
            'answers'       => json_encode(array_filter($qData['answers'], fn($a) => $a !== '')),
            'correct_answers' => json_encode($qData['correct']),
            'is_public_base'  => false,
        ]);

        $quiz->questions()->attach($question->id, [
            'question_order' => $order++,
            'points'         => 1,
        ]);
    }

    $quiz->update(['questions_count' => count($questionsData)]);

    return redirect()->route('quizzes.index')
        ->with('success', "Quiz \"{$quiz->title}\" został stworzony z {$quiz->questions_count} pytaniami!");
}
