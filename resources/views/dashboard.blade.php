<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="dash-container">

        <div class="dash-welcome">
            <div>
                <h1 class="dash-welcome__title">
                    Cześć, <span>{{ auth()->user()->name }}</span> 👋
                </h1>
                <p class="dash-welcome__sub">Gotowy na kolejne wyzwanie?</p>
            </div>
            <a href="{{ route('quizzes.create') }}" class="dash-cta-btn">
                ＋ Stwórz quiz
            </a>
        </div>

        <div class="dash-stats">
            <div class="dash-stat">
                <div class="dash-stat__icon">🎯</div>
                <div class="dash-stat__value">{{ $stats['quizzes_created'] }}</div>
                <div class="dash-stat__label">Stworzone quizy</div>
            </div>
            <div class="dash-stat">
                <div class="dash-stat__icon">✅</div>
                <div class="dash-stat__value">{{ $stats['quizzes_played'] }}</div>
                <div class="dash-stat__label">Rozwiązane quizy</div>
            </div>
            <div class="dash-stat">
                <div class="dash-stat__icon">📊</div>
                <div class="dash-stat__value">{{ $stats['avg_score'] }}%</div>
                <div class="dash-stat__label">Średni wynik</div>
            </div>
            <div class="dash-stat">
                <div class="dash-stat__icon">👥</div>
                <div class="dash-stat__value">{{ $stats['friends_count'] }}</div>
                <div class="dash-stat__label">Znajomi</div>
            </div>
        </div>

        <div class="dash-grid">

            <div class="dash-panel">
                <div class="dash-panel__head">
                    <h2 class="dash-panel__title">Moje quizy</h2>
                    <a href="{{ route('quizzes.create') }}" class="dash-panel__link">+ Nowy quiz →</a>
                </div>

                @forelse($myQuizzes as $quiz)
                    <div class="dash-quiz-item">
                        <div class="dash-quiz-item__info">
                            <div class="dash-quiz-item__title">{{ $quiz->title }}</div>
                            <div class="dash-quiz-item__meta">
                                {{ $quiz->questions_count }} pytań
                                @if($quiz->category) · {{ $quiz->category->name }} @endif
                                @if($quiz->is_premium) · <span class="dash-badge dash-badge--premium">Premium</span> @endif
                                @if(!$quiz->is_active) · <span class="dash-badge dash-badge--inactive">Nieaktywny</span> @endif
                            </div>
                        </div>
                        <div class="dash-quiz-item__actions">
                            <a href="{{ route('quizzes.edit', $quiz) }}" class="dash-icon-btn" title="Edytuj">✏️</a>
                            <a href="{{ route('quiz.show', $quiz) }}" class="dash-icon-btn" title="Zagraj">▶️</a>
                        </div>
                    </div>
                @empty
                    <div class="dash-empty">
                        <div class="dash-empty__icon">📝</div>
                        <p>Nie masz jeszcze żadnych quizów.</p>
                        <a href="{{ route('quizzes.create') }}" class="dash-empty__link">Stwórz pierwszy quiz →</a>
                    </div>
                @endforelse
            </div>

            <div class="dash-side">

                <div class="dash-panel">
                    <div class="dash-panel__head">
                        <h2 class="dash-panel__title">Ostatnie quizy</h2>
                    </div>

                    @forelse($recentAttempts as $attempt)
                        <div class="dash-attempt-item">
                            <div class="dash-attempt-item__info">
                                <div class="dash-attempt-item__title">{{ $attempt->quiz->title ?? 'Quiz usunięty' }}</div>
                                <div class="dash-attempt-item__date">{{ $attempt->finished_at?->diffForHumans() }}</div>
                            </div>
                            <div class="dash-attempt-item__score {{ $attempt->max_points > 0 && ($attempt->score_points / $attempt->max_points) >= 0.7 ? 'dash-attempt-item__score--good' : 'dash-attempt-item__score--bad' }}">
                                {{ $attempt->score_points }}/{{ $attempt->max_points }}
                            </div>
                        </div>
                    @empty
                        <div class="dash-empty">
                            <div class="dash-empty__icon">🎮</div>
                            <p>Nie rozwiązałeś jeszcze żadnego quizu.</p>
                        </div>
                    @endforelse
                </div>

                <div class="dash-panel">
                    <div class="dash-panel__head">
                        <h2 class="dash-panel__title">Znajomi</h2>
                    </div>

                    @if($pendingRequests->count() > 0)
                        <div class="dash-friend-section-label">Zaproszenia ({{ $pendingRequests->count() }})</div>
                        @foreach($pendingRequests as $req)
                            <div class="dash-friend-item">
                                <div class="dash-friend-item__avatar">{{ strtoupper(substr($req->requester->name, 0, 1)) }}</div>
                                <div class="dash-friend-item__name">{{ $req->requester->name }}</div>
                                <div class="dash-friend-item__actions">
                                    <form method="POST" action="#" style="display:inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="dash-friend-btn dash-friend-btn--accept" title="Akceptuj">✓</button>
                                    </form>
                                    <form method="POST" action="#" style="display:inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="dash-friend-btn dash-friend-btn--reject" title="Odrzuć">✕</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                        <div class="dash-panel__divider"></div>
                    @endif

                    @forelse($friends as $friend)
                        <div class="dash-friend-item">
                            <div class="dash-friend-item__avatar">{{ strtoupper(substr($friend->name, 0, 1)) }}</div>
                            <div class="dash-friend-item__name">{{ $friend->name }}</div>
                            <div class="dash-friend-item__quizzes">{{ $friend->quizzes_count ?? 0 }} quizów</div>
                        </div>
                    @empty
                        <div class="dash-empty">
                            <div class="dash-empty__icon">👥</div>
                            <p>Nie masz jeszcze znajomych.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>

    </div>
</x-app-layout>