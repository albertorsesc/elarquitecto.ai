<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Confirma tu suscripción a {{ config('app.name') }}</title>
</head>
<body style="margin:0;padding:0;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;background-color:#0f0f0f;color:#ffffff;line-height:1.6;font-size:16px;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#0f0f0f;">
<tr><td align="center" style="padding:0;">
<table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;background-color:#1a1a2e;">

<!-- HEADER -->
<tr>
<td align="center" style="background-color:#16213e;padding:30px 20px;border-bottom:3px solid #ff00ff;" bgcolor="#16213e">
<p style="font-size:28px;font-weight:bold;color:#ff00ff;margin:0 0 10px 0;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;line-height:1.2;">🤖 {{ config('app.name') }} 🧠</p>
<h1 style="font-size:22px;color:#ffffff;margin:0;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;line-height:1.3;">Un paso más — confirma tu correo</h1>
</td>
</tr>

<!-- CONTENT -->
<tr>
<td style="padding:40px 30px;background-color:#1a1a2e;" bgcolor="#1a1a2e">

<p style="font-size:16px;color:#e0e0e0;line-height:1.6;margin:0 0 16px 0;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;">¡Hola! 👋</p>

<p style="font-size:16px;color:#e0e0e0;line-height:1.6;margin:0 0 16px 0;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;">Casi estás dentro. Solo falta que confirmes tu dirección de correo haciendo clic en el botón:</p>

<!-- CTA button -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:30px 0;">
<tr><td align="center">
<table cellpadding="0" cellspacing="0" border="0">
<tr><td align="center" style="border-radius:8px;background-color:#ff00ff;" bgcolor="#ff00ff">
<a href="{{ config('app.url') }}/subscribe/{{ $subscriber->hash }}" target="_blank" style="display:inline-block;padding:16px 36px;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;font-size:16px;font-weight:bold;color:#000000;text-decoration:none;border-radius:8px;letter-spacing:0.5px;">✨ Confirmar suscripción</a>
</td></tr>
</table>
</td></tr>
</table>

<!-- What to expect -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#0d2635;border:1px solid #00ffff;border-radius:8px;margin:25px 0;" bgcolor="#0d2635">
<tr><td style="padding:20px;">
<p style="font-size:14px;color:#00ffff;margin:0 0 10px 0;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;line-height:1.5;"><strong>📬 Qué vas a recibir después de confirmar:</strong></p>
<p style="font-size:14px;color:#e0e0e0;margin:0;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;line-height:1.6;">Todos los lunes en la mañana — 3 noticias importantes con enfoque LATAM, una herramienta con mi veredicto honesto, un prompt listo para copiar y pegar, y un BONUS técnico para los que quieren ir más profundo.</p>
</td></tr>
</table>

<!-- Fallback link -->
<p style="font-size:13px;color:#888888;line-height:1.6;margin:25px 0 8px 0;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;">Si el botón no funciona, copia y pega este enlace en tu navegador:</p>
<p style="font-size:12px;color:#00ffff;word-break:break-all;margin:0 0 25px 0;font-family:'Courier New',monospace;line-height:1.4;">{{ config('app.url') }}/subscribe/{{ $subscriber->hash }}</p>

<p style="font-size:13px;color:#808080;line-height:1.6;margin:25px 0 0 0;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;">Si no solicitaste esta suscripción, puedes ignorar este correo y nunca más escucharás de nosotros.</p>

</td>
</tr>

<!-- FOOTER -->
<tr>
<td style="background-color:#0a1628;padding:25px 20px;text-align:center;border-top:1px solid #333333;" bgcolor="#0a1628">
<p style="color:#888888;font-size:14px;margin:4px 0;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;line-height:1.4;"><strong style="color:#b0b0b0;">{{ config('app.name') }}</strong></p>
<p style="color:#888888;font-size:13px;margin:4px 0;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;line-height:1.4;">Democratizando IA para el beneficio de LATAM 🚀</p>
</td></tr>

</table>
</td></tr>
</table>

</body>
</html>
