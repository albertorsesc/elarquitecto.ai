---
title: "OpenClaw"
slug: "openclaw"
excerpt: "Asistente de IA personal que vive en tus dispositivos y te responde directo en WhatsApp, Telegram, Slack y 20+ canales más"
business_model: "open_source"

website_url: "https://github.com/openclaw/openclaw"
pricing_url: ""
documentation_url: "https://github.com/openclaw/openclaw/blob/main/README.md"

# Monetización — OpenClaw es open source (MIT), sin programa de afiliados.
# No incluir bloque affiliate.

research:
  score: 8
  validated_at: "2026-04-20"
  sources:
    - "https://github.com/openclaw/openclaw"
    - "https://medium.com/data-science-collective/355k-github-stars-in-5-months-17-defense-rate-the-complete-honest-guide-to-openclaw-28d2f59598e1"
    - "https://www.oneclaw.net/blog/openclaw-ai-github"
  alternatives_considered:
    - "Nanobot (Python, 4K LOC)"
    - "PicoClaw (Go, <10MB RAM)"
    - "n8n (self-hosted)"
  why_include: "Casa el problema real de LATAM — responder IA donde la gente ya chatea (WhatsApp). Local-first + MIT + free con modelos locales = costo cero posible. 247K+ estrellas en GitHub en 5 meses = tracción masiva y comunidad activa."

meta_title: "OpenClaw — Asistente de IA que vive en tus dispositivos"
meta_description: "Corre tu propio asistente de IA en tu compu, responde en WhatsApp, Telegram y 20+ canales. Open source, MIT, funciona con modelos locales o pagos."
meta_keywords: ["AI assistant", "WhatsApp bot", "open source", "local-first", "agentes IA"]

categories: ["ai", "codigo"]
tags: ["cli", "codigo-abierto", "llm"]

is_featured: true
featured_image: "openclaw.png"

published_at: "2026-04-20 09:00:00"
timezone: "America/Tijuana"
---

## ¿Qué es OpenClaw?

OpenClaw es un asistente de IA que corre en **tus propios dispositivos** (Mac, iPhone, Android, Linux) y te responde en los canales donde ya chateas: WhatsApp, Telegram, Slack, Discord, iMessage, Signal y más de 20 plataformas.

La diferencia clave con ChatGPT, Claude o Gemini: **no tienes que abrir otra app**. La IA llega a ti donde ya estás. Le mandas un mensaje por WhatsApp como si fuera un contacto, y te responde — usando el modelo de IA que tú elijas (GPT, Claude, o uno local gratuito).

Es **open source (licencia MIT)** y pasó de 0 a más de 247,000 estrellas en GitHub en 5 meses. Para ponerlo en contexto: esa es la curva de crecimiento más agresiva que hemos visto en un proyecto open source de IA.

## Características principales

- **Multi-canal**: WhatsApp, Telegram, Slack, Discord, iMessage, Signal, Matrix, Microsoft Teams, WeChat, LINE y 15+ más — todo conectado al mismo asistente
- **Local-first**: el "gateway" que orquesta todo corre en tu máquina. Tus conversaciones no pasan por servidores de terceros (a menos que uses un modelo de IA en la nube, obviamente)
- **Voz**: palabra-gatillo en macOS/iOS, modo de voz continuo en Android
- **Live Canvas (A2UI)**: un espacio visual donde el agente puede renderizar gráficas, formularios y controles que tú manipulas en tiempo real
- **Multi-agente**: puedes tener agentes especializados para distintos canales o cuentas (ej: "agente de trabajo" en Slack, "agente personal" en WhatsApp) con sesiones aisladas
- **Modelo de IA a tu elección**: OpenAI, Claude, Gemini, o modelos locales vía Ollama

## ¿Para quién es?

**Si eres emprendedor o freelancer en LATAM** que vive en WhatsApp: imagina poder resumir chats largos, agendar tareas, traducir mensajes de clientes, o preparar respuestas — todo sin salir de WhatsApp. Eso es OpenClaw.

**Si eres desarrollador** que quiere experimentar con agentes de IA pero no quiere depender de un SaaS: es el runtime más completo que existe para hospedar tus propios agentes con acceso a canales reales.

**Si valoras la privacidad**: corriendo con Ollama + Gemma localmente, cero datos salen de tu máquina. Para trabajo sensible, es superior a cualquier asistente en la nube.

**Si buscas una herramienta plug-and-play sin setup técnico**: esto NO es para ti. Requiere instalar Node.js, usar línea de comandos, y configurar tus llaves de API.

## Precio

**Gratis** — licencia MIT, sin costo de licencia ni suscripción.

Pero necesitas un modelo de IA detrás. Tus opciones:

- **Cero costo (local)**: [Ollama](https://ollama.com) + Gemma 4. Corre en cualquier Mac con 16GB de RAM. No tan potente como Claude o GPT-4, pero útil para tareas comunes.
- **Open source en la nube barato**: [OpenRouter](https://openrouter.ai) te da acceso a los mejores modelos open source del mundo con una sola API key, cobrando por uso real. Es el punto medio entre local-gratis y APIs premium.
- **APIs premium**: conecta tu key de OpenAI, Anthropic o Google directo. Máxima capacidad, máximo costo.

### Comparativa de costos (abril 2026, precio por millón de tokens)

| Modelo | Entrada | Salida | Notas |
|---|---|---|---|
| Gemma 3 (OpenRouter) | **Gratis** | **Gratis** | Tier gratuito con límites de uso |
| Llama 3.3 70B (OpenRouter) | $0.12 | $0.38 | El caballo de batalla open source |
| DeepSeek V3 (OpenRouter) | $0.32 | $0.89 | Gran razonamiento, MoE |
| Qwen 3.6 Plus (OpenRouter) | $0.33 | $1.95 | Fuerte en multilingüe |
| DeepSeek R1 Distill 70B | $0.70 | $0.80 | Razonamiento tipo "o1" |
| **Claude Opus 4.7** (Anthropic) | **$15.00** | **$75.00** | Referencia premium |

Para que tengas perspectiva: con Llama 3.3 70B gastas **125x menos en tokens de entrada** que con Claude Opus 4.7. Para la mayoría de tareas diarias de un asistente personal (resumir, traducir, redactar respuestas), Llama 3.3 es más que suficiente. Reserva Claude Opus para cuando realmente necesites razonamiento complejo.

- **Híbrido recomendado**: Ollama + Gemma para tareas sensibles/locales, Llama 3.3 vía OpenRouter para todo lo demás, Claude Opus para tareas que genuinamente lo pidan.

## Mi veredicto

**Lo bueno:**
- La tracción (247K estrellas en 5 meses) no miente — hay una comunidad masiva y desarrollo activo
- La cobertura de canales es incomparable: ningún competidor comercial conecta tantas plataformas
- El enfoque local-first es genuino. Tus datos son tuyos.
- Funciona gratis con modelos locales — clave para emprendedores LATAM con presupuestos ajustados

**Lo no tan bueno:**
- La instalación NO es casual. Si nunca has abierto una terminal, prepárate para aprender
- Palo Alto Networks publicó un análisis llamando a OpenClaw una "pesadilla de seguridad" por sus 430,000+ líneas de código — mucha superficie de ataque. Si vas a usarlo para datos sensibles, ponlo tras firewall y revisa los permisos de cada plugin
- Es overkill si solo quieres un bot de WhatsApp para responder preguntas simples — hay alternativas más ligeras (Nanobot de 4,000 líneas hace el 80% en Python puro)

**¿Vale la pena?** Sí — si estás dispuesto a invertir medio día en el setup y tienes curiosidad técnica. El retorno es un asistente de IA que vive donde tú vives, operable 100% desde WhatsApp, sin depender de ninguna empresa. Para LATAM, donde WhatsApp ES el sistema operativo social, esto cambia la ecuación completa de cómo usamos IA.

## 💡 Tip práctico

No intentes configurar los 20 canales en el primer intento. Instala OpenClaw con un solo canal primero — recomiendo **Telegram** porque el bot API es el más documentado y el más tolerante a errores.

```bash
npm install -g openclaw@latest
openclaw onboard --install-daemon
```

Configura solo Telegram, haz que funcione con un modelo local (Ollama + Gemma), y recién cuando lo tengas respondiéndote de forma consistente, agrega WhatsApp. Si intentas todo al mismo tiempo y algo falla, vas a perder horas debuggeando sin saber dónde está el problema.
