<x-guest-layout>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="auth-alert auth-alert--error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Name --}}
        <div class="auth-field">
            <x-input-label for="name" :value="__('Imię')" class="auth-label" />
            <x-text-input
                id="name"
                class="auth-input"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
                placeholder="John Doe"
            />
            <x-input-error :messages="$errors->get('imię')" class="auth-error" />
        </div>

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
                autocomplete="username"
                placeholder="you@example.com"
            />
            <x-input-error :messages="$errors->get('email')" class="auth-error" />
        </div>

        {{-- Password --}}
        <div class="auth-field">
            <x-input-label for="password" :value="__('Hasło')" class="auth-label" />
            <x-text-input
                id="password"
                class="auth-input"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="••••••••"
            />
            <p class="auth-hint">{{ __('Minimum 8 znaków') }}</p>
            <x-input-error :messages="$errors->get('password')" class="auth-error" />
        </div>

        {{-- Confirm Password --}}
        <div class="auth-field">
            <x-input-label for="password_confirmation" :value="__('Potwierdź hasło')" class="auth-label" />
            <x-text-input
                id="password_confirmation"
                class="auth-input"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="••••••••"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="auth-error" />
        </div>

        {{-- Submit --}}
        <div class="mt-6">
            <x-primary-button class="auth-btn-primary auth-btn-primary--full">
                {{ __('Stwórz konto') }}
            </x-primary-button>
        </div>
    </form>

    <div class="auth-divider"></div>

    <p class="auth-footer">
        {{ __('Masz już konto?') }}
        <a href="{{ route('login') }}" class="auth-link">{{ __('Zaloguj się') }}</a>
    </p>

</x-guest-layout>