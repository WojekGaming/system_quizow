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
        ]);

        $categoryId = $request->input('category_id');

        $questions = \App\Models\Question::where('category_id', $categoryId)
            ->where(function ($q) {
                $q->where('is_public_base', true)
                  ->orWhere('creator_id', Auth::id());
            })
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
                    'can_edit'     => false, // questions from DB are always read-only when added to a new quiz
                    'image_path'   => $q->image_path,
                ];
            });

        return response()->json(['questions' => $questions]);
    }

    public function destroy(Quiz $quiz)
    {
        $this->authorize('delete', $quiz);
        $quiz->questions()->detach();
        $quiz->delete();
        return redirect()->route('quizzes.index')->with('success', 'Quiz usunięty.');
    }

    private function syncQuestions(Quiz $quiz, string $questionsJson, array $files = []): void
    {
        $questions = json_decode($questionsJson, true);
        if (!is_array($questions)) return;

        $syncData = [];
        $order = 1;

        foreach ($questions as $idx => $q) {
            $text    = trim($q['text'] ?? '');
            $type    = $q['type'] ?? 'single_choice';
            $answers = $q['answers'] ?? ['', '', '', ''];
            $correct = $q['correct'] ?? [];

            if (!$text) continue;

            $existingId = isset($q['id']) && is_numeric($q['id']) && $q['id'] < 1_000_000_000
                ? (int)$q['id'] : null;

            $question = $existingId ? \App\Models\Question::find($existingId) : null;

            // If question exists and is not created by current user, do not overwrite its content/answers/image.
            $canModifyQuestion = true;
            if ($question && $question->creator_id !== Auth::id()) {
                $canModifyQuestion = false;
            }

            if (!$question) {
                $question = new \App\Models\Question();
                $question->creator_id = Auth::id();
                $question->category_id = $quiz->category_id; // inherit quiz category for new questions
            }

            if ($canModifyQuestion) {
                $question->content         = $text;
                $question->question_type   = $type;
                $question->answers         = json_encode(array_values($answers));
                $question->correct_answers = json_encode(array_values($correct));
                $question->category_id     = $quiz->category_id; // keep category in sync with quiz

                // Handle image upload for this question (key: image_q_{idx})
                $fileKey = 'image_q_' . $idx;
                if (isset($files[$fileKey])) {
                    // Delete old image if exists
                    if ($question->image_path) {
                        Storage::disk('public')->delete($question->image_path);
                    }
                    $path = $files[$fileKey]->store('question_images', 'public');
                    $question->image_path = $path;
                } elseif (isset($q['remove_image']) && $q['remove_image']) {
                    if ($question->image_path) {
                        Storage::disk('public')->delete($question->image_path);
                    }
                    $question->image_path = null;
                }

                $question->save();
            }

            $syncData[$question->id] = ['question_order' => $order++];
        }

        $quiz->questions()->sync($syncData);
        $quiz->update(['questions_count' => count($syncData)]);
    }
}