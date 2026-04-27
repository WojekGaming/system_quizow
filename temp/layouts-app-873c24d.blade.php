<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Quizzies') }} ÔÇö {{ $title ?? 'Dashboard' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-body">

    <div class="app-bg">
        <div class="app-bg-blob app-bg-blob--1"></div>
        <div class="app-bg-blob app-bg-blob--2"></div>
        <div class="app-bg-grid"></div>
    </div>

    <nav class="app-nav">
        <div class="app-nav__inner">

            <a href="{{ route('dashboard') }}" class="app-nav__logo">
                <div class="app-nav__logo-icon">ÔÜí</div>
                <span class="app-nav__logo-text">Quizz<span>ies</span></span>
            </a>

            <div class="app-nav__links">
                <a href="{{ route('dashboard') }}"
                   class="app-nav__link {{ request()->routeIs('dashboard') ? 'app-nav__link--active' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('quizzes.index') }}"
                   class="app-nav__link {{ request()->routeIs('quizzes.*') ? 'app-nav__link--active' : '' }}">
                    Moje quizy
                </a>
                <a href="{{ route('friends.index') }}"
                   class="app-nav__link {{ request()->routeIs('friends.*') ? 'app-nav__link--active' : '' }}">
                    Znajomi
                    @php
                        $pendingCount = \App\Models\Friendship::where('addressee_id', auth()->id())
                            ->where('status','pending')->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="app-nav__badge">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('stats.index') }}"
                   class="app-nav__link {{ request()->routeIs('stats.*') ? 'app-nav__link--active' : '' }}">
                    Statystyki
                </a>
            </div>

            <div class="app-nav__right">
                <div class="app-nav__user" id="userDropdownToggle">
                    <div class="app-nav__avatar">
                        @if(auth()->user()->avatar_path)
                            <img src="{{ Storage::url(auth()->user()->avatar_path) }}"
                                 style="width:100%;height:100%;object-fit:cover;border-radius:8px;">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <span class="app-nav__username">{{ auth()->user()->name }}</span>
                    <span class="app-nav__chevron">Ôľż</span>

                    <div class="app-nav__dropdown" id="userDropdown">
                        <div class="app-nav__dropdown-header">
                            <div class="app-nav__dropdown-name">{{ auth()->user()->name }}</div>
                            <div class="app-nav__dropdown-email">{{ auth()->user()->email }}</div>
                            @if(auth()->user()->isPremium())
                                <span class="app-nav__premium-badge">ÔşÉ Premium</span>
                            @endif
                        </div>
                        <div class="app-nav__dropdown-divider"></div>
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.quizzes') }}" class="app-nav__dropdown-item">­čŤí Panel admina</a>
                            <div class="app-nav__dropdown-divider"></div>
                        @endif
                        <a href="{{ route('profile.edit') }}" class="app-nav__dropdown-item">ÔťĆ´ŞĆ Edycja profilu</a>
                        <div class="app-nav__dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="app-nav__dropdown-item app-nav__dropdown-item--danger">
                                ­čÜ¬ Wyloguj
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <button class="app-nav__hamburger" id="mobileMenuToggle">Ôś░</button>
        </div>

        <div class="app-nav__mobile" id="mobileMenu">
            <a href="{{ route('dashboard') }}" class="app-nav__mobile-link">Dashboard</a>
            <a href="{{ route('quizzes.index') }}" class="app-nav__mobile-link">Moje quizy</a>
            <a href="{{ route('friends.index') }}" class="app-nav__mobile-link">Znajomi</a>
            <a href="{{ route('stats.index') }}" class="app-nav__mobile-link">Statystyki</a>
            @if(auth()->user()->is_admin)
                <a href="{{ route('admin.quizzes') }}" class="app-nav__mobile-link">­čŤí Panel admina</a>
            @endif
            <a href="{{ route('profile.edit') }}" class="app-nav__mobile-link">ÔťĆ´ŞĆ Edycja profilu</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="app-nav__mobile-link app-nav__mobile-link--danger">Wyloguj</button>
            </form>
        </div>
    </nav>

    <main class="app-main">
        {{ $slot }}
    </main>

    <script>
        const toggle = document.getElementById('userDropdownToggle');
        const dropdown = document.getElementById('userDropdown');
        if (toggle && dropdown) {
            toggle.addEventListener('click', e => {
                e.stopPropagation();
                toggle.classList.toggle('open');
                dropdown.classList.toggle('app-nav__dropdown--open');
            });
            document.addEventListener('click', () => {
                toggle.classList.remove('open');
                dropdown.classList.remove('app-nav__dropdown--open');
            });
        }
        const hamburger = document.getElementById('mobileMenuToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        if (hamburger && mobileMenu) {
            hamburger.addEventListener('click', () => mobileMenu.classList.toggle('app-nav__mobile--open'));
        }
    </script>
</body>
</html>
