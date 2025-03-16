<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Our Newsletter</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Base styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #111827;
            color: #F9FAFB;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Container */
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Glass effect card */
        .glass-card {
            background-color: rgba(18, 18, 18, 0.7);
            border-radius: 12px;
            padding: 30px;
            margin: 20px 0;
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(124, 58, 237, 0.3),
                        0 8px 30px rgba(124, 58, 237, 0.2);
        }

        /* Corner accents */
        .corner {
            position: absolute;
            width: 20px;
            height: 20px;
        }

        .top-left {
            top: 0;
            left: 0;
            border-top: 1px solid #7C3AED;
            border-left: 1px solid #7C3AED;
        }

        .top-right {
            top: 0;
            right: 0;
            border-top: 1px solid #22D3EE;
            border-right: 1px solid #22D3EE;
        }

        .bottom-left {
            bottom: 0;
            left: 0;
            border-bottom: 1px solid #EC4899;
            border-left: 1px solid #EC4899;
        }

        .bottom-right {
            bottom: 0;
            right: 0;
            border-bottom: 1px solid #60A5FA;
            border-right: 1px solid #60A5FA;
        }

        /* Neon borders */
        .neon-border-top {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(to right, transparent, #7C3AED, transparent);
            opacity: 0.7;
        }

        .neon-border-bottom {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(to right, transparent, #22D3EE, transparent);
            opacity: 0.7;
        }

        /* Typography */
        h1 {
            color: #7C3AED;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            text-shadow: 0 0 10px rgba(124, 58, 237, 0.5);
        }

        p {
            line-height: 1.6;
            margin-bottom: 20px;
            color: rgba(249, 250, 251, 0.8);
        }

        /* Button */
        .btn {
            display: inline-block;
            background-color: rgba(124, 58, 237, 0.1);
            color: #7C3AED;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin: 20px 0;
            border: 1px solid rgba(124, 58, 237, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .btn:hover {
            background-color: rgba(124, 58, 237, 0.2);
            box-shadow: 0 0 15px rgba(124, 58, 237, 0.5);
        }

        /* Logo */
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            max-width: 120px;
            height: auto;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 14px;
            color: rgba(249, 250, 251, 0.6);
        }

        /* Responsive */
        @media only screen and (max-width: 600px) {
            .container {
                width: 100%;
                padding: 10px;
            }

            .glass-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="{{ asset('logo.png') }}" alt="{{ config('app.name') }} Logo">
        </div>

        <div class="glass-card">
            <!-- Corner accents -->
            <div class="corner top-left"></div>
            <div class="corner top-right"></div>
            <div class="corner bottom-left"></div>
            <div class="corner bottom-right"></div>

            <!-- Neon borders -->
            <div class="neon-border-top"></div>
            <div class="neon-border-bottom"></div>

            <h1>¡Bienvenido a Nuestro Newsletter!</h1>

            <p>Gracias por suscribirte a nuestro newsletter. Por favor, haz clic en el enlace a continuación para verificar tu dirección de correo electrónico:</p>

            <div style="text-align: center;">
                <a href="{{ route('subscribe.confirm', $user->hash) }}" class="btn">Verificar Dirección de Correo</a>
            </div>

            <p>Si no solicitaste esta suscripción, no es necesario realizar ninguna acción adicional.</p>
        </div>

        <div class="footer">
            <p>Saludos,<br>{{ config('app.name') }}</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}.</p>
        </div>
    </div>
</body>
</html>