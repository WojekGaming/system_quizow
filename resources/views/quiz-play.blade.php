<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $quiz->title }} — Quizzies</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="app-body">

@if (session('success'))
    <div style="
        position:relative;
        z-index:5;
        width:min(720px, calc(100% - 32px));
        margin:20px auto 0;
        padding:12px 16px;
        background:rgba(46,158,91,0.15);
        color:#d8f5e3;
        border:1px solid rgba(46,158,91,0.35);
        border-radius:12px;
    ">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div style="
        position:relative;
        z-index:5;
        width:min(720px, calc(100% - 32px));
        margin:20px auto 0;
        padding:12px 16px;
        background:rgba(214,69,69,0.15);
        color:#ffe1e1;
        border:1px solid rgba(214,69,69,0.35);
        border-radius:12px;
    ">
        {{ session('error') }}
    </div>
@endif

<div class="app-bg">
    <div class="app-bg-blob app-bg-blob--1"></div>
    <div class="app-bg-blob app-bg-blob--2"></div>
    <div class="app-bg-grid"></div>
</div>

{{-- ===== MODAL ZGŁOSZENIA ===== --}}
@auth
<div id="reportModal" style="
    display:none;
    position:fixed;
    inset:0;
    z-index:100;
    align-items:flex-start;
    justify-content:center;
    padding:24px 16px;
">
    {{-- Backdrop --}}
    <div
        onclick="closeReportModal()"
        style="
            position:absolute;
            inset:0;
            background:rgba(0,0,0,0.68);
            backdrop-filter:blur(4px);
        "
    ></div>

    {{-- Panel / Form --}}
    <form method="POST" action="{{ route('quiz.report', $quiz) }}" style="
        position:relative;
        z-index:1;
        width:min(480px,100%);
        margin-top:60px;
        background:rgba(47,51,54,0.98);
        border:1px solid rgba(255,107,0,0.24);
        border-radius:20px;
        padding:28px 28px 24px;
        box-shadow:
            0 24px 64px rgba(0,0,0,0.6),
            0 0 24px rgba(255,107,0,0.10),
            0 0 0 1px rgba(255,255,255,0.03);
    ">
        @csrf

        {{-- Nagłówek --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="
                    width:32px;
                    height:32px;
                    border-radius:10px;
                    background:rgba(255,107,0,0.14);
                    border:1px solid rgba(255,107,0,0.42);
                    color:#FF6B00;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    font-size:16px;
                ">⚑</div>

                <span style="color:#E6E8EA;font-weight:700;font-size:17px;">
                    Zgłoś quiz
                </span>
            </div>

            <button
                type="button"
                onclick="closeReportModal()"
                style="
                    background:rgba(73,77,80,0.45);
                    border:1px solid rgba(199,202,204,0.16);
                    color:rgba(230,232,234,0.62);
                    border-radius:8px;
                    width:30px;
                    height:30px;
                    cursor:pointer;
                    font-size:16px;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    font-family:'Outfit',sans-serif;
                    transition:.15s;
                "
                onmouseover="
                    this.style.color='#E6E8EA';
                    this.style.borderColor='rgba(255,107,0,0.42)';
                    this.style.background='rgba(73,77,80,0.7)';
                "
                onmouseout="
                    this.style.color='rgba(230,232,234,0.62)';
                    this.style.borderColor='rgba(199,202,204,0.16)';
                    this.style.background='rgba(73,77,80,0.45)';
                "
            >
                ✕
            </button>
        </div>

        {{-- Powody --}}
        <div style="margin-bottom:18px;">
            <div style="
                color:rgba(230,232,234,0.62);
                font-size:12px;
                letter-spacing:.3px;
                text-transform:uppercase;
                margin-bottom:10px;
            ">
                Powód zgłoszenia
            </div>

            <div style="display:flex;flex-direction:column;gap:8px;" id="reportReasons">
                @foreach([
                    ['spam', '🚫', 'Spam lub reklama'],
                    ['inappropriate', '⚠️', 'Nieodpowiednia treść'],
                    ['wrong', '❌', 'Błędne odpowiedzi'],
                    ['copyright', '©', 'Naruszenie praw autorskich'],
                    ['other', '💬', 'Inny powód'],
                ] as [$val, $icon, $label])
                    <label
                        class="report-reason-label"
                        style="
                            display:flex;
                            align-items:center;
                            gap:12px;
                            padding:11px 14px;
                            border-radius:12px;
                            border:1px solid rgba(199,202,204,0.12);
                            background:rgba(73,77,80,0.32);
                            cursor:pointer;
                            transition:.15s;
                            color:rgba(230,232,234,0.78);
                            font-size:14px;
                        "
                    >
                        <input
                            type="radio"
                            name="report_type"
                            value="{{ $val }}"
                            onchange="onReasonChange(this)"
                            style="
                                accent-color:#FF6B00;
                                width:16px;
                                height:16px;
                                flex-shrink:0;
                            "
                        >

                        <span style="font-size:15px;">{{ $icon }}</span>

                        {{ $label }}
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Opis --}}
        <div style="margin-bottom:20px;">
            <label
                for="reportReasonText"
                style="
                    display:block;
                    color:rgba(230,232,234,0.62);
                    font-size:12px;
                    letter-spacing:.3px;
                    text-transform:uppercase;
                    margin-bottom:8px;
                "
            >
                Dodatkowy opis
            </label>

            <textarea
                id="reportReasonText"
                name="reason"
                rows="4"
                placeholder="Opcjonalnie napisz więcej..."
                style="
                    width:100%;
                    resize:vertical;
                    min-height:96px;
                    border-radius:12px;
                    border:1px solid rgba(199,202,204,0.14);
                    background:rgba(31,34,36,0.82);
                    color:#E6E8EA;
                    padding:12px 14px;
                    font-family:'Outfit',sans-serif;
                    font-size:14px;
                    outline:none;
                "
                onfocus="this.style.borderColor='rgba(255,107,0,0.55)'"
                onblur="this.style.borderColor='rgba(199,202,204,0.14)'"
            ></textarea>

            <p style="
                margin-top:7px;
                color:rgba(230,232,234,0.38);
                font-size:12px;
                line-height:1.5;
            ">
                Zgłoszenie trafi do moderacji. Administrator sprawdzi quiz i podejmie decyzję.
            </p>
        </div>

        {{-- Przyciski --}}
        <div style="display:flex;justify-content:flex-end;gap:10px;flex-wrap:wrap;">
            <button
                type="button"
                onclick="closeReportModal()"
                style="
                    padding:10px 16px;
                    border-radius:10px;
                    background:rgba(73,77,80,0.45);
                    border:1px solid rgba(199,202,204,0.14);
                    color:rgba(230,232,234,0.72);
                    cursor:pointer;
                    font-family:'Outfit',sans-serif;
                    font-weight:700;
                "
            >
                Anuluj
            </button>

            <button
                type="submit"
                style="
                    padding:10px 18px;
                    border-radius:10px;
                    background:linear-gradient(135deg,#FF6B00,#F2A541);
                    border:1px solid rgba(255,107,0,0.55);
                    color:#fff;
                    cursor:pointer;
                    font-family:'Outfit',sans-serif;
                    font-weight:800;
                    box-shadow:0 0 18px rgba(255,107,0,0.22);
                "
            >
                Wyślij zgłoszenie
            </button>
        </div>
    </form>
</div>
@endauth

<div style="position:relative;z-index:1;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:24px;">

    <form method="POST" action="{{ route('quiz.submit', $quiz) }}" id="quizForm" style="width:min(720px,100%);">
        @csrf

        <input type="hidden" name="time_spent" id="timeSpentInput" value="0">
        <div id="answersContainer"></div>

        @php
            $questions = $quiz->questions;
            $total = $questions->count();
        @endphp

        @foreach($questions as $i => $question)
            @php
                $answers = $question->answers;

                if (is_string($answers)) {
                    $answers = json_decode($answers, true);
                }

                $answers = $answers ?? [];
                $type = $question->question_type;

                if ($type === 'true_false') {
                    $answers = array_slice($answers, 0, 2);

                    if (empty($answers[0])) {
                        $answers[0] = 'Prawda';
                    }

                    if (empty($answers[1])) {
                        $answers[1] = 'Fałsz';
                    }
                }

                $letters = ['A', 'B', 'C', 'D'];
            @endphp

            <div class="question-slide" id="slide-{{ $i }}" style="{{ $i > 0 ? 'display:none;' : '' }}">

                {{-- Header --}}
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;gap:12px;flex-wrap:wrap;">
                    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                        <span style="color:#FF6B00;font-weight:700;font-size:14px;letter-spacing:.2px;">
                            {{ $quiz->title }}
                        </span>

                        @auth
                            <button
                                type="button"
                                onclick="openReportModal()"
                                style="
                                    background:rgba(255,107,0,0.12);
                                    border:1px solid rgba(255,107,0,0.35);
                                    color:#FFD7B5;
                                    border-radius:999px;
                                    padding:6px 10px;
                                    font-size:12px;
                                    cursor:pointer;
                                    font-family:'Outfit',sans-serif;
                                    transition:.15s;
                                "
                                onmouseover="this.style.background='rgba(255,107,0,0.18)';this.style.borderColor='rgba(255,107,0,0.55)'"
                                onmouseout="this.style.background='rgba(255,107,0,0.12)';this.style.borderColor='rgba(255,107,0,0.35)'"
                            >
                                Zgłoś quiz
                            </button>
                        @endauth

                        @guest
                            <a
                                href="{{ route('login') }}"
                                style="
                                    color:#FFD7B5;
                                    font-size:12px;
                                    text-decoration:none;
                                    border:1px solid rgba(255,107,0,0.35);
                                    border-radius:999px;
                                    padding:6px 10px;
                                    background:rgba(255,107,0,0.12);
                                    transition:.15s;
                                "
                            >
                                Zaloguj się, aby zgłosić
                            </a>
                        @endguest
                    </div>

                    <div style="text-align:center;min-width:140px;">
                        <div style="color:rgba(230,232,234,0.45);font-size:12px;letter-spacing:.15px;">
                            Czas rozwiązywania
                        </div>
                        <div class="timerDisplay" style="color:#fff;font-size:16px;font-weight:800;">00:00</div>
                    </div>

                    <span style="color:rgba(230,232,234,0.45);font-size:13px;">
                        Pytanie {{ $i + 1 }} / {{ $total }}
                    </span>
                </div>

                {{-- Progress --}}
                <div style="height:4px;background:rgba(199,202,204,0.12);border-radius:2px;margin-bottom:28px;overflow:hidden;">
                    <div style="height:100%;width:{{ round((($i + 1) / $total) * 100) }}%;background:linear-gradient(135deg,#FF6B00,#F2A541);border-radius:2px;"></div>
                </div>

                <div class="dash-panel" style="margin-bottom:0;">

                    @if($question->image_path)
                        <img
                            src="{{ asset('storage/' . $question->image_path) }}"
                            alt="Zdjęcie pytania"
                            style="width:100%;max-height:220px;object-fit:cover;border-radius:12px;margin-bottom:20px;"
                        >
                    @endif

                    {{-- Question --}}
                    <div style="font-size:20px;font-weight:700;color:#E6E8EA;line-height:1.35;margin-bottom:22px;">
                        {{ $question->content }}
                    </div>

                    {{-- Answers --}}
                    <div style="display:grid;gap:10px;{{ $type === 'true_false' ? 'grid-template-columns:1fr 1fr;' : '' }}margin-bottom:24px;">
                        @foreach($answers as $idx => $answer)
                            @if(!empty(trim($answer ?? '')))
                                <div
                                    class="qp-answer"
                                    data-idx="{{ $idx }}"
                                    data-qid="{{ $question->id }}"
                                    data-type="{{ $type }}"
                                    onclick="selectAnswer(this)"
                                    style="
                                        padding:13px 16px;
                                        border-radius:12px;
                                        background:rgba(73,77,80,0.32);
                                        border:1px solid rgba(199,202,204,0.12);
                                        cursor:pointer;
                                        transition:.2s ease;
                                        color:rgba(230,232,234,0.8);
                                        font-size:15px;
                                        display:flex;
                                        align-items:center;
                                        gap:12px;
                                        user-select:none;
                                    "
                                    onmouseover="if(!this.classList.contains('active')){this.style.borderColor='rgba(255,107,0,0.42)';this.style.background='rgba(255,107,0,0.07)';}"
                                    onmouseout="if(!this.classList.contains('active')){this.style.borderColor='rgba(199,202,204,0.12)';this.style.background='rgba(73,77,80,0.32)';}"
                                >
                                    <span
                                        class="qp-letter"
                                        style="
                                            width:28px;
                                            height:28px;
                                            border-radius:50%;
                                            background:rgba(199,202,204,0.12);
                                            display:inline-flex;
                                            align-items:center;
                                            justify-content:center;
                                            font-weight:800;
                                            font-size:12px;
                                            flex-shrink:0;
                                            transition:.2s;
                                        "
                                    >
                                        {{ $letters[$idx] ?? chr(65 + $idx) }}
                                    </span>

                                    {{ $answer }}
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- Actions --}}
                    <div style="display:flex;justify-content:space-between;align-items:center;padding-top:16px;border-top:1px solid rgba(199,202,204,0.12);">
                        @if($i > 0)
                            <button
                                type="button"
                                onclick="goTo({{ $i - 1 }})"
                                style="background:none;border:none;color:rgba(230,232,234,0.45);font-size:14px;cursor:pointer;font-family:'Outfit',sans-serif;transition:.2s;padding:0;"
                                onmouseover="this.style.color='rgba(230,232,234,0.82)'"
                                onmouseout="this.style.color='rgba(230,232,234,0.45)'"
                            >
                                ← Poprzednie
                            </button>
                        @else
                            <a
                                href="{{ url('/') }}"
                                style="color:rgba(230,232,234,0.45);font-size:14px;text-decoration:none;transition:.2s;"
                                onmouseover="this.style.color='rgba(230,232,234,0.82)'"
                                onmouseout="this.style.color='rgba(230,232,234,0.45)'"
                            >
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
    background: rgba(255,107,0,0.14) !important;
    border-color: rgba(255,107,0,0.85) !important;
    color: #fff !important;
}

.qp-answer.active .qp-letter {
    background: #FF6B00 !important;
    color: #fff !important;
}

.report-reason-label:hover {
    background: rgba(255,107,0,0.06) !important;
    border-color: rgba(255,107,0,0.28) !important;
    color: #E6E8EA !important;
}

.report-reason-label:has(input:checked) {
    background: rgba(255,107,0,0.12) !important;
    border-color: rgba(255,107,0,0.75) !important;
    color: #fff !important;
}

#reportModal.open {
    display: flex !important;
}

#reportReasonText::placeholder {
    color: rgba(154,160,166,0.75);
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

    document.querySelectorAll('.timerDisplay').forEach(display => {
        display.textContent = formatDuration(elapsed);
    });

    const input = document.getElementById('timeSpentInput');

    if (input) {
        input.value = elapsed;
    }
}

window.addEventListener('load', () => {
    updateTimer();
    timerInterval = setInterval(updateTimer, 1000);
});

function selectAnswer(el) {
    const qid = el.dataset.qid;
    const idx = parseInt(el.dataset.idx);
    const type = el.dataset.type;
    const siblings = document.querySelectorAll(`.qp-answer[data-qid="${qid}"]`);

    if (type === 'multiple_choice') {
        if (!selected[qid]) {
            selected[qid] = [];
        }

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
            input.type = 'hidden';
            input.name = `answers[${qid}][]`;
            input.value = idx;
            container.appendChild(input);
        });
    }
}

// ===== MODAL ZGŁOSZENIA =====
function openReportModal() {
    const modal = document.getElementById('reportModal');
    if (!modal) return;

    modal.classList.add('open');
    document.body.style.overflow = 'hidden';

    document.querySelectorAll('input[name="report_type"]').forEach(radio => {
        radio.checked = false;
    });

    document.querySelectorAll('.report-reason-label').forEach(label => {
        label.style.borderColor = 'rgba(199,202,204,0.12)';
        label.style.background = 'rgba(73,77,80,0.32)';
        label.style.color = 'rgba(230,232,234,0.78)';
    });

    const textarea = document.getElementById('reportReasonText');
    if (textarea) {
        textarea.value = '';
        textarea.style.borderColor = 'rgba(199,202,204,0.14)';
    }
}

function closeReportModal() {
    const modal = document.getElementById('reportModal');
    if (!modal) return;

    modal.classList.remove('open');
    document.body.style.overflow = '';
}

function onReasonChange(input) {
    document.querySelectorAll('.report-reason-label').forEach(label => {
        label.style.borderColor = 'rgba(199,202,204,0.12)';
        label.style.background = 'rgba(73,77,80,0.32)';
        label.style.color = 'rgba(230,232,234,0.78)';
    });

    const label = input.closest('.report-reason-label');

    if (label) {
        label.style.borderColor = 'rgba(255,107,0,0.55)';
        label.style.background = 'rgba(255,107,0,0.12)';
        label.style.color = '#E6E8EA';
    }
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeReportModal();
    }
});
</script>

</body>
</html>
