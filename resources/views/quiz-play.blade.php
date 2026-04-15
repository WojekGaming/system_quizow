<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $quiz->title }} — Quizzies</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-body">
    <h1 style="color:red;position:fixed;top:10px;left:10px;z-index:9999;">
        QUIZ PLAY TEST
    </h1>

<div class="app-bg">
    <div class="app-bg-blob app-bg-blob--1"></div>
    <div class="app-bg-blob app-bg-blob--2"></div>
    <div class="app-bg-grid"></div>
</div>

<div style="position:relative;z-index:1;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:24px;">

<form method="POST" action="{{ route('quiz.submit', $quiz) }}" id="quizForm" style="width:min(720px,100%);">
@csrf

<div id="answersContainer"></div>

@php
    $questions = $quiz->questions;
    $total = $questions->count();
@endphp

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

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
        <span style="color:#ff6b00;font-weight:700;font-size:14px;">{{ $quiz->title }}</span>
        <span style="color:rgba(255,255,255,0.4);font-size:13px;">Pytanie {{ $i + 1 }} / {{ $total }}</span>
    </div>

    <div style="height:4px;background:rgba(255,255,255,0.07);border-radius:2px;margin-bottom:28px;overflow:hidden;">
        <div style="height:100%;width:{{ round((($i+1)/$total)*100) }}%;background:linear-gradient(135deg,#ff6b00,#ff9a3c);"></div>
    </div>

    <div class="dash-panel">

        @if($question->image_path)
            <img src="{{ asset('storage/' . $question->image_path) }}" style="width:100%;max-height:220px;object-fit:cover;border-radius:12px;margin-bottom:20px;">
        @endif

        <div style="font-size:20px;font-weight:700;color:#fff;margin-bottom:22px;">
            {{ $question->content }}
        </div>

        <div style="display:grid;gap:10px;{{ $type === 'true_false' ? 'grid-template-columns:1fr 1fr;' : '' }}">
            @foreach($answers as $idx => $answer)
                @if(!empty(trim($answer ?? '')))
                <div class="qp-answer"
                     data-idx="{{ $idx }}"
                     data-qid="{{ $question->id }}"
                     data-type="{{ $type }}"
                     onclick="selectAnswer(this)"
                     style="padding:13px 16px;border-radius:12px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);cursor:pointer;color:rgba(255,255,255,0.8);">

                    <span class="qp-letter">{{ $letters[$idx] ?? chr(65+$idx) }}</span>
                    {{ $answer }}
                </div>
                @endif
            @endforeach
        </div>

        <div style="display:flex;justify-content:space-between;margin-top:20px;align-items:center;">

            {{-- LEWA STRONA (może być pusta albo info) --}}
            <div>
                {{-- opcjonalnie coś możesz dać --}}
            </div>

            {{-- PRAWA STRONA: akcje --}}
            <div style="display:flex;gap:12px;align-items:center;">

                @if($i > 0)
                    <button type="button" onclick="goTo({{ $i - 1 }})">← Poprzednie</button>
                @else
                    <a href="{{ url('/') }}">← Wyjdź</a>
                @endif

                @if($i < $total - 1)
                    <button type="button" onclick="goTo({{ $i + 1 }})">Dalej →</button>
                @else
                    <button type="submit" onclick="prepareSubmit()">Zakończ quiz ✓</button>
                @endif

                {{-- 🚨 REPORT --}}
                <a href="{{ route('quiz.report', $quiz->id) }}"
                onclick="event.preventDefault(); document.getElementById('report-form').submit();"
                style="color:rgba(255,100,100,0.9);font-size:13px;cursor:pointer;text-decoration:none;">
                    🚨 Zgłoś
                </a>

                <form id="report-form" method="POST" action="{{ route('quiz.report', $quiz->id) }}" style="display:none;">
                    @csrf
                </form>

            </div>

        </div>
        

        <div style="display:flex;justify-content:space-between;margin-top:20px;">
            @if($i > 0)
                <button type="button" onclick="goTo({{ $i - 1 }})">← Poprzednie</button>
            @else
                <a href="{{ url('/') }}">← Wyjdź</a>
            @endif

            @if($i < $total - 1)
                <button type="button" onclick="goTo({{ $i + 1 }})">Dalej →</button>
            @else
                <button type="submit" onclick="prepareSubmit()">Zakończ quiz ✓</button>
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
</style>

<script>
const selected = {};

function selectAnswer(el) {
    const qid = el.dataset.qid;
    const idx = parseInt(el.dataset.idx);
    const type = el.dataset.type;

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
        document.querySelectorAll(`[data-qid="${qid}"]`).forEach(e => e.classList.remove('active'));
        el.classList.add('active');
    }
}

function goTo(i) {
    document.querySelectorAll('.question-slide').forEach(s => s.style.display = 'none');
    document.getElementById('slide-' + i).style.display = 'block';
}

function prepareSubmit() {
    const container = document.getElementById('answersContainer');
    container.innerHTML = '';

    for (const [qid, arr] of Object.entries(selected)) {
        arr.forEach(idx => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `answers[${qid}][]`;
            input.value = idx;
            container.appendChild(input);
        });
    }
}
</script>

</body>
</html>