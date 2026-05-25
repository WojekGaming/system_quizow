<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Potwierdź swój adres email – Quizzies</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Manrope:wght@400;500;600&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        a,
        a:visited {
            color: #ff6b00 !important;
            text-decoration: none !important;
        }

        body {
            background-color: #080809;
            font-family: 'Manrope', Arial, sans-serif;
            color: #e6e8ea;
            -webkit-font-smoothing: antialiased;
        }

        .email-wrapper {
            width: 100%;
            background-color: #080809;
            background-image: radial-gradient(rgba(255,107,0,0.06) 1px, transparent 1px);
            background-size: 28px 28px;
            padding: 52px 16px;
        }

        .email-container {
            max-width: 580px;
            margin: 0 auto;
        }

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
            background: linear-gradient(135deg, #ff6b00, #ff8c33);
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 18px rgba(255,107,0,0.55), 0 0 40px rgba(255,107,0,0.2);
        }

        .logo-icon svg { width: 20px; height: 20px; fill: #ffffff; }

        .logo-text {
            font-family: 'Syne', Arial, sans-serif;
            font-size: 24px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.5px;
        }

        .logo-text span { color: #ff6b00; }

        .email-card {
            background: #121315;
            border: 1px solid rgba(255,255,255,0.09);
            border-radius: 14px;
            overflow: hidden;
            box-shadow:
                0 0 0 1px rgba(255,107,0,0.08),
                0 40px 80px rgba(0,0,0,0.7);
        }

        .card-accent {
            height: 3px;
            background: linear-gradient(90deg, #ff6b00, #ff8c33 50%, #ff6b00);
        }

        .card-body { padding: 48px 52px 40px; }

        .email-title {
            font-family: 'Syne', Arial, sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: #ffffff;
            text-align: center;
            margin-bottom: 12px;
            letter-spacing: -0.3px;
        }

        .email-greeting {
            font-size: 14px;
            color: #9aa0a6;
            text-align: center;
            line-height: 1.75;
            margin-bottom: 6px;
        }

        .email-greeting strong { color: #e6e8ea; font-weight: 600; }

        .email-description {
            font-size: 13.5px;
            color: #9aa0a6;
            text-align: center;
            line-height: 1.75;
            margin-bottom: 36px;
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.12), transparent);
            margin: 0 0 36px;
        }

        .cta-wrapper {
            text-align: center;
            margin-bottom: 28px;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #ff6b00, #ff8c33);
            color: #ffffff !important;
            font-family: 'Syne', Arial, sans-serif;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            text-decoration: none !important;
            padding: 15px 44px;
            border-radius: 8px;
            box-shadow:
                0 0 22px rgba(255,107,0,0.4),
                0 4px 14px rgba(0,0,0,0.5);
        }

        .fallback-section {
            background: rgba(255,255,255,0.035);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 8px;
            padding: 16px 18px;
            margin-bottom: 24px;
        }

        .fallback-label {
            font-size: 10px;
            font-weight: 700;
            color: #9aa0a6;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            margin-bottom: 7px;
        }

        .fallback-url {
            font-size: 11.5px;
            color: #ff6b00 !important;
            word-break: break-all;
            line-height: 1.6;
            text-decoration: none !important;
        }

        .expiry-notice {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,107,0,0.07);
            border: 1px solid rgba(255,107,0,0.16);
            border-radius: 8px;
            padding: 13px 15px;
            margin-bottom: 28px;
        }

        .expiry-notice svg { width: 15px; height: 15px; fill: #ff6b00; flex-shrink: 0; }

        .expiry-text { font-size: 12px; color: #9aa0a6; line-height: 1.5; }
        .expiry-text strong { color: #ff6b00; font-weight: 600; }

        .security-notice {
            font-size: 11.5px;
            color: #9aa0a6;
            text-align: center;
            line-height: 1.65;
        }

        .email-footer {
            padding: 24px 52px 36px;
            border-top: 1px solid rgba(255,255,255,0.08);
            text-align: center;
        }

        .footer-text {
            font-size: 11.5px;
            color: #9aa0a6;
            line-height: 1.7;
            margin-bottom: 14px;
        }

        .footer-text a { color: #ff6b00 !important; text-decoration: none !important; }
        .footer-copy { font-size: 10.5px; color: #6b7280; }

        @media (max-width: 600px) {
            .card-body { padding: 32px 24px 28px; }
            .email-footer { padding: 22px 24px 30px; }
            .email-title { font-size: 19px; }
            .cta-button { padding: 13px 30px; font-size: 13px; }
        }
    </style>
</head>
<body>
<div class="email-wrapper">
    <div class="email-container">
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

        <div class="email-card">
            <div class="card-accent"></div>

            <div class="card-body">
                <h1 class="email-title">Potwierdź swój adres email</h1>

                <p class="email-greeting">
                    Cześć, <strong>{{ $user->name ?? 'Graczu' }}</strong>! 👋
                </p>
                <p class="email-description">
                    Dziękujemy za rejestrację w Quizzies. Kliknij poniższy przycisk,<br>
                    aby aktywować swoje konto i zacząć rywalizację.
                </p>

                <div class="divider"></div>

                <div class="cta-wrapper">
                    <a href="{{ $verificationUrl }}" class="cta-button" style="display:inline-block;background:linear-gradient(135deg,#ff6b00,#ff8c33);color:#ffffff !important;font-family:'Syne',Arial,sans-serif;font-size:14px;font-weight:700;letter-spacing:0.6px;text-transform:uppercase;text-decoration:none !important;padding:15px 44px;border-radius:8px;box-shadow:0 0 22px rgba(255,107,0,0.4),0 4px 14px rgba(0,0,0,0.5);">Zweryfikuj email</a>
                </div>

                <div class="fallback-section">
                    <div class="fallback-label">lub skopiuj link do przeglądarki</div>
                    <a href="{{ $verificationUrl }}" class="fallback-url" style="color:#ff6b00 !important;text-decoration:none !important;word-break:break-all;">{{ $verificationUrl }}</a>
                </div>

                <div class="expiry-notice">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 17L7 12L8.41 10.59L12 14.17L15.59 10.59L17 12L12 17ZM12 7V13H13V7H12Z"/>
                    </svg>
                    <span class="expiry-text">
                        Link weryfikacyjny wygasa za <strong>{{ config('auth.verification.expire', 60) }} minut</strong>.
                        Po tym czasie będziesz musiał poprosić o nowy link.
                    </span>
                </div>

                <p class="security-notice">
                    Jeśli nie zakładałeś konta w Quizzies, zignoruj tę wiadomość.<br>
                    Żadne działanie nie jest wymagane.
                </p>
            </div>

            <div class="email-footer">
                <p class="footer-text">
                    Wysłano z <a href="{{ config('app.url') }}" style="color:#ff6b00 !important;text-decoration:none !important;">Quizzies</a> ·
                    Ta wiadomość została wygenerowana automatycznie
                </p>
                <p class="footer-copy">© {{ date('Y') }} Quizzies. Wszelkie prawa zastrzeżone.</p>
            </div>
        </div>
    </div>
</div>
</body>
</html>