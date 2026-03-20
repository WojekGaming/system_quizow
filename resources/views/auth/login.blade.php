<x-guest-layout>

    {{-- Session Status --}}
    <x-auth-session-status class="auth-session-status" :status="session('status')" />

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="auth-alert auth-alert--error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email or Username --}}
        <div class="auth-field">
            <x-input-label for="login" :value="__('Email lub nazwa użytkownika')" class="auth-label" />
            <x-text-input
                id="login"
                class="auth-input"
                type="text"
                name="login"
                :value="old('login')"
                required
                autofocus
                autocomplete="username"
                placeholder="Email lub nazwa użytkownika"
            />
            <x-input-error :messages="$errors->get('login')" class="auth-error" />
        </div>

        {{-- Password --}}
        <div class="auth-field">
            <x-input-label for="password" :value="__('Password')" class="auth-label" />
            <x-text-input
                id="password"
                class="auth-input"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
            />
            <x-input-error :messages="$errors->get('password')" class="auth-error" />
        </div>

        {{-- Remember Me + Forgot Password --}}
        <div class="auth-actions-row">
            <label class="auth-remember">
                <input id="remember_me" type="checkbox" name="remember" />
                <span>{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-btn-link">
                    {{ __('Reset password') }}
                </a>
            @endif
        </div>

        {{-- Submit --}}
        <div class="mt-6">
            <x-primary-button class="auth-btn-primary auth-btn-primary--full">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <div class="auth-divider"></div>

    <p class="auth-footer">
        {{ __("Don't have an account?") }}
        <a href="{{ route('register') }}" class="auth-link">{{ __('Create one') }}</a>
    </p>

</x-guest-layout>