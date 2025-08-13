<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Bienvenido a El Arquitecto AI!</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #0f0f0f;">
    <div style="max-width: 600px; margin: 0 auto; background: linear-gradient(135deg, #0f0f0f 0%, #1a1a2e 100%); color: #ffffff;">
        <!-- Header -->
        <div style="background: linear-gradient(45deg, #16213e, #0f3460); padding: 40px 20px; text-align: center; border-bottom: 2px solid #00ffff;">
            <h1 style="margin: 0; font-size: 28px; background: linear-gradient(45deg, #00ffff, #ff00ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; color: #00ffff; text-shadow: 0 0 20px rgba(0, 255, 255, 0.3);">
                🤖 ¡Bienvenido a {{ config('app.name') }}! 🧠
            </h1>
        </div>
        
        <!-- Content -->
        <div style="padding: 40px 20px;">
            <p style="font-size: 18px; line-height: 1.6; color: #e0e0e0; margin-bottom: 20px;">
                ¡Hola! 👋
            </p>
            
            <p style="font-size: 16px; line-height: 1.6; color: #b0b0b0; margin-bottom: 25px;">
                Gracias por unirte a nuestra comunidad de visionarios tecnológicos. Tu suscripción ha sido <strong style="color: #00ffff;">confirmada</strong> y ahora formas parte de la revolución de la IA en Latinoamérica.
            </p>
            
            <div style="background: rgba(0, 255, 255, 0.1); border: 1px solid #00ffff; border-radius: 8px; padding: 20px; margin: 25px 0;">
                <h3 style="color: #00ffff; margin-top: 0; font-size: 18px;">🚀 ¿Qué te espera?</h3>
                <ul style="color: #b0b0b0; line-height: 1.6; margin-left: 0; padding-left: 20px;">
                    <li>Artículos exclusivos sobre IA y tecnología emergente</li>
                    <li>Prompts optimizados para maximizar tu productividad</li>
                    <li>Recursos gratuitos y herramientas de vanguardia</li>
                    <li>Contenido en español diseñado para LATAM</li>
                </ul>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ config('app.url') }}" style="display: inline-block; background: linear-gradient(45deg, #00ffff, #ff00ff); color: #000000; text-decoration: none; padding: 15px 30px; border-radius: 8px; font-weight: bold; font-size: 16px; box-shadow: 0 4px 15px rgba(0, 255, 255, 0.3);">
                    🌐 Explorar {{ config('app.name') }}
                </a>
            </div>
            
            <p style="font-size: 14px; line-height: 1.6; color: #808080; margin-top: 30px;">
                ¿Tienes preguntas? Simplemente responde a este correo y estaremos encantados de ayudarte.
            </p>
        </div>
        
        <!-- Footer -->
        <div style="background: rgba(0, 255, 255, 0.1); padding: 20px; text-align: center; border-top: 1px solid #333;">
            <p style="margin: 0; font-size: 12px; color: #666;">
                © {{ date('Y') }} {{ config('app.name') }} - Democratizando IA para el beneficio de LATAM
            </p>
        </div>
    </div>
</body>
</html>