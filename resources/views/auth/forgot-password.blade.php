<x-guest-layout>

    <div class="auth-icon-badge">🔑</div>

    <h1 class="auth-title">{{ __('Zresetuj hasło') }}</h1>
    <p class="auth-subtitle">
        {{ __('Wprowadź swój email, a wyślemy Ci link do zresetowania hasła.') }}
    </p>

    {{-- Session Status --}}
    <x-auth-session-status class="auth-alert auth-alert--success" :status="session('status')" />

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="auth-alert auth-alert--error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        {{-- Email --}}
        <div class="auth-field">
            <x-input-label for="email" :value="__('Email')" class="auth-label" />
            <x-text-input
                id="email"
                class="auth-input"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
                placeholder="you@example.com"
            />
            <x-input-error :messages="$errors->get('email')" class="auth-error" />
        </div>

        {{-- Submit --}}
        <div class="mt-6">
            <x-primary-button class="auth-btn-primary auth-btn-primary--full">
                {{ __('Wyślij link do zresetowania hasła') }}
            </x-primary-button>
        </div>
    </form>

    <div class="auth-divider"></div>

    <p class="auth-footer">
        {{ __('Pamiętasz swoje hasło?') }}
        <a href="{{ route('login') }}" class="auth-link">{{ __('Wróć do logowania') }}</a>
    </p>

</x-guest-layout>