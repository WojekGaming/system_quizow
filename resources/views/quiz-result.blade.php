<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wynik — {{ $quiz->title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-body">

<div class="app-bg">
    <div class="app-bg-blob app-bg-blob--1"></div>
    <div class="app-bg-blob app-bg-blob--2"></div>
    <div class="app-bg-grid"></div>
</div>

<div class="app-main">
<div class="dash-container" style="max-width:760px;">

    {{-- Score box --}}
    <div class="dash-panel" style="text-align:center;margin-bottom:1.5rem;">
        <div style="font-size:72px;font-weight:800;line-height:1;margin-bottom:12px;
            color:{{ $percentage >= 70 ? '#4ade80' : ($percentage >= 40 ? '#F2A541' : '#f87171') }};">
            {{ $percentage }}%
        </div>
        <div style="font-size:18px;font-weight:600;color:#fff;margin-bottom:6px;">
            {{ $scorePoints }} / {{ $maxPoints }} poprawnych odpowiedzi
        </div>
        @php
            $formattedTime = $timeSpent >= 3600
                ? gmdate('H:i:s', $timeSpent)
                : gmdate('i:s', $timeSpent);
        @endphp
        <div style="font-size:14px;color:rgba(255,255,255,0.4);margin-bottom:6px;">
            Czas rozwiązania: {{ $formattedTime }}
        </div>
        <div style="font-size:14px;color:rgba(255,255,255,0.4);">
            @if($percentage >= 70) Świetny wynik! 🎉
            @elseif($percentage >= 40) Nieźle, ale można lepiej 💪
            @else Spróbuj jeszcze raz 📚
            @endif
        </div>
    </div>

    {{-- Rating --}}
    <div class="dash-panel" style="margin-bottom:1.5rem;">
        <div style="font-size:16px;font-weight:700;color:#fff;margin-bottom:10px;">Oceń quiz</div>

        @if(Auth::check())
            <form id="quizRatingForm" action="{{ route('quiz.rate', $quiz) }}" method="POST" style="display:flex;flex-direction:column;gap:12px;align-items:center;">
                @csrf
                <input type="hidden" name="rating" id="ratingInput" value="{{ $currentRating ?? 0 }}">
                <div id="ratingStars" style="display:flex;gap:8px;flex-wrap:wrap;justify-content:center;">
                    @for($value = 1; $value <= 6; $value++)
                        <button type="button" class="rating-star" data-value="{{ $value }}" style="padding:10px 14px;border-radius:50%;border:1px solid rgba(255,255,255,0.12);background:rgba(255,255,255,0.05);color:#fff;font-size:14px;font-weight:700;cursor:pointer;transition:.2s;min-width:48px;text-align:center;">
                            {{ $value }}★
                        </button>
                    @endfor
                </div>
                <button type="submit" id="ratingSubmit" style="padding:11px 20px;border-radius:11px;border:none;background:#ff6b00;color:#fff;font-weight:700;cursor:pointer;transition:.2s;">Zapisz ocenę</button>
                <div id="ratingMessage" style="font-size:13px;color:rgba(255,255,255,0.6);"></div>
            </form>
        @else
            <div style="font-size:14px;color:rgba(255,255,255,0.6);">Zaloguj się, aby zostawić ocenę quizu.</div>
        @endif
    </div>

    {{-- Results --}}
    <div style="display:flex;flex-direction:column;gap:14px;margin-bottom:1.5rem;">
        @foreach($results as $i => $result)
        @php
            $answers = $result['answers'];
            if (is_string($answers)) $answers = json_decode($answers, true) ?? [];
            $letters = ['A','B','C','D'];
        @endphp
        <div class="dash-panel" style="
            {{ $result['is_correct']
                ? 'border-color:rgba(46,158,91,0.3);background:rgba(46,158,91,0.04);'
                : 'border-color:rgba(214,69,69,0.3);background:rgba(214,69,69,0.04);' }}
        ">
            <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:14px;">
                <span style="font-size:18px;flex-shrink:0;">{{ $result['is_correct'] ? '✅' : '❌' }}</span>
                <div style="font-size:15px;font-weight:600;color:#fff;line-height:1.4;">
                    {{ $i + 1 }}. {{ $result['question'] }}
                </div>
            </div>

            <div style="display:flex;flex-direction:column;gap:6px;padding-left:28px;">
                @foreach($answers as $idx => $answer)
                @if(!empty(trim($answer ?? '')))
                @php
                    $isCorrect  = in_array($idx, array_map('intval', $result['correct']));
                    $isSelected = in_array($idx, array_map('intval', $result['user_answer']));
                @endphp
                <div style="
                    padding:9px 13px;border-radius:9px;font-size:13px;
                    display:flex;align-items:center;gap:8px;
                    {{ $isCorrect
                        ? 'background:rgba(46,158,91,0.12);border:1px solid rgba(46,158,91,0.35);color:#4ade80;'
                        : ($isSelected && !$isCorrect
                            ? 'background:rgba(214,69,69,0.1);border:1px solid rgba(214,69,69,0.3);color:#f87171;'
                            : 'background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);color:rgba(255,255,255,0.5);') }}
                ">
                    <span style="font-weight:700;flex-shrink:0;">{{ $letters[$idx] ?? chr(65+$idx) }}.</span>
                    {{ $answer }}
                    @if($isCorrect) <span style="margin-left:auto;">✓</span> @endif
                    @if($isSelected && !$isCorrect) <span style="margin-left:auto;">✗</span> @endif
                </div>
                @endif
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    {{-- Actions --}}
    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
        <a href="{{ route('quiz.show', $quiz) }}" class="dash-cta-btn">🔄 Zagraj ponownie</a>
        <a href="{{ url('/') }}" style="
            padding:11px 22px;border-radius:11px;
            background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);
            color:rgba(255,255,255,0.7);font-weight:600;font-size:14px;
            text-decoration:none;transition:.2s;font-family:'Outfit',sans-serif;
            display:inline-flex;align-items:center;gap:6px;
        " onmouseover="this.style.background='rgba(255,255,255,0.09)'"
           onmouseout="this.style.background='rgba(255,255,255,0.05)'">
            ← Strona główna
        </a>
    </div>

</div>
</div>

<script>
(function() {
    const stars = document.querySelectorAll('.rating-star');
    const ratingInput = document.getElementById('ratingInput');
    const ratingMessage = document.getElementById('ratingMessage');
    const ratingForm = document.getElementById('quizRatingForm');

    if (!ratingForm) {
        return;
    }

    const selectedValue = parseInt(ratingInput.value, 10) || 0;
    let currentRating = selectedValue;

    function updateStars(value) {
        stars.forEach(star => {
            const starValue = parseInt(star.dataset.value, 10);
            if (starValue <= value) {
                star.style.background = '#ff6b00';
                star.style.color = '#111';
                star.style.borderColor = '#ff6b00';
            } else {
                star.style.background = 'rgba(255,255,255,0.05)';
                star.style.color = '#fff';
                star.style.borderColor = 'rgba(255,255,255,0.12)';
            }
        });
    }

    updateStars(currentRating);

    stars.forEach(star => {
        star.addEventListener('click', () => {
            currentRating = parseInt(star.dataset.value, 10);
            ratingInput.value = currentRating;
            updateStars(currentRating);
            ratingMessage.textContent = '';
        });
    });

    ratingForm.addEventListener('submit', event => {
        event.preventDefault();
        if (!currentRating || currentRating < 1) {
            ratingMessage.textContent = 'Wybierz ocenę od 1 do 6.';
            return;
        }

        const formData = new FormData(ratingForm);
        fetch(ratingForm.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.rating) {
                ratingMessage.textContent = 'Dziękujemy za ocenę!';
                ratingMessage.style.color = '#a3e635';
            } else {
                ratingMessage.textContent = 'Nie udało się zapisać oceny.';
                ratingMessage.style.color = '#f87171';
            }
        })
        .catch(() => {
            ratingMessage.textContent = 'Wystąpił błąd przy zapisie oceny.';
            ratingMessage.style.color = '#f87171';
        });
    });
})();
</script>

</body>
</html>