<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zweryfikuj swój adres email</title>
    <style>
        /* Podstawowy reset dla klientów pocztowych */
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            background-color: #0b0b0b;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }
        table {
            border-collapse: collapse !important;
        }
        
        /* Style responsywne */
        @media screen and (max-width: 600px) {
            .wrapper {
                width: 100% !important;
                padding: 10px !important;
            }
            .container {
                width: 100% !important;
                padding: 30px 20px !important;
            }
        }
    </style>
</head>
<body style="background-color: #0b0b0b; margin: 0; padding: 0;">

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #0b0b0b; background-image: linear-gradient(rgba(255, 255, 255, 0.015) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.015) 1px, transparent 1px); background-size: 40px 40px;">
        <tr>
            <td align="center" style="padding: 50px 10px;">
                
                <table class="container" border="0" cellpadding="0" cellspacing="0" width="550" style="background-color: #121212; border: 1px solid #1f1f1f; border-top: 3px solid #ff7a1a; border-radius: 24px; padding: 45px; box-shadow: 0 20px 40px rgba(0,0,0,0.7);">
                    
                    <tr>
                        <td align="left" style="padding-bottom: 30px;">
                            <span style="color: #ff7a1a; font-weight: bold; font-size: 24px; vertical-align: middle;">⚡</span>
                            <span style="color: #ffffff; font-weight: 800; font-size: 22px; letter-spacing: -0.5px; vertical-align: middle; font-family: Arial, sans-serif;">Quizz<span style="color: #ff7a1a;">ies</span></span>
                        </td>
                    </tr>

                    <tr>
                        <td align="left" style="padding-bottom: 25px;">
                            <table border="0" cellpadding="0" cellspacing="0" style="background-color: #1c1511; border: 1px solid #362216; border-radius: 12px;">
                                <tr>
                                    <td style="padding: 12px 16px; font-size: 22px; line-height: 1;">✉️</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="left" style="padding-bottom: 15px;">
                            <h1 style="color: #ffffff; font-size: 28px; font-weight: 700; margin: 0; line-height: 1.3; letter-spacing: -0.5px;">
                                Zweryfikuj swój adres e-mail!
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td align="left" style="padding-bottom: 30px;">
                            <p style="color: #a0a0a0; font-size: 14px; line-height: 1.6; margin: 0;">
                                Dziękujemy za rejestrację! Zanim zaczniemy, prosimy o weryfikację adresu email. Możesz to zrobić, klikając w poniższy przycisk.
                            </p>
                        </td>
                    </tr>


                    <tr>
                        <td style="padding-bottom: 12px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 14px;">
                                <tr>
                                    <td width="45" style="padding: 18px 0 18px 18px; font-size: 14px; font-weight: bold; color: #ff7a1a;" valign="top">
                                        <div style="background-color: #241710; width: 28px; height: 28px; line-height: 28px; border-radius: 50%; text-align: center;">1</div>
                                    </td>
                                    <td style="padding: 18px; color: #a0a0a0; font-size: 14px; line-height: 1.5;">
                                        Użyj głównego przycisku aktywacji konta. Kliknij w napis <strong style="color: #ffffff;">Zweryfikuj adres email</strong> znajdujący się poniżej.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-bottom: 12px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 14px;">
                                <tr>
                                    <td width="45" style="padding: 18px 0 18px 18px; font-size: 14px; font-weight: bold; color: #ff7a1a;" valign="top">
                                        <div style="background-color: #241710; width: 28px; height: 28px; line-height: 28px; border-radius: 50%; text-align: center;">2</div>
                                    </td>
                                    <td style="padding: 18px; color: #a0a0a0; font-size: 14px; line-height: 1.5;">
                                        Jeśli masz problem z kliknięciem przycisku, skopiuj i wklej poniższy adres URL do swojej przeglądarki internetowej:
                                        <div style="margin-top: 10px; word-break: break-all;">
                                            <a href="{{ $url }}" style="color: #ff7a1a; font-size: 13px; text-decoration: none;">
                                                {{ $url }}
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-bottom: 35px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 14px;">
                                <tr>
                                    <td width="45" style="padding: 18px 0 18px 18px; font-size: 14px; font-weight: bold; color: #ff7a1a;" valign="top">
                                        <div style="background-color: #241710; width: 28px; height: 28px; line-height: 28px; border-radius: 50%; text-align: center;">3</div>
                                    </td>
                                    <td style="padding: 18px; color: #a0a0a0; font-size: 14px; line-height: 1.5;">
                                        Jeśli to nie Ty rejestrowałeś konto w naszym serwisie, <span style="color: #ffffff; font-weight: 600;">zignoruj tę wiadomość</span> – konto zostanie automatycznie usunięte.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>


                    <tr>
                        <td align="center" style="padding-bottom: 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                                <tr>
                                    <td align="center" bgcolor="#ff7a1a" style="border-radius: 12px; box-shadow: 0 6px 20px rgba(255,122,26,0.35);">
                                        <a href="{{ $url }}" target="_blank" style="font-size: 14px; font-weight: bold; color: #ffffff; text-decoration: none; padding: 16px 20px; display: block; border-radius: 12px; letter-spacing: 0.5px; text-transform: uppercase;">
                                            Zweryfikuj adres email
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding-top: 35px;">
                            <p style="color: #444444; font-size: 11px; margin: 0; letter-spacing: 0.5px;">
                                &copy; {{ date('Y') }} Quizzies. Wszystkie prawa zastrzeżone.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>