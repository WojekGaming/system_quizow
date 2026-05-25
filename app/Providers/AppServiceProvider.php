<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Notifications\CustomVerifyEmail;
use App\Notifications\CustomResetPasswordEmail;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        VerifyEmail::toMailUsing(function ($notifiable, $verificationUrl) {
            return (new CustomVerifyEmail($verificationUrl))->toMail($notifiable);
        });

        ResetPassword::toMailUsing(function ($notifiable, $token) {
            return (new CustomResetPasswordEmail($token))->toMail($notifiable);
        });
    }
}