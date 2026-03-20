<x-guest-layout>

    <div class="auth-icon-badge">🔒</div>

    <h1 class="auth-title">{{ __('Confirm access') }}</h1>
    <p class="auth-subtitle">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </p>

    <div class="auth-secure-badge">
        <span>🛡</span> {{ __('Secure verification required') }}
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="auth-alert auth-alert--error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        {{-- Password --}}
        <div class="auth-field">
            <x-input-label for="password" :value="__('Password')" class="auth-label" />
            <x-text-input
                id="password"
                class="auth-input"
                type="password"
                name="password"
                required
                autofocus
                autocomplete="current-password"
                placeholder="••••••••"
            />
            <x-input-error :messages="$errors->get('password')" class="auth-error" />
        </div>

        {{-- Submit --}}
        <div class="mt-6">
            <x-primary-button class="auth-btn-primary auth-btn-primary--full">
                {{ __('Confirm & continue') }}
            </x-primary-button>
        </div>
    </form>

</x-guest-layout>