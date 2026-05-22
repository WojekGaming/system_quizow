<x-guest-layout>

    <div class="auth-icon-badge">🔒</div>

    <h1 class="auth-title">{{ __('Potwierdź dostęp') }}</h1>
    <p class="auth-subtitle">
        {{ __('To jest bezpieczna strefa aplikacji. Proszę potwierdzić hasło przed kontynuowaniem.') }}
    </p>

    <div class="auth-secure-badge">
        <span>🛡</span> {{ __('Wymagana weryfikacja bezpieczeństwa') }}
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
            <x-input-label for="password" :value="__('Hasło')" class="auth-label" />
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
                {{ __('Potwierdź i kontynuuj') }}
            </x-primary-button>
        </div>
    </form>

</x-guest-layout>