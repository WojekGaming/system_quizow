<x-app-layout>
    <x-slot name="title">Moje quizy</x-slot>

    <div class="dash-container">

        <div class="dash-welcome">
            <div>
                <h1 class="dash-welcome__title">Moje <span>quizy</span></h1>
                <p class="dash-welcome__sub">Wszystkie quizy które stworzyłeś</p>
            </div>
            <a href="{{ route('quizzes.create') }}" class="dash-cta-btn">＋ Stwórz nowy quiz</a>
        </div>

        @if(session('success'))
            <div class="dash-alert dash-alert--success">{{ session('success') }}</div>
        @endif

        @if($quizzes->isEmpty())
            <div class="dash-panel" style="text-align:center; padding: 3rem;">
                <div style="font-size:48px; margin-bottom:1rem;">📝</div>
                <p style="color:rgba(255,255,255,0.5); margin-bottom:1.5rem;">Nie masz jeszcze żadnych quizów.</p>
                <a href="{{ route('quizzes.create') }}" class="dash-cta-btn">Stwórz pierwszy quiz</a>
            </div>
        @else
            <div class="qlist-grid">
                @foreach($quizzes as $quiz)
                    <div class="qlist-card">
                        <div class="qlist-card__head">
                            <div class="qlist-card__title">{{ $quiz->title }}</div>
                            <div style="display:flex; gap:6px; align-items:center;">
                                @if($quiz->is_premium)
                                    <span class="dash-badge dash-badge--premium">Premium</span>
                                @endif
                                @if(!$quiz->is_active)
                                    <span class="dash-badge dash-badge--inactive">Nieaktywny</span>
                                @endif
                            </div>
                        </div>

                        @if($quiz->description)
                            <p class="qlist-card__desc">{{ Str::limit($quiz->description, 80) }}</p>
                        @endif

                        <div class="qlist-card__meta">
                            <span>📋 {{ $quiz->questions_count }} pytań</span>
                            @if($quiz->category)
                                <span>🏷 {{ $quiz->category->name }}</span>
                            @endif
                            <span>▶️ {{ $quiz->attempts_count }} podejść</span>
                            @if($quiz->average_rating > 0)
                                <span>⭐ {{ number_format($quiz->average_rating, 1) }}</span>
                            @endif
                        </div>

                        <div class="qlist-card__foot">
                            <span class="qlist-card__date">{{ $quiz->created_at->format('d.m.Y') }}</span>
                            <div style="display:flex; gap:8px;">
                                <a href="{{ route('quizzes.edit', $quiz) }}" class="dash-icon-btn" title="Edytuj">✏️</a>
                                <form method="POST" action="{{ route('quizzes.destroy', $quiz) }}"
                                      onsubmit="return confirm('Na pewno usunąć ten quiz?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="dash-icon-btn" title="Usuń"
                                            style="border-color:rgba(220,50,50,0.2);">🗑</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top:1.5rem;">
                {{ $quizzes->links() }}
            </div>
        @endif

    </div>
</x-app-layout>
