<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Resetowanie hasła – Quizzies</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Manrope:wght@400;500;600&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: #0d0d0d;
            font-family: 'Manrope', sans-serif;
            color: #e0e0e0;
            -webkit-font-smoothing: antialiased;
        }

        .email-wrapper {
            width: 100%;
            background-color: #0d0d0d;
            /* subtle noise-like dot grid matching site bg */
            background-image: radial-gradient(rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 28px 28px;
            padding: 52px 16px;
        }

        .email-container {
            max-width: 580px;
            margin: 0 auto;
        }

        /* ── Header / Logo ── */
        .email-header {
            text-align: center;
            margin-bottom: 36px;
        }

        .logo-wrapper {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #ff8c00, #e65c00);
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 18px rgba(230,92,0,0.55), 0 0 40px rgba(230,92,0,0.2);
        }

        .logo-icon svg {
            width: 20px;
            height: 20px;
            fill: #fff;
        }

        .logo-text {
            font-family: 'Syne', sans-serif;
            font-size: 24px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
        }

        .logo-text span { color: #ff8c00; }

        /* ── Card ── */
        .email-card {
            background: #141414;
            border: 1px solid #242424;
            border-radius: 14px;
            overflow: hidden;
            box-shadow:
                0 0 0 1px rgba(230,92,0,0.07),
                0 40px 80px rgba(0,0,0,0.7);
        }

        /* orange top bar — same as site's accent line */
        .card-accent {
            height: 3px;
            background: linear-gradient(90deg, #e65c00, #ff8c00 50%, #e65c00);
        }

        .card-body { padding: 48px 52px 40px; }

        /* ── Icon badge ── */
        .icon-badge {
            width: 60px;
            height: 60px;
            background: rgba(230,92,0,0.09);
            border: 1px solid rgba(230,92,0,0.22);
            border-radius: 50%;
            margin: 0 auto 26px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-badge svg {
            width: 28px;
            height: 28px;
            fill: #ff8c00;
        }

        /* ── Typography ── */
        .email-title {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            text-align: center;
            margin-bottom: 12px;
            letter-spacing: -0.3px;
        }

        .email-greeting {
            font-size: 14px;
            color: #888;
            text-align: center;
            line-height: 1.75;
            margin-bottom: 6px;
        }

        .email-greeting strong { color: #d0d0d0; font-weight: 600; }

        .email-description {
            font-size: 13.5px;
            color: #606060;
            text-align: center;
            line-height: 1.75;
            margin-bottom: 36px;
        }

        /* ── Divider ── */
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #262626, transparent);
            margin: 0 0 36px;
        }

        /* ── CTA button — matches "Zacznij za darmo" / "Zagraj →" style ── */
        .cta-wrapper {
            text-align: center;
            margin-bottom: 28px;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #ff8c00, #e65c00);
            color: #fff;
            font-family: 'Syne', sans-serif;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            text-decoration: none;
            padding: 15px 44px;
            border-radius: 8px;
            box-shadow:
                0 0 22px rgba(230,92,0,0.4),
                0 4px 14px rgba(0,0,0,0.5);
        }

        /* ── Fallback link ── */
        .fallback-section {
            background: rgba(255,255,255,0.025);
            border: 1px solid #1e1e1e;
            border-radius: 8px;
            padding: 16px 18px;
            margin-bottom: 24px;
        }

        .fallback-label {
            font-size: 10px;
            font-weight: 700;
            color: #444;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            margin-bottom: 7px;
        }

        .fallback-url {
            font-size: 11.5px;
            color: #ff8c00;
            word-break: break-all;
            line-height: 1.6;
            text-decoration: none;
        }

        /* ── Warning (not-me notice) ── */
        .warning-notice {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background: rgba(230,92,0,0.05);
            border: 1px solid rgba(230,92,0,0.14);
            border-radius: 8px;
            padding: 13px 15px;
            margin-bottom: 18px;
        }

        .warning-notice svg {
            width: 15px;
            height: 15px;
            fill: #e65c00;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .warning-text {
            font-size: 12px;
            color: #777;
            line-height: 1.65;
        }

        .warning-text strong { color: #e65c00; font-weight: 600; }

        /* ── Expiry ── */
        .expiry-notice {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.02);
            border: 1px solid #1e1e1e;
            border-radius: 8px;
            padding: 11px 15px;
            margin-bottom: 28px;
        }

        .expiry-notice svg { width: 14px; height: 14px; fill: #444; flex-shrink: 0; }

        .expiry-text { font-size: 12px; color: #484848; line-height: 1.5; }
        .expiry-text strong { color: #777; font-weight: 600; }

        /* ── Security footnote ── */
        .security-notice {
            font-size: 11.5px;
            color: #444;
            text-align: center;
            line-height: 1.65;
        }

        /* ── Footer ── */
        .email-footer {
            padding: 24px 52px 36px;
            border-top: 1px solid #1c1c1c;
            text-align: center;
        }

        .footer-text {
            font-size: 11.5px;
            color: #3a3a3a;
            line-height: 1.7;
            margin-bottom: 14px;
        }

        .footer-text a { color: #e65c00; text-decoration: none; }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 18px;
            margin-bottom: 14px;
        }

        .footer-links a {
            font-size: 11px;
            color: #383838;
            text-decoration: none;
            letter-spacing: 0.3px;
        }

        .footer-copy { font-size: 10.5px; color: #2c2c2c; }

        /* ── Responsive ── */
        @media (max-width: 600px) {
            .card-body { padding: 32px 24px 28px; }
            .email-footer { padding: 22px 24px 30px; }
            .email-title { font-size: 19px; }
            .cta-button { padding: 13px 30px; font-size: 13px; }
            .footer-links { flex-direction: column; gap: 9px; }
        }
    </style>
</head>
<body>
<div class="email-wrapper">
    <div class="email-container">

        <!-- Logo -->
        <div class="email-header">
            <div class="logo-wrapper">
                <div class="logo-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 2L4.09 12.97H11L10.08 21.97L19 11H12L13 2Z"/>
                    </svg>
                </div>
                <span class="logo-text">Quizz<span>ies</span></span>
            </div>
        </div>

        <!-- Card -->
        <div class="email-card">
            <div class="card-accent"></div>

            <div class="card-body">
                <h1 class="email-title">Resetowanie hasła</h1>

                <p class="email-greeting">
                    Cześć, <strong>{{ $user->name ?? 'Graczu' }}</strong>!
                </p>
                <p class="email-description">
                    Otrzymaliśmy prośbę o zresetowanie hasła do Twojego konta.<br>
                    Kliknij poniższy przycisk, aby ustawić nowe hasło.
                </p>

                <div class="divider"></div>

                <div class="cta-wrapper">
                    <a href="{{ $resetUrl }}" class="cta-button">Zresetuj hasło</a>
                </div>

                <div class="fallback-section">
                    <div class="fallback-label">lub skopiuj link do przeglądarki</div>
                    <a href="{{ $resetUrl }}" class="fallback-url">{{ $resetUrl }}</a>
                </div>

                <div class="warning-notice">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 21H23L12 2L1 21ZM13 18H11V16H13V18ZM13 14H11V10H13V14Z"/>
                    </svg>
                    <span class="warning-text">
                        <strong>Nie prosiłeś o zmianę hasła?</strong> Zignoruj tę wiadomość.
                        Twoje hasło pozostanie bez zmian i nikt nie uzyska dostępu do konta.
                    </span>
                </div>

                <div class="expiry-notice">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.99 2C6.47 2 2 6.48 2 12C2 17.52 6.47 22 11.99 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 11.99 2ZM12 20C7.58 20 4 16.42 4 12C4 7.58 7.58 4 12 4C16.42 4 20 7.58 20 12C20 16.42 16.42 20 12 20ZM12.5 7H11V13L16.25 16.15L17 14.92L12.5 12.25V7Z"/>
                    </svg>
                    <span class="expiry-text">
                        Link wygasa za <strong>{{ config('auth.passwords.users.expire', 60) }} minut</strong>
                        od momentu wysłania wiadomości.
                    </span>
                </div>

                <p class="security-notice">
                    Ze względów bezpieczeństwa ten link działa tylko raz.<br>
                    Po jego użyciu stanie się nieaktywny.
                </p>
            </div>

            <div class="email-footer">
                <p class="footer-text">
                    Wysłano z <a href="{{ config('app.url') }}">Quizzies</a> ·
                    Ta wiadomość została wygenerowana automatycznie
                </p>
                <p class="footer-copy">© {{ date('Y') }} Quizzies. Wszelkie prawa zastrzeżone.</p>
            </div>

        </div><!-- /email-card -->

    </div><!-- /email-container -->
</div><!-- /email-wrapper -->
</body>
</html>