<x-app-layout>
    <x-slot name="title">Panel Admina</x-slot>

    <div class="dash-container" style="max-width:1300px;">

        <div class="dash-welcome">
            <div>
                <h1 class="dash-welcome__title">Panel <span>Admina</span> 🛡</h1>
                <p class="dash-welcome__sub">Zarządzaj quizami, zgłoszeniami i użytkownikami</p>
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

        {{-- Tabs --}}
        <div style="display:flex;gap:6px;margin-bottom:1.5rem;flex-wrap:wrap;">
            <a href="{{ route('admin.quizzes') }}"
               style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;border-radius:10px;font-size:14px;font-weight:600;text-decoration:none;transition:all .2s;
               {{ $tab === 'quizzes' ? 'background:linear-gradient(135deg,#ff6b00,#ff8c33);color:#fff;box-shadow:0 4px 14px rgba(255,107,0,.3);' : 'background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);color:rgba(255,255,255,.6);' }}">
                📋 Quizy
            </a>

            <a href="{{ route('admin.reports') }}"
               style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;border-radius:10px;font-size:14px;font-weight:600;text-decoration:none;transition:all .2s;
               {{ $tab === 'reports' ? 'background:linear-gradient(135deg,#ff6b00,#ff8c33);color:#fff;box-shadow:0 4px 14px rgba(255,107,0,.3);' : 'background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);color:rgba(255,255,255,.6);' }}">
                🚨 Zgłoszenia
            </a>

            <a href="{{ route('admin.users') }}"
               style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;border-radius:10px;font-size:14px;font-weight:600;text-decoration:none;transition:all .2s;
               {{ in_array($tab, ['users', 'user_quizzes']) ? 'background:linear-gradient(135deg,#ff6b00,#ff8c33);color:#fff;box-shadow:0 4px 14px rgba(255,107,0,.3);' : 'background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);color:rgba(255,255,255,.6);' }}">
                👥 Użytkownicy
            </a>
        </div>

        {{-- ══════════════ TAB: QUIZZES ══════════════ --}}
        @if($tab === 'quizzes')

            <div class="dash-panel" style="margin-bottom:1.2rem;">
                <form method="GET" action="{{ route('admin.quizzes') }}">
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:10px;align-items:end;">

                        <div class="auth-field" style="margin:0">
                            <label class="auth-label">Tytuł / szukaj</label>
                            <input class="auth-input" type="text" name="search" value="{{ request('search') }}" placeholder="Tytuł quizu...">
                        </div>

                        <div class="auth-field" style="margin:0">
                            <label class="auth-label">Kategoria</label>
                            <select class="auth-input" name="category" style="cursor:pointer;background:#1a1a1a;">
                                <option value="">Wszystkie</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="auth-field" style="margin:0">
                            <label class="auth-label">Premium</label>
                            <select class="auth-input" name="premium" style="cursor:pointer;background:#1a1a1a;">
                                <option value="">Wszystkie</option>
                                <option value="1" {{ request('premium') === '1' ? 'selected' : '' }}>Tak</option>
                                <option value="0" {{ request('premium') === '0' ? 'selected' : '' }}>Nie</option>
                            </select>
                        </div>

                        <div class="auth-field" style="margin:0">
                            <label class="auth-label">Min. pytań</label>
                            <input class="auth-input" type="number" name="min_questions" value="{{ request('min_questions') }}" placeholder="np. 5" min="0">
                        </div>

                        <div class="auth-field" style="margin:0">
                            <label class="auth-label">Min. ocena</label>
                            <input class="auth-input" type="number" name="min_rating" value="{{ request('min_rating') }}" placeholder="np. 3.5" step="0.1" min="0" max="5">
                        </div>

                        <div class="auth-field" style="margin:0">
                            <label class="auth-label">Data od</label>
                            <input class="auth-input" type="date" name="date_from" value="{{ request('date_from') }}">
                        </div>

                        <div class="auth-field" style="margin:0">
                            <label class="auth-label">Data do</label>
                            <input class="auth-input" type="date" name="date_to" value="{{ request('date_to') }}">
                        </div>

                        <div class="auth-field" style="margin:0">
                            <label class="auth-label">Użytkownik</label>
                            <input class="auth-input" type="text" name="user" value="{{ request('user') }}" placeholder="Nazwa...">
                        </div>

                        <div class="auth-field" style="margin:0">
                            <label class="auth-label">Sortuj</label>
                            <select class="auth-input" name="sort" style="cursor:pointer;background:#1a1a1a;">
                                <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Najnowsze</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Najstarsze</option>
                                <option value="questions" {{ request('sort') === 'questions' ? 'selected' : '' }}>Najwięcej pytań</option>
                                <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Najwyższa ocena</option>
                            </select>
                        </div>

                        <div style="display:flex;gap:7px;align-items:flex-end;">
                            <button type="submit" class="dash-cta-btn" style="padding:10px 16px;font-size:13px;">
                                Filtruj
                            </button>

                            @if(request()->hasAny(['search','category','premium','min_questions','min_rating','date_from','date_to','user','sort']))
                                <a href="{{ route('admin.quizzes') }}"
                                   style="padding:10px 12px;border:1px solid rgba(255,255,255,.1);border-radius:9px;color:rgba(255,255,255,.5);text-decoration:none;font-size:13px;">
                                    ✕
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <div class="dash-panel">
                <div class="dash-panel__head">
                    <h2 class="dash-panel__title">Quizy ({{ $quizzes->total() }})</h2>
                </div>

                @forelse($quizzes as $quiz)
                    <div class="dash-quiz-item" style="padding:14px 0;">
                        <div class="dash-quiz-item__info" style="flex:1;">
                            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                <div class="dash-quiz-item__title">{{ $quiz->title }}</div>
                                @if($quiz->is_premium)
                                    <span class="dash-badge dash-badge--premium">Premium</span>
                                @endif
                            </div>

                            <div class="dash-quiz-item__meta" style="margin-top:4px;">
                                👤 {{ $quiz->user->name ?? '—' }}
                                · 📋 {{ $quiz->questions_count }} pytań
                                · ▶ {{ $quiz->attempts_count }} podejść
                                @if($quiz->category) · 🏷 {{ $quiz->category->name }} @endif
                                @if($quiz->average_rating > 0) · ⭐ {{ number_format($quiz->average_rating, 1) }} @endif
                                · 📅 {{ $quiz->created_at->format('d.m.Y') }}
                            </div>
                        </div>

                        <form method="POST" action="{{ route('admin.quiz.delete', $quiz) }}"
                              onsubmit="return confirm('Na pewno usunąć quiz: {{ addslashes($quiz->title) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dash-icon-btn" title="Usuń" style="border-color:rgba(220,50,50,.2);">
                                🗑
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="dash-empty">
                        <div class="dash-empty__icon">📋</div>
                        <p>Brak quizów pasujących do filtrów.</p>
                    </div>
                @endforelse

                <div style="margin-top:1rem;">
                    {{ $quizzes->links() }}
                </div>
            </div>

        @endif

        {{-- ══════════════ TAB: REPORTS ══════════════ --}}
        @if($tab === 'reports')

            <div class="dash-panel" style="margin-bottom:1.2rem;">
                <form method="GET" action="{{ route('admin.reports') }}">
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:10px;align-items:end;">

                        <div class="auth-field" style="margin:0">
                            <label class="auth-label">Tytuł / szukaj</label>
                            <input class="auth-input" type="text" name="search" value="{{ request('search') }}" placeholder="Tytuł quizu...">
                        </div>

                        <div class="auth-field" style="margin:0">
                            <label class="auth-label">Status</label>
                            <select class="auth-input" name="status" style="cursor:pointer;background:#1a1a1a;">
                                <option value="">Wszystkie</option>
                                <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>Nowe</option>
                                <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>Przeglądane</option>
                                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Rozwiązane</option>
                            </select>
                        </div>

                        <div class="auth-field" style="margin:0">
                            <label class="auth-label">Kategoria</label>
                            <select class="auth-input" name="category" style="cursor:pointer;background:#1a1a1a;">
                                <option value="">Wszystkie</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="auth-field" style="margin:0">
                            <label class="auth-label">Premium</label>
                            <select class="auth-input" name="premium" style="cursor:pointer;background:#1a1a1a;">
                                <option value="">Wszystkie</option>
                                <option value="1" {{ request('premium') === '1' ? 'selected' : '' }}>Tak</option>
                                <option value="0" {{ request('premium') === '0' ? 'selected' : '' }}>Nie</option>
                            </select>
                        </div>

                        <div class="auth-field" style="margin:0">
                            <label class="auth-label">Min. pytań</label>
                            <input class="auth-input" type="number" name="min_questions" value="{{ request('min_questions') }}" placeholder="np. 5" min="0">
                        </div>

                        <div class="auth-field" style="margin:0">
                            <label class="auth-label">Min. ocena</label>
                            <input class="auth-input" type="number" name="min_rating" value="{{ request('min_rating') }}" placeholder="np. 3.5" step="0.1" min="0" max="5">
                        </div>

                        <div class="auth-field" style="margin:0">
                            <label class="auth-label">Data od</label>
                            <input class="auth-input" type="date" name="date_from" value="{{ request('date_from') }}">
                        </div>

                        <div class="auth-field" style="margin:0">
                            <label class="auth-label">Data do</label>
                            <input class="auth-input" type="date" name="date_to" value="{{ request('date_to') }}">
                        </div>

                        <div class="auth-field" style="margin:0">
                            <label class="auth-label">Użytkownik</label>
                            <input class="auth-input" type="text" name="user" value="{{ request('user') }}" placeholder="Nazwa...">
                        </div>

                        <div class="auth-field" style="margin:0">
                            <label class="auth-label">Sortuj</label>
                            <select class="auth-input" name="sort" style="cursor:pointer;background:#1a1a1a;">
                                <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Najnowsze zgłoszenia</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Najstarsze zgłoszenia</option>
                                <option value="quiz_newest" {{ request('sort') === 'quiz_newest' ? 'selected' : '' }}>Najnowsze quizy</option>
                                <option value="quiz_oldest" {{ request('sort') === 'quiz_oldest' ? 'selected' : '' }}>Najstarsze quizy</option>
                                <option value="questions" {{ request('sort') === 'questions' ? 'selected' : '' }}>Najwięcej pytań</option>
                                <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Najwyższa ocena</option>
                            </select>
                        </div>

                        <div style="display:flex;gap:7px;align-items:flex-end;">
                            <button type="submit" class="dash-cta-btn" style="padding:10px 16px;font-size:13px;">
                                Filtruj
                            </button>

                            @if(request()->hasAny(['search','status','category','premium','min_questions','min_rating','date_from','date_to','user','sort']))
                                <a href="{{ route('admin.reports') }}"
                                   style="padding:10px 12px;border:1px solid rgba(255,255,255,.1);border-radius:9px;color:rgba(255,255,255,.5);text-decoration:none;font-size:13px;">
                                    ✕
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <div class="dash-panel">
                <div class="dash-panel__head">
                    <h2 class="dash-panel__title">Zgłoszenia ({{ $reports->total() }})</h2>
                </div>

                @forelse($reports as $report)
                    <div class="dash-quiz-item" style="padding:14px 0;align-items:flex-start;">
                        <div style="flex:1;">
                            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                <div class="dash-quiz-item__title">
                                    {{ $report->quiz->title ?? 'Quiz usunięty' }}
                                </div>

                                <span class="dash-badge" style="
                                    {{ $report->status === 'new' ? 'background:rgba(242,165,65,.1);color:#f2a541;border:1px solid rgba(242,165,65,.2);' : '' }}
                                    {{ $report->status === 'resolved' ? 'background:rgba(74,222,128,.1);color:#4ade80;border:1px solid rgba(74,222,128,.2);' : '' }}
                                    {{ $report->status === 'reviewed' ? 'background:rgba(93,188,216,.1);color:#5bc8d8;border:1px solid rgba(93,188,216,.2);' : '' }}
                                ">
                                    {{ ucfirst($report->status) }}
                                </span>

                                @if($report->quiz?->is_premium)
                                    <span class="dash-badge dash-badge--premium">Premium</span>
                                @endif
                            </div>

                            <div class="dash-quiz-item__meta" style="margin-top:4px;">
                                👤 zgłosił: {{ $report->reportedBy->name ?? '—' }}
                                · 📅 zgłoszono: {{ $report->created_at->format('d.m.Y H:i') }}

                                @if($report->quiz)
                                    · 📋 {{ $report->quiz->questions_count }} pytań

                                    @if($report->quiz->average_rating > 0)
                                        · ⭐ {{ number_format($report->quiz->average_rating, 1) }}
                                    @endif

                                    @if($report->quiz->user)
                                        · autor: {{ $report->quiz->user->name }}
                                    @endif

                                    @if($report->quiz->category)
                                        · 🏷 {{ $report->quiz->category->name }}
                                    @endif

                                    · quiz utworzony: {{ $report->quiz->created_at->format('d.m.Y') }}
                                @endif

                                @if($report->reason)
                                    · 💬 {{ \Illuminate\Support\Str::limit($report->reason, 60) }}
                                @endif
                            </div>
                        </div>

                        <div style="display:flex;gap:7px;flex-shrink:0;">
                            @if($report->status !== 'resolved')
                                <form method="POST" action="{{ route('admin.report.resolve', $report) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="dash-icon-btn" title="Rozwiąż" style="border-color:rgba(74,222,128,.2);">
                                        ✓
                                    </button>
                                </form>
                            @endif

                            @if($report->quiz && !$report->quiz->trashed())
                                <form method="POST" action="{{ route('admin.quiz.delete', $report->quiz) }}"
                                      onsubmit="return confirm('Usunąć quiz?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dash-icon-btn" title="Usuń quiz" style="border-color:rgba(220,50,50,.2);">
                                        🗑
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="dash-empty">
                        <div class="dash-empty__icon">🚨</div>
                        <p>Brak zgłoszeń.</p>
                    </div>
                @endforelse

                <div style="margin-top:1rem;">
                    {{ $reports->links() }}
                </div>
            </div>

        @endif

        {{-- ══════════════ TAB: USERS ══════════════ --}}
        @if($tab === 'users')

            <div class="dash-panel" style="margin-bottom:1.2rem;">
                <form method="GET" action="{{ route('admin.users') }}">
                    <div style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
                        <div class="auth-field" style="margin:0;flex:1;min-width:200px;">
                            <label class="auth-label">Szukaj użytkownika</label>
                            <input class="auth-input" type="text" name="search" value="{{ request('search') }}" placeholder="Nazwa lub email...">
                        </div>

                        <button type="submit" class="dash-cta-btn" style="padding:10px 16px;font-size:13px;">
                            Szukaj
                        </button>

                        @if(request('search'))
                            <a href="{{ route('admin.users') }}"
                               style="padding:10px 12px;border:1px solid rgba(255,255,255,.1);border-radius:9px;color:rgba(255,255,255,.5);text-decoration:none;font-size:13px;">
                                ✕
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="dash-panel">
                <div class="dash-panel__head">
                    <h2 class="dash-panel__title">Użytkownicy z usuniętymi quizami ({{ $users->total() }})</h2>
                </div>

                @forelse($users as $u)
                    <div class="dash-quiz-item" style="padding:14px 0;">
                        <div class="dash-friend-item__avatar">
                            {{ strtoupper(substr($u->name, 0, 1)) }}
                        </div>

                        <div style="flex:1;margin-left:10px;">
                            <div class="dash-quiz-item__title">{{ $u->name }}</div>

                            <div class="dash-quiz-item__meta">
                                {{ $u->email }}
                                · 🗑 {{ $u->deleted_quizzes_count }} usuniętych quizów

                                @if($u->isBanned())
                                    · <span style="color:#f87171;">🔒 ban do {{ $u->banned_until->format('d.m.Y') }}</span>
                                @endif
                            </div>
                        </div>

                        <div style="display:flex;gap:7px;flex-shrink:0;align-items:center;">
                            <a href="{{ route('admin.user.quizzes', $u) }}" class="dash-icon-btn" title="Pokaż quizy">
                                👁
                            </a>

                            <form method="POST" action="{{ route('admin.user.ban', $u) }}"
                                  style="display:flex;gap:5px;align-items:center;"
                                  onsubmit="return confirm('Zbanować {{ addslashes($u->name) }}?')">
                                @csrf
                                @method('PATCH')

                                <input type="number" name="days" min="1" max="3650" placeholder="dni"
                                       class="auth-input" style="width:70px;padding:7px 9px;font-size:12px;">

                                <button type="submit" class="dash-icon-btn" title="Banuj" style="border-color:rgba(242,165,65,.25);">
                                    🔒
                                </button>
                            </form>

                            @if($u->isBanned())
                                <form method="POST" action="{{ route('admin.user.unban', $u) }}">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit" class="dash-icon-btn" title="Zdejmij ban" style="border-color:rgba(74,222,128,.2);">
                                        🔓
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="dash-empty">
                        <div class="dash-empty__icon">👥</div>
                        <p>Brak użytkowników z usuniętymi quizami.</p>
                    </div>
                @endforelse

                <div style="margin-top:1rem;">
                    {{ $users->links() }}
                </div>
            </div>

        @endif

        {{-- ══════════════ TAB: USER QUIZZES ══════════════ --}}
        @if($tab === 'user_quizzes')

            <div style="margin-bottom:1rem;">
                <a href="{{ route('admin.users') }}" class="auth-btn-link">
                    ← Wróć do użytkowników
                </a>
            </div>

            <div class="dash-panel">
                <div class="dash-panel__head">
                    <h2 class="dash-panel__title">Usunięte quizy: {{ $user->name }}</h2>
                    <span style="font-size:13px;color:rgba(255,255,255,.35);">
                        {{ $quizzes->count() }} quizów
                    </span>
                </div>

                @forelse($quizzes as $quiz)
                    <div class="dash-quiz-item" style="padding:14px 0;">
                        <div class="dash-quiz-item__info">
                            <div class="dash-quiz-item__title">{{ $quiz->title }}</div>

                            <div class="dash-quiz-item__meta">
                                📋 {{ $quiz->questions_count }} pytań

                                @if($quiz->category)
                                    · 🏷 {{ $quiz->category->name }}
                                @endif

                                @if($quiz->deleted_at)
                                    · 🗑 usunięty {{ $quiz->deleted_at->format('d.m.Y H:i') }}
                                @endif

                                @if($quiz->deleted_by_admin_at)
                                    · <span style="color:#f87171;">przez admina</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="dash-empty">
                        <div class="dash-empty__icon">📋</div>
                        <p>Brak usuniętych quizów.</p>
                    </div>
                @endforelse
            </div>

        @endif

    </div>
</x-app-layout>