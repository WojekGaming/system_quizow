<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Quizzies') }} — {{ $title ?? 'Dashboard' }}</title>
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

            <a href="{{ url('/') }}" class="app-nav__logo">
                <div class="app-nav__logo-icon">⚡</div>
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
                    <span class="app-nav__chevron">▾</span>

                    <div class="app-nav__dropdown" id="userDropdown">
                        <div class="app-nav__dropdown-header">
                            <div class="app-nav__dropdown-name">{{ auth()->user()->name }}</div>
                            <div class="app-nav__dropdown-email">{{ auth()->user()->email }}</div>
                            @if(auth()->user()->isPremium())
                                <span class="app-nav__premium-badge">⭐ Premium</span>
                            @endif
                        </div>
                        <div class="app-nav__dropdown-divider"></div>
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.quizzes') }}" class="app-nav__dropdown-item">🛡 Panel admina</a>
                            <div class="app-nav__dropdown-divider"></div>
                        @endif
                        <a href="{{ route('profile.edit') }}" class="app-nav__dropdown-item">✏️ Edycja profilu</a>
                        <div class="app-nav__dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="app-nav__dropdown-item app-nav__dropdown-item--danger">
                                🚪 Wyloguj
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <button class="app-nav__hamburger" id="mobileMenuToggle">☰</button>
        </div>

        <div class="app-nav__mobile" id="mobileMenu">
            <a href="{{ route('dashboard') }}" class="app-nav__mobile-link">Dashboard</a>
            <a href="{{ route('quizzes.index') }}" class="app-nav__mobile-link">Moje quizy</a>
            <a href="{{ route('friends.index') }}" class="app-nav__mobile-link">Znajomi</a>
            <a href="{{ route('stats.index') }}" class="app-nav__mobile-link">Statystyki</a>
            @if(auth()->user()->is_admin)
                <a href="{{ route('admin.quizzes') }}" class="app-nav__mobile-link">🛡 Panel admina</a>
            @endif
            <a href="{{ route('profile.edit') }}" class="app-nav__mobile-link">✏️ Edycja profilu</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="app-nav__mobile-link app-nav__mobile-link--danger">Wyloguj</button>
            </form>
        </div>
    </nav>

    <main class="app-main">
        {{ $slot }}
    </main>

    {{-- Quiz deleted notification popup --}}
    @auth
    @php
        $quizDeletedNotifications = \App\Models\UserNotification::where('user_id', auth()->id())
            ->where('type', 'quiz_deleted')
            ->whereNull('read_at')
            ->with('quiz')
            ->get();
    @endphp
    @if($quizDeletedNotifications->isNotEmpty())
        <div id="notifOverlay" style="
            position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:9998;
            display:flex;align-items:center;justify-content:center;padding:20px;
            backdrop-filter:blur(4px);
        ">
            <div style="
                background:#202427;border:1px solid rgba(255,255,255,0.1);border-radius:20px;
                padding:32px;max-width:480px;width:100%;box-shadow:0 24px 60px rgba(0,0,0,0.5);
                position:relative;z-index:9999;
            ">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
                    <div style="
                        width:42px;height:42px;border-radius:12px;flex-shrink:0;
                        background:rgba(248,113,113,0.15);border:1px solid rgba(248,113,113,0.3);
                        display:flex;align-items:center;justify-content:center;font-size:20px;
                    ">🛡</div>
                    <div>
                        <div style="font-size:17px;font-weight:700;color:#fff;">Powiadomienie od administracji</div>
                        <div style="font-size:13px;color:rgba(255,255,255,0.4);margin-top:2px;">Quizzies Team</div>
                    </div>
                </div>

                <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:24px;">
                    @foreach($quizDeletedNotifications as $notif)
                        <div style="
                            background:rgba(248,113,113,0.06);border:1px solid rgba(248,113,113,0.18);
                            border-radius:12px;padding:14px 16px;
                        ">
                            <div style="font-size:14px;color:rgba(255,255,255,0.85);line-height:1.5;">
                                {{ $notif->message }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <button onclick="dismissNotifications()" style="
                    width:100%;height:44px;background:linear-gradient(135deg,#ff6b00,#ff8c33);
                    color:#fff;border:none;border-radius:12px;font-family:'Outfit',sans-serif;
                    font-size:15px;font-weight:700;cursor:pointer;transition:.2s ease;
                " onmouseover="this.style.opacity='.88'" onmouseout="this.style.opacity='1'">
                    Rozumiem
                </button>
            </div>
        </div>

        <script>
        function dismissNotifications() {
            const ids = @json($quizDeletedNotifications->pluck('id'));
            fetch('{{ route('notifications.markRead') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ ids }),
            }).finally(() => {
                document.getElementById('notifOverlay').style.display = 'none';
            });
        }
        </script>
    @endif
    @endauth

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