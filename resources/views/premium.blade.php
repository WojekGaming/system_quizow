<x-app-layout>
    <x-slot name="title">Premium</x-slot>

    <div class="dash-container" style="max-width:720px;">

        <div class="dash-welcome">
            <div>
                <h1 class="dash-welcome__title">Quizzies <span>Premium</span> ⚡</h1>
                <p class="dash-welcome__sub">
                    Premium pozwala tworzyć i rozwiązywać quizy premium.
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="dash-alert dash-alert--success" style="margin-bottom:1.2rem;">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="dash-alert dash-alert--error" style="margin-bottom:1.2rem;">
                ✕ {{ session('error') }}
            </div>
        @endif

        <div class="dash-panel">
            @if(auth()->user()->isPremium())
                <h2 class="dash-panel__title">Masz aktywne Premium</h2>

                <p style="color:rgba(255,255,255,.65);margin-top:10px;">
                    Premium jest aktywne do:
                    <strong style="color:#ff8c33;">
                        {{ auth()->user()->premium_until->format('d.m.Y H:i') }}
                    </strong>
                </p>

                <form method="POST" action="{{ route('premium.buy') }}" style="margin-top:20px;">
                    @csrf
                    <button type="submit" class="dash-cta-btn">
                        Przedłuż Premium o 30 dni
                    </button>
                </form>
            @else
                <h2 class="dash-panel__title">Aktywuj Premium</h2>

                <p style="color:rgba(255,255,255,.65);margin-top:10px;">
                    To jest wersja testowa. Kliknięcie przycisku aktywuje Premium bez prawdziwej płatności.
                </p>

                <form method="POST" action="{{ route('premium.buy') }}" style="margin-top:20px;">
                    @csrf
                    <button type="submit" class="dash-cta-btn">
                        Aktywuj Premium na 30 dni
                    </button>
                </form>
            @endif
        </div>

    </div>
</x-app-layout>