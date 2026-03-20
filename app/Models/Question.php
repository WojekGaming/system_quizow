<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'creator_id',
        'content',
        'image_path',
        'question_type',
        'answers',
        'correct_answers',
        'is_public_base',
    ];

    protected $casts = [
        'answers'         => 'array',
        'correct_answers' => 'array',
        'is_public_base'  => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class, 'quiz_question')
                    ->withPivot('question_order', 'points');
    }
}
