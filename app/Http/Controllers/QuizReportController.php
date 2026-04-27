<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizReport;

class QuizReportController extends Controller
{
    public function store(Request $request, $id)
    {
        QuizReport::create([
            'quiz_id' => $id,
            'reported_by_user_id' => auth()->id(),
            'reason' => $request->reason ?? null,
            'status' => 'new'
        ]);

        return back()->with('success', 'Zgłoszono quiz');
    }
}