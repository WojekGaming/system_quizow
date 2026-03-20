<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Quizzies') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="auth-body">

        <!-- Background effects -->
        <div class="auth-bg">
            <div class="auth-bg-blob auth-bg-blob--1"></div>
            <div class="auth-bg-blob auth-bg-blob--2"></div>
            <div class="auth-bg-blob auth-bg-blob--3"></div>
            <div class="auth-bg-grid"></div>
        </div>

        <!-- Card -->
        <div class="auth-card">

            <!-- Logo -->
            <a href="/" class="auth-logo">
                <div class="auth-logo__icon">⚡</div>
                <div class="auth-logo__text">Quizz<span>ies</span></div>
            </a>

            {{ $slot }}

        </div>

    </body>
</html>