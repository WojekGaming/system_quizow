<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $loginType = filter_var($this->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (!Auth::attempt([$loginType => $this->input('login'), 'password' => $this->input('password')], $this->boolean('remember'))) {
            throw ValidationException::withMessages([
                'login' => 'Podane dane logowania są nieprawidłowe.',
            ]);
        }

        $this->session()->regenerate();
    }
}