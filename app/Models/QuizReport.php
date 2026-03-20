<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizReport extends Model
{
    protected $fillable = [
        'quiz_id',
        'reported_by_user_id',
        'reason',
        'status',
        'reviewed_by_admin_id',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by_user_id');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by_admin_id');
    }
}
