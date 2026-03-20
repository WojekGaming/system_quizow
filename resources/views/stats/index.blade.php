<x-app-layout>
    <x-slot name="title">Statystyki</x-slot>

    <div class="dash-container">

        <div class="dash-welcome">
            <div>
                <h1 class="dash-welcome__title">Twoje <span>statystyki</span></h1>
                <p class="dash-welcome__sub">Podsumowanie Twojej aktywności na Quizzies</p>
            </div>
        </div>

        {{-- Stats row --}}
        <div class="dash-stats" style="grid-template-columns: repeat(5, 1fr);">
            <div class="dash-stat">
                <div class="dash-stat__icon">✅</div>
                <div class="dash-stat__value">{{ $totalPlayed }}</div>
                <div class="dash-stat__label">Rozwiązane quizy</div>
            </div>
            <div class="dash-stat">
                <div class="dash-stat__icon">🎯</div>
                <div class="dash-stat__value">{{ $totalCreated }}</div>
                <div class="dash-stat__label">Stworzone quizy</div>
            </div>
            <div class="dash-stat">
                <div class="dash-stat__icon">📊</div>
                <div class="dash-stat__value">{{ round($avgScore ?? 0) }}%</div>
                <div class="dash-stat__label">Średni wynik</div>
            </div>
            <div class="dash-stat">
                <div class="dash-stat__icon">🏆</div>
                <div class="dash-stat__value">{{ round($bestScore ?? 0) }}%</div>
                <div class="dash-stat__label">Najlepszy wynik</div>
            </div>
            <div class="dash-stat">
                <div class="dash-stat__icon">⏱</div>
                <div class="dash-stat__value">{{ gmdate('H:i', $totalTime) }}</div>
                <div class="dash-stat__label">Czas quizów</div>
            </div>
        </div>

        <div class="dash-grid">

            {{-- Recent attempts --}}
            <div class="dash-panel">
                <div class="dash-panel__head">
                    <h2 class="dash-panel__title">Ostatnie podejścia</h2>
                </div>

                @forelse($recentAttempts as $attempt)
                    <div class="dash-attempt-item">
                        <div class="dash-attempt-item__info">
                            <div class="dash-attempt-item__title">
                                {{ $attempt->quiz->title ?? 'Quiz usunięty' }}
                            </div>
                            <div class="dash-attempt-item__date">
                                {{ $attempt->finished_at?->format('d.m.Y H:i') }}
                                · {{ gmdate('i:s', $attempt->duration_seconds) }} min
                            </div>
                        </div>
                        <div class="dash-attempt-item__score
                            {{ $attempt->max_points > 0 && ($attempt->score_points / $attempt->max_points) >= 0.7
                                ? 'dash-attempt-item__score--good'
                                : 'dash-attempt-item__score--bad' }}">
                            {{ $attempt->max_points > 0
                                ? round($attempt->score_points / $attempt->max_points * 100)
                                : 0 }}%
                        </div>
                    </div>
                @empty
                    <div class="dash-empty">
                        <div class="dash-empty__icon">🎮</div>
                        <p>Nie rozwiązałeś jeszcze żadnego quizu.</p>
                    </div>
                @endforelse
            </div>

            {{-- Right: distribution + my top quizzes --}}
            <div class="dash-side">

                {{-- Score distribution --}}
                <div class="dash-panel">
                    <div class="dash-panel__head">
                        <h2 class="dash-panel__title">Rozkład wyników</h2>
                    </div>
                    @php
                        $labels = ['0–20%', '21–40%', '41–60%', '61–80%', '81–100%'];
                        $maxVal = max(array_merge($distribution, [1]));
                    @endphp
                    <div style="display:flex; flex-direction:column; gap:10px;">
                        @foreach($distribution as $i => $count)
                            <div style="display:flex; align-items:center; gap:10px;">
                                <span style="font-size:12px; color:rgba(255,255,255,0.4); width:55px; flex-shrink:0;">
                                    {{ $labels[$i] }}
                                </span>
                                <div style="flex:1; background:rgba(255,255,255,0.05); border-radius:6px; height:10px; overflow:hidden;">
                                    <div style="height:100%; width:{{ $maxVal > 0 ? round($count / $maxVal * 100) : 0 }}%;
                                        background: linear-gradient(90deg, #ff6b00, #ff8c33);
                                        border-radius:6px; transition: width 0.3s;"></div>
                                </div>
                                <span style="font-size:12px; color:rgba(255,255,255,0.4); width:20px; text-align:right;">
                                    {{ $count }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- My top quizzes --}}
                <div class="dash-panel">
                    <div class="dash-panel__head">
                        <h2 class="dash-panel__title">Moje popularne quizy</h2>
                        <a href="{{ route('quizzes.index') }}" class="dash-panel__link">Wszystkie →</a>
                    </div>
                    @forelse($myQuizzes as $quiz)
                        <div class="dash-quiz-item">
                            <div class="dash-quiz-item__info">
                                <div class="dash-quiz-item__title">{{ $quiz->title }}</div>
                                <div class="dash-quiz-item__meta">
                                    {{ $quiz->attempts_count }} podejść
                                    @if($quiz->category) · {{ $quiz->category->name }} @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="dash-empty" style="padding:1rem 0;">
                            <p>Brak quizów.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>

    </div>
</x-app-layout>
