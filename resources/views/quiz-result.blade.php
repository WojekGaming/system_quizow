<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wynik — {{ $quiz->title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-body">
    <h1>ALLAHUAKBAR!!!!</h1>

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
        <div style="font-size:14px;color:rgba(255,255,255,0.4);">
            @if($percentage >= 70) Świetny wynik! 🎉
            @elseif($percentage >= 40) Nieźle, ale można lepiej 💪
            @else Spróbuj jeszcze raz 📚
            @endif
        </div>
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

</body>
</html>