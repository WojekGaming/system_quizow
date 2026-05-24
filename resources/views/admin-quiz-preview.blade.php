<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Podgląd quizu — Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --cta: #FF6B00; --ctaHover: #E65F00;
            --panel: #2a2f33; --panel2: #31363b; --input: #3a4045;
            --border: #C7CACC;
        }
        body.app-body { font-family: 'Outfit', system-ui, sans-serif; }

        .qb-app { min-height: calc(100vh - 60px); display: grid; grid-template-columns: 300px 1fr; }

        /* Sidebar */
        .qb-sidebar {
            background: #202427; border-right: 1px solid rgba(255,255,255,0.08);
            padding: 22px 18px; display: flex; flex-direction: column; gap: 18px;
            position: sticky; top: 60px; height: calc(100vh - 60px); overflow-y: auto;
        }
        .qb-brand { display: flex; align-items: center; gap: 10px; padding-bottom: 4px; }
        .qb-brand-icon {
            width: 34px; height: 34px; background: linear-gradient(135deg, #ff6b00, #ff9a3c);
            border-radius: 9px; display: flex; align-items: center; justify-content: center;
            font-size: 17px; flex-shrink: 0;
        }
        .qb-brand-text { font-size: 20px; font-weight: 700; color: #fff; letter-spacing: -.3px; }
        .qb-brand-text span { color: #ff6b00; }
        .side-block {
            background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);
            border-radius: 16px; padding: 16px;
        }
        .side-block h2 { margin: 0 0 14px; font-size: 15px; font-weight: 600; color: #fff; }
        .meta-row { display: flex; flex-direction: column; gap: 8px; }
        .meta-item { font-size: 13px; color: rgba(255,255,255,0.55); line-height: 1.4; }
        .meta-item strong { color: rgba(255,255,255,0.85); font-weight: 600; }
        .meta-item.danger { color: #f87171; }
        .meta-item.warning { color: #f2a541; }
        .meta-item.success { color: #4ade80; }

        .q-nav-list { display: flex; flex-direction: column; gap: 6px; }
        .q-nav-item {
            background: #2b3034; border: 1px solid transparent; border-radius: 10px;
            padding: 9px 12px; cursor: pointer; transition: .2s ease; text-decoration: none;
            display: block;
        }
        .q-nav-item:hover { border-color: rgba(255,107,0,0.35); transform: translateY(-1px); }
        .q-nav-num { font-size: 11px; color: var(--border); text-transform: uppercase; letter-spacing: .4px; margin-bottom: 3px; }
        .q-nav-text { font-size: 13px; color: rgba(255,255,255,0.8); line-height: 1.3; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .qb-btn {
            height: 40px; padding: 0 16px; border-radius: 10px; border: 1px solid transparent;
            cursor: pointer; font-family: 'Outfit', sans-serif; font-weight: 600; font-size: 14px;
            transition: .2s ease; display: inline-flex; align-items: center; justify-content: center;
            gap: 6px; text-decoration: none;
        }
        .qb-btn-secondary { background: #494D50; color: white; border-color: #5c6166; }
        .qb-btn-secondary:hover { background: #5a5f64; }
        .qb-btn-danger { background: rgba(214,69,69,0.15); color: #f87171; border-color: rgba(214,69,69,0.3); }
        .qb-btn-danger:hover { background: rgba(214,69,69,0.25); }
        .qb-btn-full { width: 100%; }

        /* Main workspace */
        .qb-workspace { padding: 28px; display: flex; flex-direction: column; gap: 28px; }

        .admin-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,107,0,0.1); border: 1px solid rgba(255,107,0,0.25);
            color: #ff6b00; font-size: 12px; font-weight: 700; padding: 4px 10px;
            border-radius: 20px; letter-spacing: .3px;
        }
        .deleted-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(248,113,113,0.1); border: 1px solid rgba(248,113,113,0.25);
            color: #f87171; font-size: 12px; font-weight: 700; padding: 4px 10px;
            border-radius: 20px;
        }

        /* Question card */
        .q-card {
            background: var(--panel); border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px; padding: 24px; display: flex; flex-direction: column; gap: 18px;
            scroll-margin-top: 80px;
        }
        .q-card-header { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
        .q-card-num {
            font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.35);
            text-transform: uppercase; letter-spacing: .6px;
        }
        .q-type-badge {
            font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px;
            background: rgba(93,188,216,0.1); border: 1px solid rgba(93,188,216,0.2); color: #5bc8d8;
        }
        .q-text {
            font-size: 18px; font-weight: 600; color: #fff; line-height: 1.45;
        }
        .q-image { max-width: 100%; max-height: 280px; object-fit: contain; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08); }

        .answers-grid { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 12px; }
        .answer-tile {
            background: var(--panel2); border: 1px solid rgba(255,255,255,0.06);
            border-radius: 13px; padding: 14px; display: flex; align-items: flex-start; gap: 12px;
            transition: .2s ease;
        }
        .answer-tile.correct {
            border-color: rgba(46,158,91,0.45); background: rgba(46,158,91,0.07);
        }
        .answer-letter {
            width: 30px; height: 30px; border-radius: 50%; background: #494D50; flex-shrink: 0;
            display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;
            color: #fff;
        }
        .answer-tile.correct .answer-letter {
            background: rgba(46,158,91,0.6);
        }
        .answer-text { font-size: 14px; color: rgba(255,255,255,0.8); line-height: 1.4; padding-top: 5px; }
        .correct-label { font-size: 11px; color: #4ade80; font-weight: 700; margin-top: 3px; }

        .small-note { font-size: 12px; color: var(--border); }

        @media (max-width: 1100px) {
            .qb-app { grid-template-columns: 1fr; }
            .qb-sidebar { position: static; height: auto; border-right: none; border-bottom: 1px solid rgba(255,255,255,0.08); }
        }
        @media (max-width: 700px) {
            .qb-workspace { padding: 16px; }
            .answers-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body class="app-body">

<div class="app-bg">
    <div class="app-bg-blob app-bg-blob--1"></div>
    <div class="app-bg-blob app-bg-blob--2"></div>
    <div class="app-bg-grid"></div>
</div>

{{-- Navbar --}}
<nav class="app-nav">
    <div class="app-nav__inner">
        <a href="{{ url('/') }}" class="app-nav__logo">
            <div class="app-nav__logo-icon">⚡</div>
            <div class="app-nav__logo-text">Quiz<span>zies</span></div>
        </a>
        <div class="app-nav__right">
            <div class="app-nav__user" onclick="document.getElementById('userDropdown').classList.toggle('app-nav__dropdown--open')">
                <div class="app-nav__avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <span class="app-nav__username">{{ Auth::user()->name }}</span>
                <span class="app-nav__chevron">▾</span>
                <div class="app-nav__dropdown" id="userDropdown">
                    <div class="app-nav__dropdown-header">
                        <div class="app-nav__dropdown-name">{{ Auth::user()->name }}</div>
                        <div class="app-nav__dropdown-email">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="app-nav__dropdown-divider"></div>
                    <a href="{{ route('profile.edit') }}" class="app-nav__dropdown-item">👤 Profil</a>
                    <div class="app-nav__dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="app-nav__dropdown-item app-nav__dropdown-item--danger">🚪 Wyloguj</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="qb-app">

    {{-- Sidebar --}}
    <aside class="qb-sidebar">
        <div class="qb-brand">
            <div class="qb-brand-icon">🛡</div>
            <div class="qb-brand-text">Admin<span>Preview</span></div>
        </div>

        {{-- Quiz info --}}
        <div class="side-block">
            <h2>Info o quizie</h2>
            <div class="meta-row">
                <div class="meta-item"><strong>Tytuł:</strong> {{ $quiz->title }}</div>
                <div class="meta-item"><strong>Autor:</strong> {{ $quiz->user->name ?? '—' }}</div>
                <div class="meta-item"><strong>Email:</strong> {{ $quiz->user->email ?? '—' }}</div>
                @if($quiz->category)
                    <div class="meta-item"><strong>Kategoria:</strong> {{ $quiz->category->name }}</div>
                @endif
                <div class="meta-item"><strong>Pytań:</strong> {{ $quiz->questions_count }}</div>
                <div class="meta-item"><strong>Utworzony:</strong> {{ $quiz->created_at->format('d.m.Y H:i') }}</div>

                @if($quiz->trashed())
                    <div class="meta-item danger">
                        🗑 Usunięty: {{ $quiz->deleted_at->format('d.m.Y H:i') }}
                    </div>
                    @if($quiz->deleted_by_admin_at)
                        <div class="meta-item danger">🛡 Usunięty przez admina</div>
                    @endif
                @endif

                @if($quiz->reports_count > 0)
                    <div class="meta-item warning">🚨 Zgłoszeń: {{ $quiz->reports_count }}</div>
                @endif
            </div>
        </div>

        {{-- Question nav --}}
        <div class="side-block">
            <h2>Pytania ({{ $questions->count() }})</h2>
            <div class="q-nav-list">
                @foreach($questions as $i => $q)
                    <a href="#q-{{ $i + 1 }}" class="q-nav-item">
                        <div class="q-nav-num">Pytanie {{ $i + 1 }}</div>
                        <div class="q-nav-text">{{ $q->content }}</div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Actions --}}
        <div class="side-block">
            <h2>Akcje</h2>
            <div style="display:flex;flex-direction:column;gap:8px;">
                <a href="{{ route('admin.user.quizzes', $quiz->user) }}" class="qb-btn qb-btn-secondary qb-btn-full">
                    ← Wróć do quizów użytkownika
                </a>

                @if(!$quiz->trashed())
                    <form method="POST" action="{{ route('admin.quiz.delete', $quiz) }}"
                          onsubmit="return confirm('Na pewno usunąć quiz: {{ addslashes($quiz->title) }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="qb-btn qb-btn-danger qb-btn-full">
                            🗑 Usuń quiz
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </aside>

    {{-- Main --}}
    <main class="qb-workspace">

        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            <span class="admin-badge">🛡 Tryb admina — tylko odczyt</span>
            @if($quiz->trashed())
                <span class="deleted-badge">🗑 Quiz usunięty</span>
            @endif
            @if($quiz->reports_count > 0)
                <span style="display:inline-flex;align-items:center;gap:6px;background:rgba(242,165,65,0.1);border:1px solid rgba(242,165,65,0.25);color:#f2a541;font-size:12px;font-weight:700;padding:4px 10px;border-radius:20px;">
                    🚨 {{ $quiz->reports_count }} {{ $quiz->reports_count === 1 ? 'zgłoszenie' : 'zgłoszeń' }}
                </span>
            @endif
        </div>

        @if($questions->isEmpty())
            <div style="text-align:center;padding:60px 20px;color:rgba(255,255,255,0.3);">
                <div style="font-size:48px;margin-bottom:12px;">📭</div>
                <p style="font-size:16px;">Brak pytań do wyświetlenia.</p>
                <p style="font-size:13px;margin-top:8px;color:rgba(255,255,255,0.2);">Quiz mógł zostać usunięty przed wdrożeniem podglądu — powiązania z pytaniami nie są już dostępne.</p>
            </div>
        @else
            @php $letters = ['A','B','C','D']; @endphp

            @foreach($questions as $i => $q)
                @php
                    $answers = is_string($q->answers) ? json_decode($q->answers, true) : ($q->answers ?? []);
                    $correct = is_string($q->correct_answers) ? json_decode($q->correct_answers, true) : ($q->correct_answers ?? []);
                    $answers = array_values(array_filter($answers, fn($a) => $a !== ''));
                    $typeLabel = match($q->question_type) {
                        'multiple_choice' => 'Wiele poprawnych',
                        'true_false'      => 'Prawda / Fałsz',
                        default           => 'Jedna poprawna',
                    };
                @endphp

                <div class="q-card" id="q-{{ $i + 1 }}">
                    <div class="q-card-header">
                        <span class="q-card-num">Pytanie {{ $i + 1 }} / {{ $questions->count() }}</span>
                        <span class="q-type-badge">{{ $typeLabel }}</span>
                    </div>

                    <div class="q-text">{{ $q->content }}</div>

                    @if($q->image_path)
                        <img class="q-image"
                             src="{{ Storage::url($q->image_path) }}"
                             alt="Zdjęcie do pytania {{ $i + 1 }}">
                    @endif

                    <div class="answers-grid">
                        @foreach($answers as $idx => $answer)
                            @php $isCorrect = in_array($idx, $correct); @endphp
                            <div class="answer-tile {{ $isCorrect ? 'correct' : '' }}">
                                <div class="answer-letter">{{ $letters[$idx] ?? chr(65 + $idx) }}</div>
                                <div>
                                    <div class="answer-text">{{ $answer ?: '—' }}</div>
                                    @if($isCorrect)
                                        <div class="correct-label">✓ Poprawna</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif

    </main>
</div>

<script>
document.addEventListener('click', e => {
    const dropdown = document.getElementById('userDropdown');
    if (dropdown && !dropdown.parentElement.contains(e.target)) {
        dropdown.classList.remove('app-nav__dropdown--open');
    }
});
</script>

</body>
</html>