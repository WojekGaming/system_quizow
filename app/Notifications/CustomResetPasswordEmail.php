<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPasswordEmail
{
    public function __construct(protected string $token) {}

    public function toMail($notifiable): MailMessage
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Resetowanie hasła – Quizzies')
            ->view('emails.reset-password', [
                'user' => $notifiable,
                'resetUrl' => $resetUrl,
            ]);
    }
}