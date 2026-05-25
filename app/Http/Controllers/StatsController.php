<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\QuizAttempt;
use App\Models\Quiz;

class StatsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Overall stats
        $totalPlayed = QuizAttempt::where('user_id', $user->id)
            ->whereNotNull('finished_at')->count();

        $totalCreated = Quiz::where('user_id', $user->id)
            ->whereNull('deleted_at')->count();

        $avgScore = QuizAttempt::where('user_id', $user->id)
            ->whereNotNull('finished_at')
            ->where('max_points', '>', 0)
            ->selectRaw('AVG(score_points / max_points * 100) as avg')
            ->value('avg');

        $bestScore = QuizAttempt::where('user_id', $user->id)
            ->whereNotNull('finished_at')
            ->where('max_points', '>', 0)
            ->selectRaw('MAX(score_points / max_points * 100) as best')
            ->value('best');

        $avgTime = QuizAttempt::where('user_id', Auth::id())
            ->whereNotNull('started_at')
            ->whereNotNull('finished_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(SECOND, started_at, finished_at)) as avg_time')
            ->value('avg_time');

        // Recent 10 attempts with quiz title
        $recentAttempts = QuizAttempt::where('user_id', $user->id)
            ->whereNotNull('finished_at')
            ->with('quiz')
            ->latest('finished_at')
            ->take(10)
            ->get();

        // My quizzes with attempt counts
        $myQuizzes = Quiz::where('user_id', $user->id)
            ->whereNull('deleted_at')
            ->withCount('attempts')
            ->with('category')
            ->latest()
            ->take(5)
            ->get();

        // Score distribution (buckets 0-20, 21-40, 41-60, 61-80, 81-100)
        $distribution = [0, 0, 0, 0, 0];
        QuizAttempt::where('user_id', $user->id)
            ->whereNotNull('finished_at')
            ->where('max_points', '>', 0)
            ->selectRaw('(score_points / max_points * 100) as pct')
            ->get()
            ->each(function ($a) use (&$distribution) {
                $idx = min(4, (int) floor($a->pct / 20));
                $distribution[$idx]++;
            });

        return view('stats.index', compact(
            'totalPlayed', 'totalCreated', 'avgScore', 'bestScore',
            'avgTime', 'recentAttempts', 'myQuizzes', 'distribution'
        ));
        return view('stats.index', [
            'totalTime' => $totalTime,
            'avgTime' => $avgTime,
        ]);
    }
}
