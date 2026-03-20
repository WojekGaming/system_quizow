<x-guest-layout>

    <div class="auth-icon-badge" style="font-size:28px; width:60px; height:60px;">✉️</div>

    <h1 class="auth-title">{{ __('Check your inbox') }}</h1>
    <p class="auth-subtitle">
        {{ __('Thanks for signing up! Before getting started, please verify your email address by clicking the link we sent you.') }}
    </p>

    {{-- Resent confirmation --}}
    @if (session('status') == 'verification-link-sent')
        <div class="auth-alert auth-alert--success">
            ✓ {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    {{-- Steps --}}
    <div class="auth-steps">
        <div class="auth-step">
            <div class="auth-step__num">1</div>
            <div class="auth-step__text">
                {{ __('Open your') }} <strong>{{ __('email inbox') }}</strong> {{ __('and find the message from Quizzies') }}
            </div>
        </div>
        <div class="auth-step">
            <div class="auth-step__num">2</div>
            <div class="auth-step__text">
                {{ __('Click the') }} <strong>{{ __('verification link') }}</strong> {{ __('inside the email') }}
            </div>
        </div>
        <div class="auth-step">
            <div class="auth-step__num">3</div>
            <div class="auth-step__text">
                {{ __('You\'ll be redirected back and your account will be') }} <strong>{{ __('activated') }}</strong>
            </div>
        </div>
    </div>

    {{-- Resend --}}
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <x-primary-button class="auth-btn-primary auth-btn-primary--full">
            {{ __('Resend Verification Email') }}
        </x-primary-button>
    </form>

    <div class="auth-divider"></div>

    <div class="auth-footer" style="display:flex; align-items:center; justify-content:center; gap:6px;">
        {{ __('Wrong account?') }}
        <form method="POST" action="{{ route('logout') }}" style="display:inline; margin:0;">
            @csrf
            <button type="submit" class="auth-btn-link">
                {{ __('Log out') }}
            </button>
        </form>
    </div>

</x-guest-layout>