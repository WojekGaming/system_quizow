<x-guest-layout>

    <div class="auth-icon-badge">🔑</div>

    <h1 class="auth-title">{{ __('Set new password') }}</h1>
    <p class="auth-subtitle">
        {{ __('Choose a strong new password for your Quizzies account.') }}
    </p>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="auth-alert auth-alert--error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        {{-- Token --}}
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- Email --}}
        <div class="auth-field">
            <x-input-label for="email" :value="__('Email')" class="auth-label" />
            <x-text-input
                id="email"
                class="auth-input"
                type="email"
                name="email"
                :value="old('email', $request->email)"
                required
                autofocus
                autocomplete="username"
                placeholder="you@example.com"
            />
            <x-input-error :messages="$errors->get('email')" class="auth-error" />
        </div>

        {{-- New Password --}}
        <div class="auth-field">
            <x-input-label for="password" :value="__('New Password')" class="auth-label" />
            <x-text-input
                id="password"
                class="auth-input"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="••••••••"
            />
            <p class="auth-hint">{{ __('Minimum 8 characters') }}</p>
            <x-input-error :messages="$errors->get('password')" class="auth-error" />
        </div>

        {{-- Confirm Password --}}
        <div class="auth-field">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="auth-label" />
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
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>

    <div class="auth-divider"></div>

    <p class="auth-footer">
        {{ __('Remember your password?') }}
        <a href="{{ route('login') }}" class="auth-link">{{ __('Back to login') }}</a>
    </p>

</x-guest-layout>