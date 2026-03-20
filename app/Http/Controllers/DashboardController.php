<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Friendship;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $quizzesCreated = Quiz::where('user_id', $user->id)
            ->whereNull('deleted_at')
            ->count();

        $quizzesPlayed = QuizAttempt::where('user_id', $user->id)
            ->whereNotNull('finished_at')
            ->count();

        $avgScore = QuizAttempt::where('user_id', $user->id)
            ->whereNotNull('finished_at')
            ->where('max_points', '>', 0)
            ->selectRaw('AVG(score_points / max_points * 100) as avg')
            ->value('avg');

        $friendsCount = Friendship::where(function ($q) use ($user) {
                $q->where('requester_id', $user->id)
                  ->orWhere('addressee_id', $user->id);
            })
            ->where('status', 'accepted')
            ->count();

        $stats = [
            'quizzes_created' => $quizzesCreated,
            'quizzes_played'  => $quizzesPlayed,
            'avg_score'       => round($avgScore ?? 0),
            'friends_count'   => $friendsCount,
        ];

        $myQuizzes = Quiz::where('user_id', $user->id)
            ->whereNull('deleted_at')
            ->with('category')
            ->latest()
            ->take(5)
            ->get();

        $recentAttempts = QuizAttempt::where('user_id', $user->id)
            ->whereNotNull('finished_at')
            ->with('quiz')
            ->latest('finished_at')
            ->take(5)
            ->get();

        $pendingRequests = Friendship::where('addressee_id', $user->id)
            ->where('status', 'pending')
            ->with('requester')
            ->latest()
            ->get();

        $friends = User::whereIn('id', function ($q) use ($user) {
                $q->select('requester_id')->from('friendships')
                  ->where('addressee_id', $user->id)
                  ->where('status', 'accepted');
            })
            ->orWhereIn('id', function ($q) use ($user) {
                $q->select('addressee_id')->from('friendships')
                  ->where('requester_id', $user->id)
                  ->where('status', 'accepted');
            })
            ->withCount('quizzes')
            ->take(5)
            ->get();

        $unreadNotifications = $user->userNotifications()
            ->whereNull('read_at')
            ->count();

        $pendingFriendRequests = $pendingRequests->count();

        return view('dashboard', compact(
            'stats',
            'myQuizzes',
            'recentAttempts',
            'pendingRequests',
            'friends',
            'unreadNotifications',
            'pendingFriendRequests'
        ));
    }
}