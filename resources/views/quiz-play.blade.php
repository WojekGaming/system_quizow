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
        style="position:absolute;inset:0;background:rgba(0,0,0,0.68);backdrop-filter:blur(4px);"
    ></div>

    {{-- Panel --}}
    <div style="
        position:relative;
        z-index:1;
        width:min(480px,100%);
        margin-top:60px;
        background:rgba(47,51,54,0.98);
        border:1px solid rgba(199,202,204,0.16);
        border-radius:20px;
        padding:28px 28px 24px;
        box-shadow:0 24px 64px rgba(0,0,0,0.6),0 0 0 1px rgba(255,255,255,0.03);
    ">
        {{-- Nagłówek --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="
                    width:32px;height:32px;border-radius:10px;
                    background:rgba(255,107,0,0.14);
                    border:1px solid rgba(255,107,0,0.35);
                    color:#FF6B00;
                    display:flex;align-items:center;justify-content:center;
                    font-size:16px;
                ">⚑</div>
                <span style="color:#E6E8EA;font-weight:700;font-size:17px;">Zgłoś quiz</span>
            </div>

            <button
                onclick="closeReportModal()"
                style="
                    background:rgba(73,77,80,0.45);
                    border:1px solid rgba(199,202,204,0.16);
                    color:rgba(230,232,234,0.62);
                    border-radius:8px;
                    width:30px;height:30px;
                    cursor:pointer;
                    font-size:16px;
                    display:flex;align-items:center;justify-content:center;
                    font-family:'Outfit',sans-serif;
                    transition:.15s;
                "
                onmouseover="this.style.color='#E6E8EA';this.style.borderColor='rgba(255,107,0,0.42)';this.style.background='rgba(73,77,80,0.7)'"
                onmouseout="this.style.color='rgba(230,232,234,0.62)';this.style.borderColor='rgba(199,202,204,0.16)';this.style.background='rgba(73,77,80,0.45)'"
            >✕</button>
        </div>

        {{-- Powody --}}
        <div style="margin-bottom:18px;">
            <div style="color:rgba(230,232,234,0.62);font-size:12px;letter-spacing:.3px;text-transform:uppercase;margin-bottom:10px;">
                Powód zgłoszenia
            </div>

            <div style="display:flex;flex-direction:column;gap:8px;" id="reportReasons">
                @foreach([
                    ['spam',        '🚫', 'Spam lub reklama'],
                    ['inappropriate','⚠️', 'Nieodpowiednia treść'],
                    ['wrong',       '❌', 'Błędne odpowiedzi'],
                    ['copyright',   '©',  'Naruszenie praw autorskich'],
                    ['other',       '💬', 'Inny powód'],
                ] as [$val, $icon, $label])
                <label style="
                    display:flex;align-items:center;gap:12px;
                    padding:11px 14px;
                    border-radius:12px;
                    border:1px solid rgba(199,202,204,0.12);
                    background:rgba(73,77,80,0.32);
                    cursor:pointer;
                    transition:.15s;
                    color:rgba(230,232,234,0.78);
                    font-size:14px;
                " class="report-reason-label">
                    <input
                        type="radio"
                        name="report_reason"
                        value="{{ $val }}"
                        onchange="onReasonChange(this)"
                        style="accent-color:#FF6B00;width:16px;height:16px;flex-shrink:0;"
                    >
                    <span style="font-size:15px;">{{ $icon }}</span>
                    {{ $label }}
                </label>
                @endforeach
            </div>
        </div>

        {{-- Opis --}}
        <div id="reportDescWrap" style="margin-bottom:18px;">
            <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:8px;">
                <div style="color:rgba(230,232,234,0.62);font-size:12px;letter-spacing:.3px;text-transform:uppercase;">
                    Opis
                </div>
                <div id="reportDescHint" style="color:rgba(154,160,166,0.9);font-size:11px;">opcjonalny</div>
            </div>

            <textarea
                id="reportDescInput"
                placeholder="Opisz szczegółowo problem…"
                maxlength="500"
                rows="3"
                style="
                    width:100%;
                    background:rgba(31,34,37,0.72);
                    border:1px solid rgba(199,202,204,0.14);
                    border-radius:12px;
                    color:#E6E8EA;
                    font-size:14px;
                    font-family:'Outfit',sans-serif;
                    padding:11px 14px;
                    resize:vertical;
                    outline:none;
                    transition:.15s;
                    box-sizing:border-box;
                "
                onfocus="this.style.borderColor='rgba(255,107,0,0.65)'"
                onblur="this.style.borderColor='rgba(199,202,204,0.14)'"
            ></textarea>
        </div>

        {{-- Przyciski --}}
        <div style="display:flex;gap:10px;justify-content:flex-end;">
            <button
                onclick="closeReportModal()"
                style="
                    background:rgba(73,77,80,0.45);
                    border:1px solid rgba(199,202,204,0.16);
                    color:rgba(230,232,234,0.72);
                    border-radius:10px;
                    padding:10px 18px;
                    font-size:14px;
                    cursor:pointer;
                    font-family:'Outfit',sans-serif;
                    transition:.15s;
                "
                onmouseover="this.style.background='rgba(73,77,80,0.7)'"
                onmouseout="this.style.background='rgba(73,77,80,0.45)'"
            >Anuluj</button>

            <button
                onclick="submitReport()"
                id="reportSubmitBtn"
                disabled
                style="
                    background:linear-gradient(135deg,#FF6B00,#E65F00);
                    border:none;
                    color:#fff;
                    border-radius:10px;
                    padding:10px 20px;
                    font-size:14px;
                    font-weight:700;
                    cursor:not-allowed;
                    font-family:'Outfit',sans-serif;
                    opacity:.45;
                    transition:.15s;
                    box-shadow:0 10px 24px rgba(255,107,0,0.24);
                "
            >Wyślij zgłoszenie</button>
        </div>
    </div>
</div>

{{-- Ukryty formularz zgłoszenia --}}
<form method="POST" action="{{ route('quiz.report', $quiz) }}" id="quizReportForm">
    @csrf
    <input type="hidden" name="reason" id="reportReasonInput" value="">
    <input type="hidden" name="description" id="reportDescriptionInput" value="">
</form>
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
                        <div id="timerDisplay" style="color:#E6E8EA;font-size:16px;font-weight:800;">
                            00:00
                        </div>
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

#reportDescInput::placeholder {
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
    const display = document.getElementById('timerDisplay');
    const input = document.getElementById('timeSpentInput');

    if (display) {
        display.textContent = formatDuration(elapsed);
    }

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

    document.querySelectorAll('input[name="report_reason"]').forEach(r => r.checked = false);

    const descInput = document.getElementById('reportDescInput');
    if (descInput) descInput.value = '';

    updateReportDescHint('', false);

    const btn = document.getElementById('reportSubmitBtn');
    if (btn) {
        btn.disabled = true;
        btn.style.opacity = '.45';
        btn.style.cursor = 'not-allowed';
    }
}

function closeReportModal() {
    const modal = document.getElementById('reportModal');
    if (!modal) return;

    modal.classList.remove('open');
    document.body.style.overflow = '';
}

function onReasonChange(radio) {
    const isOther = radio.value === 'other';
    updateReportDescHint(radio.value, isOther);

    const btn = document.getElementById('reportSubmitBtn');
    if (btn) {
        btn.disabled = false;
        btn.style.opacity = '1';
        btn.style.cursor = 'pointer';
    }
}

function updateReportDescHint(value, isOther) {
    const hint = document.getElementById('reportDescHint');
    const textarea = document.getElementById('reportDescInput');
    if (!hint || !textarea) return;

    if (isOther) {
        hint.textContent = 'wymagany';
        hint.style.color = 'rgba(214,69,69,0.85)';
        textarea.placeholder = 'Opisz szczegółowo problem…';
        textarea.style.borderColor = 'rgba(214,69,69,0.45)';
    } else {
        hint.textContent = 'opcjonalny';
        hint.style.color = 'rgba(154,160,166,0.9)';
        textarea.placeholder = 'Opisz szczegółowo problem…';
        textarea.style.borderColor = 'rgba(199,202,204,0.14)';
    }
}

function submitReport() {
    const reason = document.querySelector('input[name="report_reason"]:checked');
    const desc = document.getElementById('reportDescInput');

    if (!reason) return;

    if (reason.value === 'other' && (!desc || !desc.value.trim())) {
        desc.style.borderColor = 'rgba(214,69,69,0.75)';
        desc.focus();
        return;
    }

    document.getElementById('reportReasonInput').value = reason.value;
    document.getElementById('reportDescriptionInput').value = desc ? desc.value.trim() : '';
    document.getElementById('quizReportForm').submit();
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeReportModal();
});
</script>

</body>
</html>