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
            case 'oldest':
                $query->oldest();
                break;

            case 'questions':
                $query->orderByDesc('questions_count');
                break;

            case 'rating':
                $query->orderByDesc('average_rating');
                break;

            default:
                $query->latest();
                break;
        }

        $quizzes    = $query->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $tab        = 'quizzes';

        return view('admin-dashboard', compact('quizzes', 'categories', 'tab'));
    }

    // ── Tab: Reports ──────────────────────────────────────
    public function reports(Request $request)
    {
        $query = QuizReport::with(['quiz.category', 'quiz.user', 'reportedBy', 'quiz.user']);

        if ($request->filled('search')) {
            $query->whereHas('quiz', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->whereHas('quiz', function ($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        if ($request->filled('user')) {
            $query->whereHas('quiz.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user . '%')
                  ->orWhere('email', 'like', '%' . $request->user . '%');
            });
        }

        if ($request->filled('premium')) {
            $query->whereHas('quiz', function ($q) use ($request) {
                $q->where('is_premium', $request->premium);
            });
        }

        if ($request->filled('min_questions')) {
            $query->whereHas('quiz', function ($q) use ($request) {
                $q->where('questions_count', '>=', $request->min_questions);
            });
        }

        if ($request->filled('min_rating')) {
            $query->whereHas('quiz', function ($q) use ($request) {
                $q->where('average_rating', '>=', $request->min_rating);
            });
        }

        if ($request->filled('date_from')) {
            $query->whereHas('quiz', function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            });
        }

        if ($request->filled('date_to')) {
            $query->whereHas('quiz', function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->date_to);
            });
        }

        switch ($request->get('sort', 'newest')) {
            case 'oldest':
                $query->oldest();
                break;

            case 'quiz_newest':
                $query->orderByDesc(
                    Quiz::select('created_at')
                        ->whereColumn('quizzes.id', 'quiz_reports.quiz_id')
                        ->limit(1)
                );
                break;

            case 'quiz_oldest':
                $query->orderBy(
                    Quiz::select('created_at')
                        ->whereColumn('quizzes.id', 'quiz_reports.quiz_id')
                        ->limit(1)
                );
                break;

            case 'questions':
                $query->orderByDesc(
                    Quiz::select('questions_count')
                        ->whereColumn('quizzes.id', 'quiz_reports.quiz_id')
                        ->limit(1)
                );
                break;

            case 'rating':
                $query->orderByDesc(
                    Quiz::select('average_rating')
                        ->whereColumn('quizzes.id', 'quiz_reports.quiz_id')
                        ->limit(1)
                );
                break;

            default:
                $query->latest();
                break;
        }

        $reports    = $query->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $tab        = 'reports';

        return view('admin-dashboard', compact('reports', 'categories', 'tab'));
    }

    // ── Tab: Users ────────────────────────────────────────
    public function users(Request $request)
    {
        // Show users who either had quizzes deleted by admin OR authored a reported quiz
        $reportedAuthorIds = QuizReport::join('quizzes', 'quizzes.id', '=', 'quiz_reports.quiz_id')
            ->whereNotNull('quizzes.user_id')
            ->pluck('quizzes.user_id');

        $query = User::where(function ($q) use ($reportedAuthorIds) {
            $q->whereHas('quizzes', function ($q2) {
                $q2->whereNotNull('deleted_by_admin_at')->withTrashed();
            })
            ->orWhereIn('id', $reportedAuthorIds);
        })
        ->withCount(['quizzes as deleted_quizzes_count' => function ($q) {
            $q->whereNotNull('deleted_by_admin_at')->withTrashed();
        }])
        ->withCount(['quizzes as reported_quizzes_count' => function ($q) {
            $q->whereHas('reports');
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
            ->where(function ($q) {
                $q->whereNotNull('deleted_by_admin_at')
                  ->orWhereHas('reports');
            })
            ->with('category')
            ->latest()
            ->get();

        $tab = 'user_quizzes';

        return view('admin-dashboard', compact('user', 'quizzes', 'tab'));
    }

    // ── Quiz preview ─────────────────────────────────────
    public function previewQuiz(int $quizId)
    {
        $quiz = Quiz::withTrashed()
            ->with(['user', 'category'])
            ->withCount('reports')
            ->findOrFail($quizId);

        $questions = $quiz->questions()
            ->orderBy('quiz_question.question_order')
            ->get();

        return view('admin-quiz-preview', compact('quiz', 'questions'));
    }

    // ── Actions ───────────────────────────────────────────
    public function deleteQuiz(Quiz $quiz)
    {
        $quizTitle = $quiz->title;

        $quiz->update([
            'deleted_by_admin_at'      => now(),
            'deleted_by_admin_user_id' => Auth::id(),
        ]);

        $quiz->delete(); // soft delete — pivot stays intact for preview

        return back()->with('success', "Quiz \"{$quizTitle}\" został usunięty.");
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

    public function dismissReport(QuizReport $report)
    {
        $report->delete();

        return back()->with('success', 'Zgłoszenie zostało usunięte z listy.');
    }

    public function banUser(Request $request, User $user)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:3650',
        ]);

        $user->update([
            'banned_until' => now()->addDays((int) $request->days),
        ]);

        return back()->with('success', "Użytkownik {$user->name} zbanowany na {$request->days} dni.");
    }

    public function unbanUser(User $user)
    {
        $user->update([
            'banned_until' => null,
        ]);

        return back()->with('success', "Ban użytkownika {$user->name} został zdjęty.");
    }
}