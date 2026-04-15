<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizReport;

class QuizReportController extends Controller
{
    public function store(Request $request, $quizId)
    {
        QuizReport::create([
            'quiz_id' => $quizId,
            'reported_by_user_id' => auth()->id(),
            'reason' => $request->reason,
            'status' => 'new'
        ]);

        return back()->with('success', 'Quiz został zgłoszony');
    }
}