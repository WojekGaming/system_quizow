<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz;
use App\Models\Category;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->with('category')
            ->withCount('attempts')
            ->latest()
            ->paginate(12);

        return view('quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('quizzes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:150',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'is_premium'  => 'boolean',
            'is_active'   => 'boolean',
        ]);

        $quiz = Quiz::create([
            'user_id'     => Auth::id(),
            'title'       => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'is_premium'  => $request->boolean('is_premium'),
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('quizzes.edit', $quiz)
            ->with('success', 'Quiz został stworzony! Dodaj teraz pytania.');
    }

    public function edit(Quiz $quiz)
    {
        $this->authorize('update', $quiz);
        $categories = Category::orderBy('name')->get();
        $quiz->load(['questions' => fn($q) => $q->orderBy('quiz_question.question_order')]);
        return view('quizzes.edit', compact('quiz', 'categories'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $this->authorize('update', $quiz);

        $request->validate([
            'title'       => 'required|string|max:150',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'is_premium'  => 'boolean',
            'is_active'   => 'boolean',
        ]);

        $quiz->update([
            'title'       => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'is_premium'  => $request->boolean('is_premium'),
            'is_active'   => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Quiz zaktualizowany.');
    }

    public function destroy(Quiz $quiz)
    {
        $this->authorize('delete', $quiz);
        $quiz->delete();
        return redirect()->route('quizzes.index')->with('success', 'Quiz usunięty.');
    }
}
