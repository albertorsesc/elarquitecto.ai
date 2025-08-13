<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirma tu suscripción</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #0f0f0f;">
    <div style="max-width: 600px; margin: 0 auto; background: linear-gradient(135deg, #0f0f0f 0%, #1a1a2e 100%); color: #ffffff;">
        <!-- Header -->
        <div style="background: linear-gradient(45deg, #16213e, #0f3460); padding: 40px 20px; text-align: center; border-bottom: 2px solid #ff00ff;">
            <h1 style="margin: 0; font-size: 28px; background: linear-gradient(45deg, #ff00ff, #00ffff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; color: #ff00ff; text-shadow: 0 0 20px rgba(255, 0, 255, 0.3);">
                🚀 Confirma tu suscripción
            </h1>
        </div>
        
        <!-- Content -->
        <div style="padding: 40px 20px;">
            <p style="font-size: 18px; line-height: 1.6; color: #e0e0e0; margin-bottom: 20px;">
                ¡Un paso más para unirte a la revolución! 🤖
            </p>
            
            <p style="font-size: 16px; line-height: 1.6; color: #b0b0b0; margin-bottom: 25px;">
                Hemos recibido tu solicitud para unirte a <strong style="color: #ff00ff;">{{ config('app.name') }}</strong>. Para completar tu suscripción y comenzar a recibir contenido exclusivo sobre IA, necesitamos que confirmes tu dirección de correo electrónico.
            </p>
            
            <div style="text-align: center; margin: 40px 0;">
                <a href="{{ config('app.url') }}/subscribe/{{ $subscriber->hash }}" 
                   style="display: inline-block; background: linear-gradient(45deg, #ff00ff, #00ffff); color: #000000; text-decoration: none; padding: 20px 40px; border-radius: 8px; font-weight: bold; font-size: 18px; box-shadow: 0 6px 20px rgba(255, 0, 255, 0.4); text-transform: uppercase; letter-spacing: 1px;">
                    ✨ Confirmar Suscripción ✨
                </a>
            </div>
            
            <div style="background: rgba(255, 0, 255, 0.1); border: 1px solid #ff00ff; border-radius: 8px; padding: 20px; margin: 25px 0;">
                <p style="color: #ff00ff; margin: 0; font-size: 14px; line-height: 1.6;">
                    <strong>💡 Tip:</strong> Si no puedes hacer clic en el botón, copia y pega este enlace en tu navegador:
                </p>
                <p style="word-break: break-all; color: #00ffff; font-size: 12px; margin: 10px 0 0 0;">
                    {{ config('app.url') }}/subscribe/{{ $subscriber->hash }}
                </p>
            </div>
            
            <p style="font-size: 14px; line-height: 1.6; color: #808080; margin-top: 30px;">
                Este enlace expirará en 24 horas por seguridad. Si no solicitaste esta suscripción, puedes ignorar este correo.
            </p>
        </div>
        
        <!-- Footer -->
        <div style="background: rgba(255, 0, 255, 0.1); padding: 20px; text-align: center; border-top: 1px solid #333;">
            <p style="margin: 0; font-size: 12px; color: #666;">
                © {{ date('Y') }} {{ config('app.name') }} - El futuro de la IA en tus manos
            </p>
        </div>
    </div>
</body>
</html>