<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Quiz;
use App\Models\Category;

class QuizController extends Controller
{
    use AuthorizesRequests;

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
            'category_id' => $request->category_id ?: null,
            'is_premium'  => $request->boolean('is_premium'),
            'is_active'   => $request->boolean('is_active', true),
        ]);

        if ($request->filled('questions_json')) {
            $this->syncQuestions($quiz, $request->input('questions_json'), $request->allFiles());
        }

        return redirect()->route('quizzes.edit', $quiz)
            ->with('success', 'Quiz został stworzony!');
    }

    public function edit(Quiz $quiz)
    {
        $this->authorize('update', $quiz);
        $categories = Category::orderBy('name')->get();
        $quiz->load(['questions' => fn($q) => $q->orderBy('quiz_question.question_order')]);

        $questionsJson = $quiz->questions->map(function ($q) {
            $answers = is_string($q->answers) ? json_decode($q->answers, true) : $q->answers;
            $correct = is_string($q->correct_answers) ? json_decode($q->correct_answers, true) : $q->correct_answers;
            return [
                'id'         => $q->id,
                'text'       => $q->content,
                'type'       => $q->question_type,
                'answers'    => $answers ?? ['', '', '', ''],
                'correct'    => $correct ?? [],
                'image_path' => $q->image_path,
            ];
        })->values();

        return view('quizzes.edit', compact('quiz', 'categories', 'questionsJson'));
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
            'category_id' => $request->category_id ?: null,
            'is_premium'  => $request->boolean('is_premium'),
            'is_active'   => $request->boolean('is_active'),
        ]);

        if ($request->filled('questions_json')) {
            $this->syncQuestions($quiz, $request->input('questions_json'), $request->allFiles());
        }

        return back()->with('success', 'Quiz zaktualizowany.');
    }

    /**
     * Return available questions for a given category.
     * Includes public base questions and the current user's own questions.
     */
    public function availableQuestions(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'quiz_id'     => 'nullable|exists:quizzes,id',
        ]);

        $categoryId = $request->input('category_id');
        $quizId     = $request->input('quiz_id');

        // Get IDs of questions already in this quiz so we can exclude them
        $excludeIds = $quizId
            ? \App\Models\Quiz::withTrashed()->find($quizId)?->questions()->pluck('questions.id')->toArray() ?? []
            : [];

        $questions = \App\Models\Question::where('category_id', $categoryId)
            ->where(function ($q) {
                $q->where('is_public_base', true)
                  ->orWhere('creator_id', Auth::id());
            })
            ->when(!empty($excludeIds), fn($q) => $q->whereNotIn('id', $excludeIds))
            ->orderBy('created_at', 'desc')
            ->limit(200)
            ->get()
            ->map(function ($q) {
                $answers = is_string($q->answers) ? json_decode($q->answers, true) : $q->answers;
                $correct = is_string($q->correct_answers) ? json_decode($q->correct_answers, true) : $q->correct_answers;
                return [
                    'id'           => $q->id,
                    'text'         => $q->content,
                    'type'         => $q->question_type,
                    'answers'      => $answers ?? ['', '', '', ''],
                    'correct'      => $correct ?? [],
                    'creator_id'   => $q->creator_id,
                    'creator_name' => $q->creator?->name,
                    'can_edit'     => false,
                    'image_path'   => $q->image_path,
                ];
            });

        return response()->json(['questions' => $questions]);
    }

    public function destroy(Quiz $quiz)
    {
        $this->authorize('delete', $quiz);
        $quiz->delete(); // soft delete — pivot stays intact
        return redirect()->route('quizzes.index')->with('success', 'Quiz usunięty.');
    }

    private function syncQuestions(Quiz $quiz, string $questionsJson, array $files = []): void
    {
        $questions = json_decode($questionsJson, true);
        if (!is_array($questions)) return;

        $syncData = [];
        $order = 1;
        $currentQuizQuestionIds = $quiz->questions()->pluck('questions.id')->toArray();

        foreach ($questions as $idx => $q) {
            $text    = trim($q['text'] ?? '');
            $type    = $q['type'] ?? 'single_choice';
            $answers = is_array($q['answers']) ? $q['answers'] : ['', '', '', ''];
            $correct = is_array($q['correct']) ? $q['correct'] : [];

            if (!$text) {
                continue;
            }

            $existingId = null;
            if (isset($q['id']) && is_numeric($q['id'])) {
                $existingId = (int) $q['id'];
                if ($existingId <= 0) {
                    $existingId = null;
                }
            }

            $question = $existingId ? \App\Models\Question::find($existingId) : null;

            if (!$question) {
                $question = new \App\Models\Question();
                $question->creator_id = Auth::id();
                $question->category_id = $quiz->category_id; // inherit quiz category for new questions
                $canModifyQuestion = true;
            } else {
                $belongsToQuiz = in_array($question->id, $currentQuizQuestionIds, true);
                $canModifyQuestion = $belongsToQuiz && $question->creator_id === Auth::id();
            }

            if ($canModifyQuestion) {
                $question->content         = $text;
                $question->question_type   = $type;
                $question->answers         = json_encode(array_values($answers));
                $question->correct_answers = json_encode(array_values($correct));
                $question->category_id     = $quiz->category_id;

                $fileKey = 'image_q_' . $idx;
                if (isset($files[$fileKey])) {
                    if ($question->image_path) {
                        Storage::disk('public')->delete($question->image_path);
                    }
                    $path = $files[$fileKey]->store('question_images', 'public');
                    $question->image_path = $path;
                } elseif (!empty($q['remove_image'])) {
                    if ($question->image_path) {
                        Storage::disk('public')->delete($question->image_path);
                    }
                    $question->image_path = null;
                }

                $question->save();
            }

            if (empty($question->id)) {
                continue;
            }

            $syncData[(int) $question->id] = ['question_order' => $order++];
        }

        $quiz->questions()->sync($syncData);
        $quiz->update(['questions_count' => count($syncData)]);
    }
}