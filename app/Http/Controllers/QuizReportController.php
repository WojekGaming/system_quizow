<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizReport;

class QuizReportController extends Controller
{
    public function store(Request $request, $id)
    {
        dd('HIT REPORT ROUTE', $id);
    }
}