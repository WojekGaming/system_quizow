<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konto zablokowane — Quizzies</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-body">
<div class="auth-bg">
    <div class="auth-bg-blob auth-bg-blob--1"></div>
    <div class="auth-bg-blob auth-bg-blob--2"></div>
    <div class="auth-bg-grid"></div>
</div>
<div class="auth-card" style="max-width:460px;">
    <div class="auth-logo">
        <div class="auth-logo__icon">⚡</div>
        <div class="auth-logo__text">Quizz<span>ies</span></div>
    </div>

    <div class="auth-icon-badge" style="font-size:28px;width:60px;height:60px;background:rgba(220,50,50,0.1);border-color:rgba(220,50,50,0.25);">🔒</div>

    <h1 class="auth-title" style="color:#f87171;">Konto zablokowane</h1>
    <p class="auth-subtitle">
        Twoje konto zostało tymczasowo zablokowane przez administratora.
    </p>

    <div style="background:rgba(220,50,50,0.08);border:1px solid rgba(220,50,50,0.2);border-radius:13px;padding:16px 18px;margin-bottom:1.5rem;">
        <div style="font-size:12px;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Ban aktywny do</div>
        <div style="font-size:22px;font-weight:700;color:#f87171;">
            {{ auth()->user()->banned_until->format('d.m.Y') }}
        </div>
        <div style="font-size:13px;color:rgba(255,255,255,0.4);margin-top:4px;">
            Pozostało: {{ now()->diffInDays(auth()->user()->banned_until) }} dni
        </div>
    </div>

    <p style="font-size:13px;color:rgba(255,255,255,0.35);line-height:1.6;margin-bottom:1.5rem;">
        Jeśli uważasz, że to pomyłka, skontaktuj się z administracją serwisu.
    </p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="auth-btn-primary auth-btn-primary--full">
            Wyloguj się
        </button>
    </form>
</div>
</body>
</html>
