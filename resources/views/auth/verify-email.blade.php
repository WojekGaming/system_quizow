<x-guest-layout>

    <div class="auth-icon-badge" style="font-size:28px; width:60px; height:60px;">✉️</div>

    <h1 class="auth-title">{{ __('Sprawdź swoją skrzynkę odbiorczą') }}</h1>
    <p class="auth-subtitle">
        {{ __('Dziękujemy za rejestrację! Zanim zaczniemy, prosimy o weryfikację adresu email, klikając w link, który wysłaliśmy do Ciebie.') }}
    </p>

    {{-- Resent confirmation --}}
    @if (session('status') == 'verification-link-sent')
        <div class="auth-alert auth-alert--success">
            ✓ {{ __('Nowy link weryfikacyjny został wysłany na podany adres email.') }}
        </div>
    @endif

    {{-- Steps --}}
    <div class="auth-steps">
        <div class="auth-step">
            <div class="auth-step__num">1</div>
            <div class="auth-step__text">
                {{ __('Otwórz swoją') }} <strong>{{ __('skrzynkę odbiorczą') }}</strong> {{ __('i znajdź wiadomość od Quizzies') }}
            </div>
        </div>
        <div class="auth-step">
            <div class="auth-step__num">2</div>
            <div class="auth-step__text">
                {{ __('Kliknij') }} <strong>{{ __('link weryfikacyjny') }}</strong> {{ __('w wiadomości') }}
            </div>
        </div>
        <div class="auth-step">
            <div class="auth-step__num">3</div>
            <div class="auth-step__text">
                {{ __('Zostaniesz przekierowany z powrotem i Twoje konto zostanie') }} <strong>{{ __('aktywowane') }}</strong>
            </div>
        </div>
    </div>

    {{-- Resend --}}
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <x-primary-button class="auth-btn-primary auth-btn-primary--full">
            {{ __('Wyślij ponownie email weryfikacyjny') }}
        </x-primary-button>
    </form>

    <div class="auth-divider"></div>

    <div class="auth-footer" style="display:flex; align-items:center; justify-content:center; gap:6px;">
        {{ __('Nie te konto?') }}
        <form method="POST" action="{{ route('logout') }}" style="display:inline; margin:0;">
            @csrf
            <button type="submit" class="auth-btn-link">
                {{ __('Wyloguj się') }}
            </button>
        </form>
    </div>

</x-guest-layout>