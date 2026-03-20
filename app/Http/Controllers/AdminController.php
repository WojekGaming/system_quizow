<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz;
use App\Models\QuizReport;
use App\Models\Category;
use App\Models\User;

class AdminController extends Controller
{
    // ── Tab: Quizzes ─────────────────────────────────────
    public function quizzes(Request $request)
    {
        $query = Quiz::with(['user', 'category'])
            ->withCount('attempts')
            ->whereNull('deleted_at');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('user')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user . '%')
                  ->orWhere('email', 'like', '%' . $request->user . '%');
            });
        }
        if ($request->filled('premium')) {
            $query->where('is_premium', $request->premium);
        }
        if ($request->filled('min_questions')) {
            $query->where('questions_count', '>=', $request->min_questions);
        }
        if ($request->filled('min_rating')) {
            $query->where('average_rating', '>=', $request->min_rating);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        switch ($request->get('sort', 'newest')) {
            case 'oldest':    $query->oldest(); break;
            case 'questions': $query->orderByDesc('questions_count'); break;
            case 'rating':    $query->orderByDesc('average_rating'); break;
            default:          $query->latest();
        }

        $quizzes    = $query->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $tab        = 'quizzes';

        return view('admin-dashboard', compact('quizzes', 'categories', 'tab'));
    }

    // ── Tab: Reports ──────────────────────────────────────
    public function reports(Request $request)
    {
        $query = QuizReport::with(['quiz.category', 'reportedBy']);

        if ($request->filled('search')) {
            $query->whereHas('quiz', fn($q) => $q->where('title', 'like', '%' . $request->search . '%'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category')) {
            $query->whereHas('quiz', fn($q) => $q->where('category_id', $request->category));
        }

        $reports    = $query->latest()->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $tab        = 'reports';

        return view('admin-dashboard', compact('reports', 'categories', 'tab'));
    }

    // ── Tab: Users ────────────────────────────────────────
    public function users(Request $request)
    {
        $query = User::whereHas('quizzes', function ($q) {
            $q->whereNotNull('deleted_by_admin_at')->withTrashed();
        })
        ->withCount(['quizzes as deleted_quizzes_count' => function ($q) {
            $q->whereNotNull('deleted_by_admin_at')->withTrashed();
        }]);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();
        $tab   = 'users';

        return view('admin-dashboard', compact('users', 'tab'));
    }

    // ── Tab: User quizzes ─────────────────────────────────
    public function userQuizzes(User $user)
    {
        $quizzes = Quiz::withTrashed()
            ->where('user_id', $user->id)
            ->whereNotNull('deleted_by_admin_at')
            ->with('category')
            ->latest()
            ->get();

        $tab = 'user_quizzes';

        return view('admin-dashboard', compact('user', 'quizzes', 'tab'));
    }

    // ── Actions ───────────────────────────────────────────
    public function deleteQuiz(Quiz $quiz)
    {
        $quiz->update([
            'deleted_by_admin_at'      => now(),
            'deleted_by_admin_user_id' => Auth::id(),
        ]);
        $quiz->delete();

        return back()->with('success', "Quiz \"{$quiz->title}\" został usunięty.");
    }

    public function resolveReport(QuizReport $report)
    {
        $report->update([
            'status'               => 'resolved',
            'reviewed_by_admin_id' => Auth::id(),
            'reviewed_at'          => now(),
        ]);

        return back()->with('success', 'Zgłoszenie oznaczone jako rozwiązane.');
    }

    public function banUser(Request $request, User $user)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:3650',
        ]);

        $user->update([
            'banned_until' => now()->addDays($request->days),
        ]);

        return back()->with('success', "Użytkownik {$user->name} zbanowany na {$request->days} dni.");
    }

    public function unbanUser(User $user)
    {
        $user->update(['banned_until' => null]);
        return back()->with('success', "Ban użytkownika {$user->name} został zdjęty.");
    }
}