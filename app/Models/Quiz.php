<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'is_premium',
        'is_active',
        'questions_count',
        'average_rating',
        'ratings_count',
    ];

    protected $casts = [
        'is_premium'           => 'boolean',
        'is_active'            => 'boolean',
        'average_rating'       => 'decimal:2',
        'deleted_by_admin_at'  => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'quiz_question')
                    ->withPivot('question_order', 'points')
                    ->orderBy('quiz_question.question_order');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function ratings()
    {
        return $this->hasMany(QuizRating::class);
    }

    public function reports()
    {
        return $this->hasMany(QuizReport::class);
    }
}
