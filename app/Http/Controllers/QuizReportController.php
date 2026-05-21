<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizReport;
use Illuminate\Http\Request;

class QuizReportController extends Controller
{
    public function store(Request $request, Quiz $quiz)
    {
        $request->validate([
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $alreadyReported = QuizReport::where('quiz_id', $quiz->id)
            ->where('reported_by_user_id', auth()->id())
            ->exists();

        if ($alreadyReported) {
            return back()->with('error', 'Już zgłosiłeś ten quiz.');
        }

        QuizReport::create([
            'quiz_id' => $quiz->id,
            'reported_by_user_id' => auth()->id(),
            'reason' => $request->reason,
            'status' => 'new',
        ]);

        return back()->with('success', 'Quiz został zgłoszony do moderacji.');
    }
}