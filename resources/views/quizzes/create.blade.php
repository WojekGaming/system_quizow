<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quiz Builder — Quizzies</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --cta: #FF6B00; --ctaHover: #E65F00; --teal: #1F7A8C;
            --light: #E6E8EA; --border: #C7CACC; --warning: #F2A541;
            --panel: #2a2f33; --panel2: #31363b; --input: #3a4045;
        }
        body.app-body {
            font-family: 'Outfit', system-ui, sans-serif;
        }
        .qb-app { min-height: calc(100vh - 60px); display: grid; grid-template-columns: 340px 1fr; }
        .qb-sidebar {
            background: #202427; border-right: 1px solid rgba(255,255,255,0.08);
            padding: 22px 18px; display: flex; flex-direction: column; gap: 18px;
            position: sticky; top: 60px; height: calc(100vh - 60px); overflow-y: auto;
        }
        .qb-brand { display: flex; align-items: center; gap: 10px; padding-bottom: 4px; }
        .qb-brand-icon {
            width: 34px; height: 34px; background: linear-gradient(135deg, #ff6b00, #ff9a3c);
            border-radius: 9px; display: flex; align-items: center; justify-content: center;
            font-size: 17px; flex-shrink: 0;
        }
        .qb-brand-text { font-size: 20px; font-weight: 700; color: #fff; letter-spacing: -.3px; }
        .qb-brand-text span { color: #ff6b00; }
        .side-block {
            background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);
            border-radius: 16px; padding: 16px;
        }
        .side-block h2 { margin: 0 0 14px; font-size: 15px; font-weight: 600; color: #fff; }
        .qb-field { display: flex; flex-direction: column; gap: 6px; margin-bottom: 13px; }
        .qb-field:last-child { margin-bottom: 0; }
        .qb-field label { font-size: 11px; font-weight: 600; color: rgba(255,255,255,0.45); text-transform: uppercase; letter-spacing: .5px; }
        .qb-control, .qb-textarea, .qb-select {
            width: 100%; background: var(--input); color: white; border: 1px solid #4a5156;
            border-radius: 10px; padding: 10px 12px; outline: none;
            font-family: 'Outfit', sans-serif; font-size: 14px; transition: border-color .2s;
        }
        .qb-control:focus, .qb-textarea:focus, .qb-select:focus { border-color: var(--cta); box-shadow: 0 0 0 3px rgba(255,107,0,0.12); }
        .qb-textarea { resize: vertical; min-height: 80px; }
        .qb-select option { background: #2a2f33; }
        .questions-list { display: flex; flex-direction: column; gap: 8px; }
        .question-item {
            background: #2b3034; border: 1px solid transparent; border-radius: 12px;
            padding: 11px 13px; cursor: pointer; transition: .2s ease;
        }
        .question-item:hover { border-color: rgba(255,107,0,0.35); transform: translateY(-1px); }
        .question-item.active { border-color: var(--cta); box-shadow: 0 0 0 2px rgba(255,107,0,0.15); }
        .q-item-top { display: flex; justify-content: space-between; gap: 8px; margin-bottom: 5px; }
        .q-number { font-size: 11px; color: var(--border); text-transform: uppercase; letter-spacing: .4px; }
        .q-state { font-size: 11px; color: var(--warning); font-weight: 700; }
        .q-state.saved { color: #4ade80; }
        .q-name { font-size: 13px; line-height: 1.35; color: rgba(255,255,255,0.8); }
        .qb-btn {
            height: 40px; padding: 0 16px; border-radius: 10px; border: 1px solid transparent;
            cursor: pointer; font-family: 'Outfit', sans-serif; font-weight: 600; font-size: 14px;
            transition: .2s ease; display: inline-flex; align-items: center; justify-content: center;
            gap: 6px; text-decoration: none;
        }
        .qb-btn-secondary { background: #494D50; color: white; border-color: #5c6166; }
        .qb-btn-secondary:hover { background: #5a5f64; }
        .qb-btn-primary { background: var(--cta); color: white; border-color: var(--cta); box-shadow: 0 4px 12px rgba(255,107,0,0.22); }
        .qb-btn-primary:hover { background: var(--ctaHover); box-shadow: 0 0 16px rgba(255,107,0,0.38); }
        .qb-btn-danger { background: rgba(214,69,69,0.15); color: #f87171; border-color: rgba(214,69,69,0.3); }
        .qb-btn-danger:hover { background: rgba(214,69,69,0.25); }
        .qb-btn-full { width: 100%; }
        .qb-toggle-row { display: flex; align-items: center; gap: 10px; margin-top: 4px; }
        .qb-toggle-row label { font-size: 13px; color: rgba(255,255,255,0.6); font-weight: 500; text-transform: none; letter-spacing: 0; }
        .qb-toggle-row input[type=checkbox] { accent-color: var(--cta); width: 16px; height: 16px; }
        .qb-workspace { padding: 28px; display: flex; flex-direction: column; gap: 22px; }
        .qb-workspace-header { display: flex; justify-content: space-between; gap: 20px; align-items: center; flex-wrap: wrap; }
        .qb-workspace-title h2 { margin: 0; font-size: 26px; font-weight: 700; color: #fff; }
        .qb-workspace-title p { margin: 5px 0 0; color: var(--border); font-size: 14px; }
        .qb-editor { background: var(--panel); border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; padding: 24px; display: grid; gap: 20px; }
        .qb-editor-grid { display: grid; grid-template-columns: 1.25fr 0.95fr; gap: 20px; align-items: start; }
        .qb-editor-block { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 16px; padding: 18px; }
        .qb-editor-block h3 { margin: 0 0 14px; font-size: 17px; font-weight: 600; }
        .upload-zone {
            min-height: 220px; border: 2px dashed #4b5257; border-radius: 14px;
            background: rgba(255,255,255,0.01); display: flex; flex-direction: column;
            justify-content: center; align-items: center; padding: 16px;
            text-align: center; gap: 12px; transition: border-color .2s; cursor: pointer;
        }
        .upload-zone:hover { border-color: rgba(255,107,0,0.4); }
        .upload-zone img { max-width: 100%; max-height: 200px; object-fit: contain; display: none; border-radius: 10px; }
        .upload-placeholder { color: var(--border); line-height: 1.5; max-width: 260px; font-size: 13px; }
        .small-note { font-size: 12px; color: var(--border); }
        .answers-grid { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 14px; }
        .answer-tile {
            background: var(--panel2); border: 1px solid rgba(255,255,255,0.06);
            border-radius: 13px; padding: 14px; display: flex; flex-direction: column; gap: 10px; transition: .2s ease;
        }
        .answer-tile:hover { border-color: rgba(255,107,0,0.28); transform: translateY(-1px); }
        .answer-tile.correct-tile { border-color: rgba(46,158,91,0.4); background: rgba(46,158,91,0.05); }
        .answer-head { display: flex; justify-content: space-between; align-items: center; gap: 10px; }
        .answer-letter {
            width: 30px; height: 30px; border-radius: 50%; background: #494D50;
            display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px;
        }
        .correct-line { display: flex; align-items: center; gap: 7px; color: var(--border); font-size: 13px; white-space: nowrap; cursor: pointer; }
        .correct-line input { accent-color: var(--cta); width: 15px; height: 15px; }
        .qb-editor-actions { display: flex; justify-content: flex-end; gap: 10px; flex-wrap: wrap; }
        .qb-toast {
            position: fixed; bottom: 24px; right: 24px; background: #2a2f33;
            border: 1px solid rgba(255,107,0,0.3); border-radius: 12px; padding: 12px 20px;
            color: #fff; font-size: 14px; font-weight: 500; box-shadow: 0 8px 24px rgba(0,0,0,0.4);
            z-index: 999; opacity: 0; transform: translateY(10px); transition: .3s ease; pointer-events: none;
        }
        .qb-toast.show { opacity: 1; transform: translateY(0); }
        /* ── DB question read-only preview ── */
        .db-preview-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(93,188,216,0.1); border: 1px solid rgba(93,188,216,0.25);
            color: #5bc8d8; font-size: 12px; font-weight: 700;
            padding: 4px 10px; border-radius: 20px; letter-spacing: .3px;
        }
        .db-preview-lock-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,107,0,0.1); border: 1px solid rgba(255,107,0,0.25);
            color: #ff6b00; font-size: 12px; font-weight: 700;
            padding: 4px 10px; border-radius: 20px;
        }
        .db-q-card {
            background: var(--panel); border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px; padding: 24px; display: flex; flex-direction: column; gap: 18px;
        }
        .db-q-card-header { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
        .db-q-card-num {
            font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.35);
            text-transform: uppercase; letter-spacing: .6px;
        }
        .db-q-type-badge {
            font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px;
            background: rgba(93,188,216,0.1); border: 1px solid rgba(93,188,216,0.2); color: #5bc8d8;
        }
        .db-q-text { font-size: 18px; font-weight: 600; color: #fff; line-height: 1.45; }
        .db-q-image { max-width: 100%; max-height: 280px; object-fit: contain; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08); }
        .db-answers-grid { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 12px; }
        .db-answer-tile {
            background: var(--panel2); border: 1px solid rgba(255,255,255,0.06);
            border-radius: 13px; padding: 14px; display: flex; align-items: flex-start; gap: 12px;
        }
        .db-answer-tile.db-correct {
            border-color: rgba(46,158,91,0.45); background: rgba(46,158,91,0.07);
        }
        .db-answer-letter {
            width: 30px; height: 30px; border-radius: 50%; background: #494D50; flex-shrink: 0;
            display: inline-flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 13px; color: #fff;
        }
        .db-answer-tile.db-correct .db-answer-letter { background: rgba(46,158,91,0.6); }
        .db-answer-text { font-size: 14px; color: rgba(255,255,255,0.8); line-height: 1.4; padding-top: 5px; }
        .db-correct-label { font-size: 11px; color: #4ade80; font-weight: 700; margin-top: 3px; }

        @media (max-width: 1100px) {
            .qb-app { grid-template-columns: 1fr; }
            .qb-sidebar { position: static; height: auto; border-right: none; border-bottom: 1px solid rgba(255,255,255,0.08); }
            .qb-editor-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 700px) {
            .qb-workspace { padding: 16px; }
            .answers-grid { grid-template-columns: 1fr; }
            .qb-workspace-header { flex-direction: column; align-items: flex-start; }
            .qb-editor-actions .qb-btn { width: 100%; }
        }
    </style>
</head>
<body class="app-body">

<div class="app-bg">
    <div class="app-bg-blob app-bg-blob--1"></div>
    <div class="app-bg-blob app-bg-blob--2"></div>
    <div class="app-bg-grid"></div>
</div>

{{-- Navbar --}}
<nav class="app-nav">
    <div class="app-nav__inner">
        <a href="{{ url('/') }}" class="app-nav__logo">
            <div class="app-nav__logo-icon">⚡</div>
            <div class="app-nav__logo-text">Quiz<span>zies</span></div>
        </a>
        <div class="app-nav__right">
            <div class="app-nav__user" onclick="document.getElementById('userDropdown').classList.toggle('app-nav__dropdown--open')">
                <div class="app-nav__avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <span class="app-nav__username">{{ Auth::user()->name }}</span>
                <span class="app-nav__chevron">▾</span>
                <div class="app-nav__dropdown" id="userDropdown">
                    <div class="app-nav__dropdown-header">
                        <div class="app-nav__dropdown-name">{{ Auth::user()->name }}</div>
                        <div class="app-nav__dropdown-email">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="app-nav__dropdown-divider"></div>
                    <a href="{{ route('profile.edit') }}" class="app-nav__dropdown-item">👤 Profil</a>
                    <div class="app-nav__dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="app-nav__dropdown-item app-nav__dropdown-item--danger">🚪 Wyloguj</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="qb-app">

    <aside class="qb-sidebar">
        <div class="qb-brand">
            <div class="qb-brand-icon">⚡</div>
            <div class="qb-brand-text">Quiz<span>Builder</span></div>
        </div>

        <div class="side-block">
            <h2>Ustawienia quizu</h2>
            <div class="qb-field">
                <label>Tytuł quizu *</label>
                <input id="quizTitle" class="qb-control" type="text" placeholder="np. Historia Polski" maxlength="150">
            </div>
            <div class="qb-field">
                <label>Kategoria</label>
                <select id="quizCategory" class="qb-select">
                    <option value="">Bez kategorii</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="qb-field">
                <label>Opis</label>
                <textarea id="quizDesc" class="qb-textarea" placeholder="Krótki opis quizu..."></textarea>
            </div>
            <div class="qb-toggle-row">
                <input type="checkbox" id="quizPremium">
                <label for="quizPremium">Quiz Premium</label>
            </div>
            <div class="qb-toggle-row" style="margin-top:8px;">
                <input type="checkbox" id="quizActive" checked>
                <label for="quizActive">Aktywny (widoczny publicznie)</label>
            </div>
        </div>

        <div class="side-block">
            <h2>Pytania</h2>
            <div class="questions-list" id="questionsList"></div>
            <div style="margin-top:12px;">
                <button class="qb-btn qb-btn-primary qb-btn-full" type="button" id="addQuestionBtn">
                    ＋ Dodaj pytanie
                </button>
            </div>
            <div style="margin-top:10px; display:flex; gap:8px;">
                <button class="qb-btn qb-btn-primary" type="button" id="saveQuizBtn" style="flex:1;">
                    💾 Zapisz quiz
                </button>
                <a href="{{ route('quizzes.index') }}" class="qb-btn qb-btn-secondary" style="flex:1;">
                    ✕ Wyjdź
                </a>
            </div>
        </div>
        <div class="side-block">
            <h2>Baza pytań (wybrana kategoria)</h2>
            <div id="availableQuestions" style="display:block; max-height:320px; overflow:auto;"></div>
            <div class="small-note" style="margin-top:8px">Możesz dodać pytania z bazy. Edycja treści dostępna tylko dla autora pytania.</div>
        </div>
    </aside>

    <main class="qb-workspace">
        <div class="qb-workspace-header">
            <div class="qb-workspace-title">
                <h2 id="workspaceTitle">Edytujesz: Pytanie 1</h2>
                <p>Wypełnij treść, ustaw odpowiedzi i zaznacz poprawną.</p>
            </div>
        </div>

        <section class="qb-editor" id="dbPreviewSection" style="display:none;">
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:4px;">
                <span class="db-preview-lock-badge">🔒 Pytanie z bazy — tylko odczyt</span>
                <span class="db-preview-badge" id="dbTypeBadge"></span>
            </div>
            <div class="db-q-card">
                <div class="db-q-card-header">
                    <span class="db-q-card-num" id="dbQNum"></span>
                </div>
                <div class="db-q-text" id="dbQText"></div>
                <div class="db-answers-grid" id="dbAnswersGrid"></div>
            </div>
            <div class="qb-editor-actions" style="margin-top:4px;">
                <button class="qb-btn qb-btn-danger" type="button" id="deleteQuestionBtnDb">🗑 Usuń pytanie</button>
            </div>
        </section>

        <section class="qb-editor" id="editorSection">
            <div class="qb-editor-grid">
                <div class="qb-editor-block">
                    <h3>Treść pytania</h3>
                    <div class="qb-field">
                        <label>Pytanie *</label>
                        <textarea id="questionText" class="qb-textarea" placeholder="Wpisz treść pytania..." style="min-height:110px;"></textarea>
                    </div>
                    <div class="qb-field" style="margin-top:4px;">
                        <label>Typ pytania</label>
                        <select id="questionType" class="qb-select">
                            <option value="single_choice">Jedna poprawna odpowiedź</option>
                            <option value="multiple_choice">Wiele poprawnych odpowiedzi</option>
                            <option value="true_false">Prawda / Fałsz</option>
                        </select>
                    </div>
                    <div class="small-note" style="margin-top:8px;">Krótka, jednoznaczna treść działa najlepiej.</div>
                </div>

                <div class="qb-editor-block">
                    <h3>Zdjęcie (opcjonalne)</h3>
                    <div class="upload-zone" id="uploadZone">
                        <input type="file" id="imageInput" accept="image/jpeg,image/png,image/webp,image/gif" style="display:none;">
                        <img id="previewImg" alt="Podgląd zdjęcia">
                        <div class="upload-placeholder" id="uploadPlaceholder">
                            Kliknij aby dodać zdjęcie do pytania.<br>
                            <span style="font-size:11px;color:rgba(255,255,255,0.3);">JPG, PNG, WebP · max 5MB</span>
                        </div>
                        <div style="display:flex;gap:8px;flex-wrap:wrap;justify-content:center;">
                            <button class="qb-btn qb-btn-secondary" type="button" id="chooseImgBtn">Wybierz plik</button>
                            <button class="qb-btn qb-btn-danger" type="button" id="removeImgBtn">Usuń</button>
                        </div>
                        <div class="small-note" id="fileName">Nie wybrano pliku</div>
                    </div>
                </div>
            </div>

            <div class="qb-editor-block">
                <h3>Odpowiedzi</h3>
                <div class="answers-grid" id="answersGrid"></div>
            </div>

            <div class="qb-editor-actions">
                <button class="qb-btn qb-btn-danger" type="button" id="deleteQuestionBtn">🗑 Usuń pytanie</button>
                <button class="qb-btn qb-btn-secondary" type="button" id="saveQuestionBtn">✓ Zapisz pytanie</button>
            </div>
        </section>
    </main>
</div>

<div class="qb-toast" id="toast"></div>

<form id="submitForm" method="POST" action="{{ route('quizzes.store') }}" style="display:none;">
    @csrf
    <input type="hidden" name="title"          id="f_title">
    <input type="hidden" name="description"    id="f_desc">
    <input type="hidden" name="category_id"    id="f_category">
    <input type="hidden" name="is_premium"     id="f_premium">
    <input type="hidden" name="is_active"      id="f_active">
    <input type="hidden" name="questions_json" id="f_questions">
</form>

<script>
let questions = [{ id: 'temp-' + Date.now() + '-' + Math.random().toString(36).slice(2, 8), text: '', type: 'single_choice', answers: ['','','',''], correct: [] }];
let currentQ = 0;
let questionImages = {};

const questionsList     = document.getElementById('questionsList');
const workspaceTitle    = document.getElementById('workspaceTitle');
const questionText      = document.getElementById('questionText');
const questionType      = document.getElementById('questionType');
const answersGrid       = document.getElementById('answersGrid');
const quizCategory      = document.getElementById('quizCategory');
const imageInput        = document.getElementById('imageInput');
const previewImg        = document.getElementById('previewImg');
const uploadPlaceholder = document.getElementById('uploadPlaceholder');
const fileName          = document.getElementById('fileName');
const toast             = document.getElementById('toast');

let toastTimer;
function showToast(msg, color = '#ff6b00') {
    clearTimeout(toastTimer);
    toast.textContent = msg;
    toast.style.borderColor = color + '66';
    toast.classList.add('show');
    toastTimer = setTimeout(() => toast.classList.remove('show'), 2800);
}

function renderList() {
    questionsList.innerHTML = '';
    questions.forEach((q, i) => {
        const saved = q.text.trim().length > 0 && q.correct.length > 0;
        const div = document.createElement('div');
        div.className = 'question-item' + (i === currentQ ? ' active' : '');
        div.innerHTML = `
            <div class="q-item-top">
                <div style="display:flex;gap:8px;align-items:center;">
                    <button class="qb-btn qb-btn-secondary" style="height:28px;padding:4px 8px;font-size:12px;" onclick="moveUp(${i});event.stopPropagation();">↑</button>
                    <button class="qb-btn qb-btn-secondary" style="height:28px;padding:4px 8px;font-size:12px;" onclick="moveDown(${i});event.stopPropagation();">↓</button>
                </div>
                <span style="display:flex;gap:8px;align-items:center;">
                  <span class="q-number">Pytanie ${i + 1}</span>
                  <span class="q-state ${i === currentQ ? '' : saved ? 'saved' : ''}">
                      ${i === currentQ ? 'edytujesz' : saved ? 'zapisane' : 'robocze'}
                  </span>
                </span>
            </div>
            <div class="q-name">${q.text || 'Brak treści...'}</div>`;
        div.addEventListener('click', () => { saveCurrentToState(); switchTo(i); });
        questionsList.appendChild(div);
    });
}

function moveUp(i) {
    if (i <= 0) return;
    const a = questions[i-1]; questions[i-1] = questions[i]; questions[i] = a;
    if (currentQ === i) currentQ = i-1; else if (currentQ === i-1) currentQ = i;
    renderList(); switchTo(currentQ);
}

function moveDown(i) {
    if (i >= questions.length-1) return;
    const a = questions[i+1]; questions[i+1] = questions[i]; questions[i] = a;
    if (currentQ === i) currentQ = i+1; else if (currentQ === i+1) currentQ = i;
    renderList(); switchTo(currentQ);
}

function renderAnswers() {
    const q = questions[currentQ];
    const editable = !(q.from_db && !q.can_edit);
    answersGrid.innerHTML = '';
    const letters = ['A','B','C','D'];
    const count = q.type === 'true_false' ? 2 : 4;
    const tfLabels = ['Prawda','Fałsz'];
    const inputType = q.type === 'multiple_choice' ? 'checkbox' : 'radio';
    for (let i = 0; i < count; i++) {
        const isCorrect = q.correct.includes(i);
        const tile = document.createElement('div');
        tile.className = 'answer-tile' + (isCorrect ? ' correct-tile' : '');
        tile.innerHTML = `
            <div class="answer-head">
                <span class="answer-letter">${letters[i]}</span>
                <label class="correct-line">
                    <input type="${inputType}" name="correctAnswer" value="${i}" ${isCorrect ? 'checked' : ''}> Poprawna
                </label>
            </div>
            <input class="qb-control" type="text"
                   placeholder="${q.type === 'true_false' ? tfLabels[i] : 'Odpowiedź ' + letters[i]}"
                   value="${q.answers[i] || ''}" data-idx="${i}" ${!editable ? 'disabled' : ''}>`;
        answersGrid.appendChild(tile);
    }
    if (editable) {
        answersGrid.querySelectorAll('input[name=correctAnswer]').forEach(inp => {
            inp.addEventListener('change', () => {
                if (q.type === 'multiple_choice') {
                    q.correct = [...answersGrid.querySelectorAll('input[name=correctAnswer]:checked')].map(x => parseInt(x.value));
                } else {
                    q.correct = [parseInt(inp.value)];
                }
                renderAnswers();
            });
        });
        answersGrid.querySelectorAll('input[type=text]').forEach(inp => {
            inp.addEventListener('input', () => { q.answers[parseInt(inp.dataset.idx)] = inp.value; });
        });
    }
}

function switchTo(i) {
    currentQ = i;
    const q = questions[i];
    const isDbReadOnly = q.from_db && !q.can_edit;

    workspaceTitle.textContent = `${isDbReadOnly ? 'Podgląd' : 'Edytujesz'}: Pytanie ${i + 1}`;

    const editorSection    = document.getElementById('editorSection');
    const dbPreviewSection = document.getElementById('dbPreviewSection');

    if (isDbReadOnly) {
        editorSection.style.display    = 'none';
        dbPreviewSection.style.display = 'grid';
        renderDbPreview(i);
    } else {
        dbPreviewSection.style.display = 'none';
        editorSection.style.display    = 'grid';

        questionText.value = q.text;
        questionType.value = q.type;

        if (questionImages[q.id]) {
            previewImg.src = URL.createObjectURL(questionImages[q.id]);
            previewImg.style.display = 'block';
            uploadPlaceholder.style.display = 'none';
            fileName.textContent = questionImages[q.id].name;
        } else {
            previewImg.src = '';
            previewImg.style.display = 'none';
            uploadPlaceholder.style.display = 'block';
            fileName.textContent = 'Nie wybrano pliku';
        }

        renderAnswers();
        applyEditability();
    }

    renderList();
}

function renderDbPreview(i) {
    const q = questions[i];
    const letters = ['A','B','C','D'];
    const typeMap = { single_choice: 'Jedna poprawna', multiple_choice: 'Wiele poprawnych', true_false: 'Prawda / Fałsz' };

    document.getElementById('dbQNum').textContent    = `Pytanie ${i + 1} z ${questions.length}`;
    document.getElementById('dbTypeBadge').textContent = typeMap[q.type] || q.type;
    document.getElementById('dbQText').textContent    = q.text || '—';

    const answers = (q.answers || []).filter(a => a !== '');
    const grid = document.getElementById('dbAnswersGrid');
    grid.innerHTML = '';
    answers.forEach((ans, idx) => {
        const isCorrect = (q.correct || []).includes(idx);
        const tile = document.createElement('div');
        tile.className = 'db-answer-tile' + (isCorrect ? ' db-correct' : '');
        tile.innerHTML = `
            <div class="db-answer-letter">${letters[idx] || String.fromCharCode(65 + idx)}</div>
            <div>
                <div class="db-answer-text">${ans || '—'}</div>
                ${isCorrect ? '<div class="db-correct-label">✓ Poprawna</div>' : ''}
            </div>`;
        grid.appendChild(tile);
    });

    // Wire up delete button inside the preview
    document.getElementById('deleteQuestionBtnDb').onclick = () => {
        if (questions.length === 1) { showToast('Quiz musi mieć co najmniej 1 pytanie', '#f87171'); return; }
        delete questionImages[questions[currentQ].id];
        questions.splice(currentQ, 1);
        switchTo(Math.min(currentQ, questions.length - 1));
        showToast('Pytanie usunięte');
    };
}

quizCategory.addEventListener('change', () => {
    const cat = quizCategory.value;
    const box = document.getElementById('availableQuestions');
    box.innerHTML = 'Ładowanie...';
    if (!cat) { box.innerHTML = '<div class="small-note">Wybierz kategorię, aby zobaczyć pytania z bazy.</div>'; return; }
    fetch('{{ route('quizzes.availableQuestions') }}?category_id=' + encodeURIComponent(cat))
        .then(r => r.json())
        .then(data => renderAvailableQuestions(data.questions || []))
        .catch(err => { box.innerHTML = '<div class="small-note" style="color:#f87171">Błąd pobierania pytań</div>'; });
});

function renderAvailableQuestions(list) {
    const box = document.getElementById('availableQuestions');
    box.innerHTML = '';
    if (!list.length) { box.innerHTML = '<div class="small-note">Brak pytań w tej kategorii.</div>'; return; }
    list.forEach(q => {
        const el = document.createElement('div');
        el.className = 'question-item';
        el.style.cssText = 'display:flex;justify-content:space-between;align-items:center;gap:8px;';
        el.innerHTML = `<div style="flex:1;margin-right:8px;"><div style="font-size:13px;color:rgba(255,255,255,0.85);">${q.text}</div><div class="small-note">Autor: ${q.creator_name || 'Anonim'}</div></div>`;
        const btn = document.createElement('button');
        btn.className = 'qb-btn qb-btn-primary';
        btn.style.cssText = 'height:32px;padding:0 12px;font-size:13px;flex-shrink:0;';
        btn.textContent = 'Dodaj';
        btn.addEventListener('click', () => {
            const newQ = {
                id: q.id,
                text: q.text,
                type: q.type,
                answers: q.answers || ['','','',''],
                correct: q.correct || [],
                from_db: true,
                can_edit: false
            };
            saveCurrentToState();
            questions.push(newQ);
            switchTo(questions.length - 1);
            el.remove();
            showToast('Dodano pytanie z bazy');
        });
        el.appendChild(btn);
        box.appendChild(el);
    });
}

// Disable editing UI for DB-sourced questions without edit rights
function applyEditability() {
    const q = questions[currentQ];
    const editable = !(q.from_db && !q.can_edit);
    questionText.disabled = !editable;
    questionType.disabled = !editable;
    document.getElementById('chooseImgBtn').disabled = !editable;
    document.getElementById('removeImgBtn').disabled = !editable;
    document.getElementById('saveQuestionBtn').style.display = editable ? 'inline-flex' : 'none';
}

function saveCurrentToState() {
    const q = questions[currentQ];
    if (q.from_db && !q.can_edit) return;
    q.text = questionText.value.trim();
    q.type = questionType.value;
    answersGrid.querySelectorAll('input[type=text]').forEach(inp => { q.answers[parseInt(inp.dataset.idx)] = inp.value; });
}

document.getElementById('addQuestionBtn').addEventListener('click', () => {
    saveCurrentToState();
    questions.push({ id: 'temp-' + Date.now() + '-' + Math.random().toString(36).slice(2, 8), text: '', type: 'single_choice', answers: ['','','',''], correct: [] });
    switchTo(questions.length - 1);
    showToast('Dodano nowe pytanie');
});

document.getElementById('deleteQuestionBtn').addEventListener('click', () => {
    if (questions.length === 1) { showToast('Quiz musi mieć co najmniej 1 pytanie', '#f87171'); return; }
    delete questionImages[questions[currentQ].id];
    questions.splice(currentQ, 1);
    switchTo(Math.min(currentQ, questions.length - 1));
    showToast('Pytanie usunięte');
});

document.getElementById('saveQuestionBtn').addEventListener('click', () => {
    saveCurrentToState();
    const q = questions[currentQ];
    if (!q.text) { showToast('Wpisz treść pytania!', '#f87171'); return; }
    if (q.correct.length === 0) { showToast('Zaznacz poprawną odpowiedź!', '#f87171'); return; }
    renderList();
    showToast('✓ Pytanie zapisane', '#4ade80');
});

document.getElementById('chooseImgBtn').addEventListener('click', () => imageInput.click());
document.getElementById('uploadZone').addEventListener('click', e => {
    if (['uploadZone','uploadPlaceholder'].includes(e.target.id)) imageInput.click();
});
imageInput.addEventListener('change', e => {
    const file = e.target.files[0];
    if (!file) return;
    if (file.size > 5*1024*1024) { showToast('Zdjęcie może mieć max 5MB!', '#f87171'); imageInput.value=''; return; }
    questionImages[questions[currentQ].id] = file;
    const reader = new FileReader();
    reader.onload = ev => { previewImg.src=ev.target.result; previewImg.style.display='block'; uploadPlaceholder.style.display='none'; fileName.textContent=file.name; };
    reader.readAsDataURL(file);
});
document.getElementById('removeImgBtn').addEventListener('click', () => {
    delete questionImages[questions[currentQ].id];
    imageInput.value=''; previewImg.src=''; previewImg.style.display='none'; uploadPlaceholder.style.display='block'; fileName.textContent='Nie wybrano pliku';
});

questionType.addEventListener('change', () => {
    questions[currentQ].type = questionType.value;
    questions[currentQ].correct = [];
    if (questionType.value === 'true_false') questions[currentQ].answers = ['Prawda','Fałsz','',''];
    renderAnswers();
});

document.getElementById('saveQuizBtn').addEventListener('click', async () => {
    saveCurrentToState();
    const title = document.getElementById('quizTitle').value.trim();
    if (!title) { showToast('Podaj tytuł quizu!', '#f87171'); return; }
    const incomplete = questions.filter(q => !q.text || q.correct.length === 0);
    if (incomplete.length > 0) { showToast(`${incomplete.length} pytanie(a) nie są kompletne!`, '#f87171'); return; }

    const btn = document.getElementById('saveQuizBtn');
    btn.disabled = true;
    btn.textContent = 'Zapisuję...';

    const fd = new FormData();
    fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    fd.append('title',       title);
    fd.append('description', document.getElementById('quizDesc').value);
    fd.append('category_id', document.getElementById('quizCategory').value);
    fd.append('is_premium',  document.getElementById('quizPremium').checked ? '1' : '0');
    fd.append('is_active',   document.getElementById('quizActive').checked  ? '1' : '0');
    fd.append('questions_json', JSON.stringify(questions));

    questions.forEach((q, idx) => {
        if (questionImages[q.id]) {
            fd.append(`image_q_${idx}`, questionImages[q.id]);
        }
    });

    try {
        const resp = await fetch('{{ route('quizzes.store') }}', { method: 'POST', body: fd });
        if (resp.redirected) { window.location.href = resp.url; return; }
        if (!resp.ok) throw new Error('Błąd serwera: ' + resp.status);
        window.location.href = resp.url || '/quizzes';
    } catch (err) {
        showToast('Błąd zapisu: ' + err.message, '#f87171');
        btn.disabled = false;
        btn.textContent = '💾 Zapisz quiz';
    }
});

switchTo(0);

document.addEventListener('click', e => {
    const dropdown = document.getElementById('userDropdown');
    if (dropdown && !dropdown.parentElement.contains(e.target)) {
        dropdown.classList.remove('app-nav__dropdown--open');
    }
});
</script>
</body>
</html>