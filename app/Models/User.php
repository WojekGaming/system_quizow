<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'premium_until',
        'banned_until',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'premium_until'     => 'datetime',
            'banned_until'      => 'datetime',
            'deleted_at'        => 'datetime',
        ];
    }

    // ── Helpers ──────────────────────────────────────────

    public function isPremium(): bool
    {
        return $this->premium_until !== null && $this->premium_until->isFuture();
    }

    public function isBanned(): bool
    {
        return $this->banned_until !== null && $this->banned_until->isFuture();
    }

    // ── Relacje ───────────────────────────────────────────

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function userNotifications()
    {
        return $this->hasMany(UserNotification::class);
    }

    public function sentFriendRequests()
    {
        return $this->hasMany(Friendship::class, 'requester_id');
    }

    public function receivedFriendRequests()
    {
        return $this->hasMany(Friendship::class, 'addressee_id');
    }

    public function acceptedFriends()
    {
        return User::whereIn('id', function ($q) {
                $q->select('requester_id')
                  ->from('friendships')
                  ->where('addressee_id', $this->id)
                  ->where('status', 'accepted');
            })
            ->orWhereIn('id', function ($q) {
                $q->select('addressee_id')
                  ->from('friendships')
                  ->where('requester_id', $this->id)
                  ->where('status', 'accepted');
            });
    }
}