<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $newsletter->title }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', 'Helvetica Neue', Helvetica, sans-serif;
            background: linear-gradient(135deg, #0f0f0f 0%, #1a1a2e 100%);
            color: #ffffff;
            line-height: 1.6;
        }
        
        .container {
            max-width: 700px;
            margin: 0 auto;
            background: linear-gradient(135deg, #0f0f0f 0%, #1a1a2e 100%);
        }
        
        .header {
            background: linear-gradient(45deg, #16213e, #0f3460);
            padding: 30px 20px;
            text-align: center;
            border-bottom: 3px solid #00ffff;
            position: relative;
        }
        
        .header::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, #ff00ff, #00ffff, #ff00ff);
            animation: glow 2s ease-in-out infinite alternate;
        }
        
        @keyframes glow {
            from { box-shadow: 0 0 10px #00ffff, 0 0 20px #00ffff; }
            to { box-shadow: 0 0 20px #ff00ff, 0 0 30px #ff00ff; }
        }
        
        .logo {
            font-size: 32px;
            font-weight: bold;
            background: linear-gradient(45deg, #00ffff, #ff00ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
            text-shadow: 0 0 30px rgba(0, 255, 255, 0.3);
        }
        
        .newsletter-title {
            font-size: 24px;
            color: #ffffff;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .newsletter-date {
            color: #b0b0b0;
            font-size: 14px;
            margin-top: 8px;
        }
        
        .content {
            padding: 40px 30px;
            background: rgba(26, 26, 46, 0.8);
        }
        
        .content h1 {
            color: #00ffff;
            font-size: 28px;
            margin-bottom: 20px;
            text-shadow: 0 0 15px rgba(0, 255, 255, 0.3);
            border-bottom: 2px solid rgba(0, 255, 255, 0.3);
            padding-bottom: 10px;
        }
        
        .content h2 {
            color: #ff00ff;
            font-size: 22px;
            margin-top: 30px;
            margin-bottom: 15px;
            text-shadow: 0 0 10px rgba(255, 0, 255, 0.3);
        }
        
        .content h3 {
            color: #00ffff;
            font-size: 18px;
            margin-top: 25px;
            margin-bottom: 10px;
        }
        
        .content p {
            color: #e0e0e0;
            margin-bottom: 16px;
            font-size: 16px;
        }
        
        .content a {
            color: #00ffff;
            text-decoration: none;
            border-bottom: 1px solid transparent;
            transition: all 0.3s ease;
        }
        
        .content a:hover {
            border-bottom-color: #00ffff;
            text-shadow: 0 0 8px rgba(0, 255, 255, 0.6);
        }
        
        .content ul, .content ol {
            color: #e0e0e0;
            padding-left: 20px;
        }
        
        .content li {
            margin-bottom: 8px;
        }
        
        .content blockquote {
            background: rgba(0, 255, 255, 0.1);
            border-left: 4px solid #00ffff;
            margin: 20px 0;
            padding: 15px 20px;
            font-style: italic;
            color: #b0b0b0;
        }
        
        .content code {
            background: rgba(255, 0, 255, 0.2);
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            color: #ff00ff;
        }
        
        .content pre {
            background: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #333;
            overflow-x: auto;
            margin: 20px 0;
        }
        
        .content pre code {
            background: none;
            padding: 0;
            color: #00ffff;
        }
        
        .footer {
            background: rgba(0, 255, 255, 0.1);
            padding: 30px 20px;
            text-align: center;
            border-top: 1px solid #333;
        }
        
        .footer p {
            color: #888;
            font-size: 14px;
            margin: 8px 0;
        }
        
        .footer a {
            color: #00ffff;
            text-decoration: none;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
        
        .unsubscribe {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #444;
        }
        
        .cyber-divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #00ffff, #ff00ff, #00ffff, transparent);
            margin: 30px 0;
        }
        
        @media only screen and (max-width: 600px) {
            .container {
                width: 100% !important;
            }
            
            .header {
                padding: 20px 15px !important;
            }
            
            .logo {
                font-size: 24px !important;
            }
            
            .newsletter-title {
                font-size: 20px !important;
            }
            
            .content {
                padding: 30px 20px !important;
            }
            
            .content h1 {
                font-size: 24px !important;
            }
            
            .content h2 {
                font-size: 20px !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">🤖 {{ config('app.name') }} 🧠</div>
            <h1 class="newsletter-title">{{ $newsletter->title }}</h1>
            <div class="newsletter-date">
                📅 {{ $newsletter->send_date->format('d/m/Y') }} 
                @if($newsletter->author)
                    • 👨‍💻 {{ $newsletter->author }}
                @endif
            </div>
        </div>
        
        <!-- Content -->
        <div class="content">
            {!! $content !!}
            
            <div class="cyber-divider"></div>
            
            <div style="background: rgba(0, 255, 255, 0.1); border: 1px solid #00ffff; border-radius: 8px; padding: 20px; margin: 25px 0; text-align: center;">
                <h3 style="color: #00ffff; margin-top: 0;">💬 ¿Te gustó este newsletter?</h3>
                <p style="margin: 15px 0; color: #b0b0b0;">¡Compártelo con tus colegas y únete a nuestra comunidad de visionarios tecnológicos!</p>
                <a href="mailto:?subject=Te recomiendo este newsletter&body=Echa un vistazo a este interesante newsletter de {{ config('app.name') }}: {{ config('app.url') }}" 
                   style="display: inline-block; background: linear-gradient(45deg, #00ffff, #ff00ff); color: #000; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: bold; margin: 5px;">
                    📤 Compartir Newsletter
                </a>
                <a href="{{ config('app.url') }}" 
                   style="display: inline-block; background: linear-gradient(45deg, #ff00ff, #00ffff); color: #000; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: bold; margin: 5px;">
                    🌐 Visitar {{ config('app.name') }}
                </a>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p><strong>{{ config('app.name') }}</strong></p>
            <p>Democratizando IA para el beneficio de LATAM 🚀</p>
            <p>
                <a href="{{ config('app.url') }}">Sitio Web</a> • 
                <a href="{{ config('app.url') }}/prompts">Prompts</a> • 
                <a href="{{ config('app.url') }}/articulos">Artículos</a>
            </p>
            
            <div class="unsubscribe">
                <p style="font-size: 12px; color: #666;">
                    Estás recibiendo este newsletter porque te suscribiste a {{ config('app.name') }}.<br>
                    Este correo fue enviado a: {{ $subscriber->email }}
                </p>
                <p style="font-size: 12px; color: #666;">
                    <a href="{{ App\Http\Controllers\SubscriberController::generateUnsubscribeUrl($subscriber) }}" style="color: #888;">
                        Cancelar suscripción
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>