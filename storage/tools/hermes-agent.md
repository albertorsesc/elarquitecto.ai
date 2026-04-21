---
title: "Hermes Agent"
slug: "hermes-agent"
excerpt: "Agente de IA open source que aprende de ti — crea sus propias habilidades con el uso y compone memoria a lo largo del tiempo"
business_model: "open_source"

website_url: "https://github.com/NousResearch/hermes-agent"
pricing_url: ""
documentation_url: "https://hermes-agent.nousresearch.com/docs/"

# Monetización — Hermes es open source (MIT). No programa de afiliados.

research:
  score: 9
  validated_at: "2026-04-20"
  sources:
    - "https://github.com/NousResearch/hermes-agent"
    - "https://hermes-agent.nousresearch.com/"
    - "https://dev.to/tokenmixai/hermes-agent-review-956k-stars-self-improving-ai-agent-april-2026-11le"
    - "https://tokenmix.ai/blog/hermes-agent-review-self-improving-open-source-2026"
  alternatives_considered:
    - "OpenClaw (breadth sobre 20+ canales, 13K+ skills comunitarios)"
    - "LangChain Agents (framework más técnico, sin loop de aprendizaje)"
    - "AutoGen de Microsoft (multi-agente, sin memoria compuesta)"
  why_include: "Hermes llena un hueco distinto al de OpenClaw — mientras OpenClaw apuesta a amplitud (muchos canales, muchos skills), Hermes apuesta a profundidad (aprende de ti con el tiempo, crea sus propios skills). 105K estrellas en 8 semanas = la adopción más rápida del 2026 en agentes IA. Más seguro que OpenClaw (118 skills curados vs 13K+ comunitarios) — punto importante para LATAM donde la reputación del canal importa."

meta_title: "Hermes Agent — Agente IA que aprende de ti"
meta_description: "Agente de IA open source de Nous Research. Crea sus propias habilidades con el uso, tiene memoria entre sesiones, y corre en 200+ modelos. MIT, gratis."
meta_keywords: ["AI agent", "Nous Research", "self-improving", "agentes IA", "open source"]

categories: ["ai", "agentes"]
tags: ["cli", "codigo-abierto", "llm", "agente-de-razonamiento"]

is_featured: true
featured_image: "banner.png"

published_at: "2026-04-20 12:00:00"
timezone: "America/Tijuana"
---

## ¿Qué es Hermes Agent?

Hermes Agent es un agente de IA que **aprende de ti conforme lo usas**. No es solo un asistente que responde — es un sistema que:

1. Crea sus propias habilidades (skills) cuando detecta patrones en lo que le pides
2. Guarda memoria entre sesiones (recuerda quién eres, tus proyectos, tus preferencias)
3. Mejora esas habilidades con cada uso

Lo lanzó [Nous Research](https://nousresearch.com) el 25 de febrero de 2026 y en 8 semanas acumuló más de **105,000 estrellas en GitHub** — la adopción más rápida del año en herramientas de IA. Su slogan — *"The agent that grows with you"* (el agente que crece contigo) — no es marketing: es una descripción literal de cómo funciona.

## Características principales

- **Learning loop cerrado**: el agente crea y mejora sus propias habilidades desde la experiencia. Benchmarks independientes muestran que después de ~30 días de uso, tareas comunes se ejecutan 40% más rápido que con un agente fresco
- **Memoria compuesta entre sesiones**: FTS5 (búsqueda en tus conversaciones pasadas) + resumen por LLM + un modelo de ti que se profundiza con el tiempo
- **Multi-canal**: terminal con autocompletado, Telegram, Discord, Slack, WhatsApp, Signal, Email, Home Assistant
- **200+ modelos soportados**: OpenRouter, Nous Portal, NVIDIA NIM, Hugging Face, OpenAI, Anthropic, o tu endpoint custom
- **Automatizaciones programadas**: cron interno para tareas desatendidas
- **Subagentes aislados**: spawn de agentes paralelos con delegación de scripts Python vía RPC
- **Infra flexible**: corre local, Docker, SSH, Daytona, Singularity, Modal. Desde un VPS de $5 USD/mes hasta serverless que hiberna cuando no lo usas
- **118 habilidades curadas** listas para usar (vs miles sin curar en otros frameworks — más seguro, menos superficie de ataque)

## ¿Para quién es?

**Si eres desarrollador solista o equipo chico** que usa IA todos los días y quiere que la herramienta se vuelva más útil con el tiempo: Hermes compone valor como interés compuesto. Los primeros días haces trabajo normal; a los 3 meses, el agente ya sabe cómo trabajas y te anticipa.

**Si haces research o experimentación con modelos de IA**: Hermes incluye batch trajectory generation, ambientes RL (Atropos), y compresión de trayectorias para entrenar tus propios modelos. Es un puente entre "usar IA" y "entrenar IA".

**Si valoras seguridad y curación**: la comparación con OpenClaw es relevante. OpenClaw acepta skills comunitarios con mínima revisión (reportó 9 CVEs en 4 días recientemente). Hermes tiene 118 skills revisados — menos cantidad, mucho menos riesgo.

**Si eres no técnico o buscas plug-and-play**: NO es para ti. Requiere terminal, Python, uv/pip, comfort con Docker. Considera OpenClaw o una alternativa con GUI.

## Precio

**Gratis** — licencia MIT, sin suscripción.

Costo real mensual para uso personal diario:
- **Modelo LLM**: ~$0.30 USD por tarea compleja en modelos baratos (DeepSeek V3, Llama 3.3). Si haces 30 tareas complejas al mes, son ~$9 USD.
- **Hosting (opcional)**: VPS de $5-10 USD/mes si lo quieres corriendo 24/7. En modo serverless hiberna y paga casi nada.

**Total típico**: $10-20 USD/mes para uso intenso. Gratis si corres modelos locales con Ollama.

## Mi veredicto

**Lo bueno:**
- La tesis de "agente que aprende" no es hype — hay data independiente que muestra que funciona (40% de reducción de tiempo después de uso sostenido)
- Infra flexible de verdad: el mismo código corre en tu laptop, en un VPS de $5 USD, o en Modal serverless
- El canal de mensajería está separado del agente, lo que significa que puedes responder desde tu celular mientras el agente corre en un servidor
- Seguridad: 118 skills curados es una ventaja real sobre frameworks con marketplaces abiertos sin moderación
- Interoperabilidad: compatible con MCP (Model Context Protocol) y [agentskills.io](https://agentskills.io) — no te encierra en un ecosistema

**Lo no tan bueno:**
- La instalación requiere comodidad con terminal y Python
- Los 118 skills curados son menos que los 13K+ de competidores — si necesitas integración con algo muy específico, puede que no exista un skill listo (pero puedes escribirlo)
- La curva de aprendizaje es más larga — los primeros días el agente no "sabe" nada de ti, y vale la pena invertir 1-2 semanas de uso antes de juzgarlo

**¿Vale la pena?** Sí, absolutamente, si eres desarrollador y usas IA diario. La tesis de "compounding learning" es lo que faltaba en los agentes actuales — la mayoría empieza desde cero en cada conversación. Hermes construye contigo a lo largo del tiempo.

## 💡 Tip práctico

Instala Hermes con **un solo modelo local** (vía Ollama) para probarlo los primeros días sin gastar en APIs:

```bash
curl -fsSL https://raw.githubusercontent.com/NousResearch/hermes-agent/main/scripts/install.sh | bash
```

Arráncalo con `hermes --model ollama/llama3.3` y pasa la primera semana usándolo para tareas reales de tu trabajo diario. Después de ~7 días ya vas a ver cómo se acumula contexto sobre ti. En ese momento, activa modelos pagados (Claude o GPT) solo para tareas que genuinamente lo necesiten.

La trampa que veo más: la gente lo prueba 10 minutos, no ve la magia, y lo borra. La magia se construye con el uso, no sale en el primer día.
