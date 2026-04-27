<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizPlayController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AdminController;

// ── Public ────────────────────────────────────────────────
Route::get('/', [WelcomeController::class, 'index'])->name('home');

Route::get('/quiz/{quiz}',         [QuizPlayController::class, 'show'])->name('quiz.show');
Route::post('/quiz/{quiz}/submit', [QuizPlayController::class, 'submit'])->name('quiz.submit');

// ── Banned page ───────────────────────────────────────────
Route::get('/banned', function () {
    return view('banned');
})->middleware('auth')->name('banned');

// ── Admin ─────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',            [AdminController::class, 'quizzes'])->name('quizzes');
    Route::get('/reports',     [AdminController::class, 'reports'])->name('reports');
    Route::get('/users',       [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}/quizzes', [AdminController::class, 'userQuizzes'])->name('user.quizzes');
    Route::delete('/quizzes/{quiz}',           [AdminController::class, 'deleteQuiz'])->name('quiz.delete');
    Route::patch('/reports/{report}/resolve',  [AdminController::class, 'resolveReport'])->name('report.resolve');
    Route::patch('/users/{user}/ban',          [AdminController::class, 'banUser'])->name('user.ban');
    Route::patch('/users/{user}/unban',        [AdminController::class, 'unbanUser'])->name('user.unban');
});

// ── Auth + verified ───────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Quizzes
    Route::get('/quizzes',             [QuizController::class, 'index'])->name('quizzes.index');
    Route::get('/quizzes/create',      [QuizController::class, 'create'])->name('quizzes.create');
    Route::post('/quizzes',            [QuizController::class, 'store'])->name('quizzes.store');
    Route::get('/quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
    Route::patch('/quizzes/{quiz}',    [QuizController::class, 'update'])->name('quizzes.update');
    Route::delete('/quizzes/{quiz}',   [QuizController::class, 'destroy'])->name('quizzes.destroy');
    Route::post('/quiz/{quiz}/rate',   [QuizPlayController::class, 'rate'])->name('quiz.rate');

    // Friends
    Route::get('/friends',                        [FriendController::class, 'index'])->name('friends.index');
    Route::post('/friends/request/{user}',        [FriendController::class, 'sendRequest'])->name('friends.request');
    Route::patch('/friends/accept/{friendship}',  [FriendController::class, 'accept'])->name('friends.accept');
    Route::patch('/friends/reject/{friendship}',  [FriendController::class, 'reject'])->name('friends.reject');
    Route::delete('/friends/remove/{friendship}', [FriendController::class, 'remove'])->name('friends.remove');

    // Stats
    Route::get('/stats', [StatsController::class, 'index'])->name('stats.index');

    // Profile
    Route::get('/profile',         [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',       [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile',      [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';