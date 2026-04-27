<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $quiz->title }} — Quizzies</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-body">

<div class="app-bg">
    <div class="app-bg-blob app-bg-blob--1"></div>
    <div class="app-bg-blob app-bg-blob--2"></div>
    <div class="app-bg-grid"></div>
</div>

<div style="position:relative;z-index:1;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:24px;">

<form method="POST" action="{{ route('quiz.submit', $quiz) }}" id="quizForm" style="width:min(720px,100%);">
@csrf
<input type="hidden" name="time_spent" id="timeSpentInput" value="0">
<div id="answersContainer"></div>

@php $questions = $quiz->questions; $total = $questions->count(); @endphp

@foreach($questions as $i => $question)
@php
    $answers = $question->answers;
    if (is_string($answers)) $answers = json_decode($answers, true);
    $answers = $answers ?? [];
    $type = $question->question_type;
    if ($type === 'true_false') {
        $answers = array_slice($answers, 0, 2);
        if (empty($answers[0])) $answers[0] = 'Prawda';
        if (empty($answers[1])) $answers[1] = 'Fałsz';
    }
    $letters = ['A','B','C','D'];
@endphp

<div class="question-slide" id="slide-{{ $i }}" style="{{ $i > 0 ? 'display:none;' : '' }}">

    {{-- Header --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;gap:12px;flex-wrap:wrap;">
        <span style="color:#ff6b00;font-weight:700;font-size:14px;letter-spacing:.2px;">{{ $quiz->title }}</span>
        <div style="text-align:center;min-width:140px;">
            <div style="color:rgba(255,255,255,0.4);font-size:12px;letter-spacing:.15px;">Czas rozwiązywania</div>
            <div id="timerDisplay" style="color:#fff;font-size:16px;font-weight:800;">00:00</div>
        </div>
        <span style="color:rgba(255,255,255,0.4);font-size:13px;">Pytanie {{ $i + 1 }} / {{ $total }}</span>
    </div>

    {{-- Progress --}}
    <div style="height:4px;background:rgba(255,255,255,0.07);border-radius:2px;margin-bottom:28px;overflow:hidden;">
        <div style="height:100%;width:{{ round((($i+1)/$total)*100) }}%;background:linear-gradient(135deg,#ff6b00,#ff9a3c);border-radius:2px;"></div>
    </div>

    <div class="dash-panel" style="margin-bottom:0;">

        @if($question->image_path)
            <img src="{{ asset('storage/' . $question->image_path) }}" alt="Zdjęcie pytania"
                 style="width:100%;max-height:220px;object-fit:cover;border-radius:12px;margin-bottom:20px;">
        @endif

        {{-- Question --}}
        <div style="font-size:20px;font-weight:700;color:#fff;line-height:1.35;margin-bottom:22px;">
            {{ $question->content }}
        </div>

        {{-- Answers --}}
        <div style="display:grid;gap:10px;{{ $type === 'true_false' ? 'grid-template-columns:1fr 1fr;' : '' }}margin-bottom:24px;">
            @foreach($answers as $idx => $answer)
            @if(!empty(trim($answer ?? '')))
            <div class="qp-answer"
                 data-idx="{{ $idx }}"
                 data-qid="{{ $question->id }}"
                 data-type="{{ $type }}"
                 onclick="selectAnswer(this)"
                 style="
                     padding:13px 16px;border-radius:12px;
                     background:rgba(255,255,255,0.04);
                     border:1px solid rgba(255,255,255,0.08);
                     cursor:pointer;transition:.2s ease;
                     color:rgba(255,255,255,0.8);font-size:15px;
                     display:flex;align-items:center;gap:12px;
                     user-select:none;
                 "
                 onmouseover="if(!this.classList.contains('active')){this.style.borderColor='rgba(255,107,0,0.4)';this.style.background='rgba(255,107,0,0.06)';}"
                 onmouseout="if(!this.classList.contains('active')){this.style.borderColor='rgba(255,255,255,0.08)';this.style.background='rgba(255,255,255,0.04)';}">
                <span class="qp-letter" style="
                    width:28px;height:28px;border-radius:50%;
                    background:rgba(255,255,255,0.08);
                    display:inline-flex;align-items:center;justify-content:center;
                    font-weight:800;font-size:12px;flex-shrink:0;transition:.2s;
                ">{{ $letters[$idx] ?? chr(65+$idx) }}</span>
                {{ $answer }}
            </div>
            @endif
            @endforeach
        </div>

        {{-- Actions --}}
        <div style="display:flex;justify-content:space-between;align-items:center;padding-top:16px;border-top:1px solid rgba(255,255,255,0.06);">
            @if($i > 0)
                <button type="button" onclick="goTo({{ $i - 1 }})"
                        style="background:none;border:none;color:rgba(255,255,255,0.4);font-size:14px;cursor:pointer;font-family:'Outfit',sans-serif;transition:.2s;padding:0;"
                        onmouseover="this.style.color='rgba(255,255,255,0.8)'" onmouseout="this.style.color='rgba(255,255,255,0.4)'">
                    ← Poprzednie
                </button>
            @else
                <a href="{{ url('/') }}" style="color:rgba(255,255,255,0.4);font-size:14px;text-decoration:none;transition:.2s;"
                   onmouseover="this.style.color='rgba(255,255,255,0.8)'" onmouseout="this.style.color='rgba(255,255,255,0.4)'">
                    ← Wyjdź
                </a>
            @endif

            @if($i < $total - 1)
                <button type="button" onclick="goTo({{ $i + 1 }})" class="dash-cta-btn">
                    Dalej →
                </button>
            @else
                <button type="submit" onclick="prepareSubmit()" class="dash-cta-btn">
                    Zakończ quiz ✓
                </button>
            @endif
        </div>

    </div>
</div>
@endforeach

</form>
</div>

<style>
.qp-answer.active {
    background: rgba(255,107,0,0.12) !important;
    border-color: #ff6b00 !important;
    color: #fff !important;
}
.qp-answer.active .qp-letter {
    background: #ff6b00 !important;
    color: #fff !important;
}
</style>

<script>
const selected = {};
let startTime = Date.now();
let timerInterval;

function formatDuration(seconds) {
    const minutes = Math.floor(seconds / 60).toString().padStart(2, '0');
    const secs = (seconds % 60).toString().padStart(2, '0');
    return `${minutes}:${secs}`;
}

function updateTimer() {
    const elapsed = Math.max(0, Math.floor((Date.now() - startTime) / 1000));
    const display = document.getElementById('timerDisplay');
    const input = document.getElementById('timeSpentInput');
    if (display) display.textContent = formatDuration(elapsed);
    if (input) input.value = elapsed;
}

window.addEventListener('load', () => {
    updateTimer();
    timerInterval = setInterval(updateTimer, 1000);
});

function selectAnswer(el) {
    const qid  = el.dataset.qid;
    const idx  = parseInt(el.dataset.idx);
    const type = el.dataset.type;
    const siblings = document.querySelectorAll(`.qp-answer[data-qid="${qid}"]`);

    if (type === 'multiple_choice') {
        if (!selected[qid]) selected[qid] = [];
        if (selected[qid].includes(idx)) {
            selected[qid] = selected[qid].filter(i => i !== idx);
            el.classList.remove('active');
        } else {
            selected[qid].push(idx);
            el.classList.add('active');
        }
    } else {
        selected[qid] = [idx];
        siblings.forEach(s => s.classList.remove('active'));
        el.classList.add('active');
    }
}

function goTo(i) {
    document.querySelectorAll('.question-slide').forEach(s => s.style.display = 'none');
    document.getElementById('slide-' + i).style.display = 'block';
    window.scrollTo(0, 0);
}

function prepareSubmit() {
    updateTimer();
    if (timerInterval) {
        clearInterval(timerInterval);
    }
    const container = document.getElementById('answersContainer');
    container.innerHTML = '';
    for (const [qid, idxArr] of Object.entries(selected)) {
        idxArr.forEach(idx => {
            const input = document.createElement('input');
            input.type  = 'hidden';
            input.name  = `answers[${qid}][]`;
            input.value = idx;
            container.appendChild(input);
        });
    }
}
</script>

</body>
</html>