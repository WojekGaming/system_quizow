<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Category;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Quiz::where('is_active', 1)
            ->whereNull('deleted_at')
            ->with(['user', 'category'])
            ->withCount('attempts');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('author')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->author . '%');
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('premium')) {
            $query->where('is_premium', $request->premium);
        }
        if ($request->filled('min_rating')) {
            $query->where('average_rating', '>=', $request->min_rating);
        }

        switch ($request->get('sort', 'newest')) {
            case 'oldest':         $query->oldest(); break;
            case 'questions_desc': $query->orderByDesc('questions_count'); break;
            case 'questions_asc':  $query->orderBy('questions_count'); break;
            case 'rating':         $query->orderByDesc('average_rating'); break;
            case 'popular':        $query->orderByDesc('attempts_count'); break;
            default:               $query->latest();
        }

        $quizzes    = $query->take(10)->get();
        $categories = Category::orderBy('name')->get();

        return view('welcome', compact('quizzes', 'categories'));
    }
}