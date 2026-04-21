---
title: "Paperclip"
slug: "paperclip"
excerpt: "Orquesta un equipo de agentes de IA para correr una empresa completa — con org chart, presupuestos y gobernanza, 100% open source"
business_model: "open_source"

website_url: "https://github.com/paperclipai/paperclip"
pricing_url: ""
documentation_url: "https://paperclip.ing/docs"

# Monetización — MIT, self-hosted, sin programa de afiliados.

research:
  score: 7
  validated_at: "2026-04-20"
  sources:
    - "https://github.com/paperclipai/paperclip"
    - "https://paperclip.ing/docs"
  alternatives_considered:
    - "LangGraph (framework más técnico, sin gobernanza empresarial)"
    - "AutoGen (Microsoft, multi-agente sin capa de presupuestos)"
    - "CrewAI (teams de agentes, pero sin org chart ni audit)"
  why_include: "Llena un hueco único — no es un framework de agentes, es el PEGAMENTO que hace posible correr una empresa autónoma con múltiples agentes (OpenClaw, Claude Code, Hermes, etc.). 56.9K estrellas en GitHub. Para LATAM: oportunidad para solo-founders que quieren lanzar negocios con costo operativo cercano a cero usando IA."

meta_title: "Paperclip — Orquesta agentes IA para correr tu empresa"
meta_description: "Servidor open source (MIT) que coordina múltiples agentes de IA con presupuestos, org chart y gobernanza. Conecta OpenClaw, Claude Code, Cursor, Hermes."
meta_keywords: ["AI agents orchestration", "autonomous company", "multi-agent", "open source", "LATAM"]

categories: ["agentes", "automatizacion"]
tags: ["multi-agente", "codigo-abierto", "llm-ops"]

is_featured: true
featured_image: "paperclip.png"

published_at: "2026-04-20 14:00:00"
timezone: "America/Tijuana"
---

## ¿Qué es Paperclip?

Paperclip es un servidor de Node.js con interfaz web que **orquesta un equipo de agentes de IA para correr una empresa completa**. No es un agente — es el sistema operativo para muchos agentes trabajando juntos con propósito.

La tesis detrás: si en 2026 los modelos de IA cuestan céntimos por tarea, entonces un "empleado IA" cuesta órdenes de magnitud menos que un humano. Pero si tienes 10 agentes trabajando en paralelo, necesitas coordinación, presupuestos, jerarquías, y decisiones de alto nivel — exactamente lo que Paperclip provee.

Su slogan: *"Open-source orchestration for zero-human companies"* (Orquestación open source para empresas de cero humanos). Provocador, pero literal — varios fundadores ya lo usan para correr negocios de una sola persona donde casi todo el trabajo operativo lo hacen agentes.

**56,900 estrellas en GitHub**, licencia MIT, versión estable v2026.416.0.

## Características principales

- **Bring Your Own Agent**: conecta cualquier agente — [OpenClaw](https://elarquitecto.ai/herramientas/openclaw), [Hermes Agent](https://elarquitecto.ai/herramientas/hermes-agent), Claude Code, Codex, Cursor, scripts Bash, o endpoints HTTP custom
- **Org chart y jerarquías**: defines roles, líneas de reporte, y cadenas de aprobación — como una empresa real pero con IA en cada puesto
- **Presupuestos por agente**: límite mensual de gasto por cada agente. Si uno se pasa, se detiene automáticamente — cero riesgo de runaway spending
- **Heartbeats programables**: cada agente se "despierta" a intervalos (o por eventos) para revisar tareas pendientes y continuar
- **Goal alignment**: toda tarea se conecta a la misión de la empresa — los agentes saben POR QUÉ hacen lo que hacen
- **Sistema de tickets con audit log**: cada conversación, decisión y acción queda registrada de forma inmutable. Si algo sale mal, puedes rastrear la causa
- **Governance**: aprobaciones a nivel board para decisiones grandes (gastar >$X, contratar a un agente nuevo, cambiar estrategia)
- **Multi-empresa aislada**: puedes correr varias empresas en paralelo con aislamiento completo de datos y auditoría

## ¿Para quién es?

**Solo-founders ambiciosos en LATAM**: Si sueñas con lanzar un negocio que corra casi solo con IA — esta es la infraestructura. Un fundador + Paperclip + 5-10 agentes (OpenClaw para comunicación, Hermes para research, Claude Code para producto, etc.) puede manejar operaciones de una empresa que antes requería 20 personas.

**Desarrolladores construyendo SaaS con agentes**: Si quieres vender un servicio donde los "empleados" son agentes, Paperclip te da el backbone para orquestarlos con presupuestos y auditoría — crítico si los clientes dependen del servicio.

**Equipos chicos que quieren escalar sin contratar**: En lugar de contratar más humanos para tareas operativas (soporte, QA, research, reporting), Paperclip te deja escalar con agentes bajo un modelo de "managers humanos" dirigiendo "equipos de agentes".

**No es para ti si:** quieres un chatbot simple, un flujo con drag-and-drop visual, o si tu caso es solo 1 agente para uso personal. Para eso usa OpenClaw o Hermes directamente.

## Precio

**Gratis** — licencia MIT, self-hosted, sin límite de usuarios ni agentes.

Costos reales para una empresa con 5 agentes activos:

- **LLM para los agentes**: depende de qué modelos uses. Con modelos baratos (DeepSeek V3 vía OpenRouter): $20-50 USD/mes típico. Con modelos premium (Claude Opus 4.7): $200-500 USD/mes para volumen moderado.
- **Hosting**: un VPS de $10-20 USD/mes corre Paperclip + PostgreSQL fácilmente para equipos de hasta 10 agentes
- **Tu tiempo**: las primeras 2 semanas son de setup y calibración. Es un sistema serio, no un juguete.

**Total típico**: $30-70 USD/mes para una "empresa" operativa de 3-5 agentes. Compáralo con contratar 1 persona junior en LATAM (~$800-1500 USD/mes) y la ecuación económica es clara.

## Mi veredicto

**Lo bueno:**
- Resuelve un problema real que ningún otro framework de agentes resuelve: gobernanza, presupuestos, y estructura organizacional
- La integración con múltiples ecosistemas (OpenClaw, Claude Code, Cursor, Hermes) significa que NO te amarra a un stack
- El audit log inmutable es crítico si vas a usarlo para algo serio — si un agente tomó una mala decisión, puedes rastrear exactamente qué pasó y por qué
- Open source MIT = sin riesgo de vendor lock-in ni precios que se disparen
- Activo desarrollo (2,283 commits) y comunidad sólida en Discord

**Lo no tan bueno:**
- La curva de aprendizaje es SERIA. No es una app que abres y usas en 30 minutos. Plan para 1-2 semanas de setup si quieres algo real
- Requiere infraestructura propia — PostgreSQL, Node.js, el servidor. Si no tienes experiencia con sysadmin, esto agrega fricción
- La filosofía de "zero-human companies" es polémica. En LATAM hay consideraciones éticas y legales sobre automatizar trabajos — úsalo con criterio
- Todavía emergente: aunque tiene 56K estrellas, el número de empresas corriéndolo en producción es menor. Estás cerca del bleeding edge

**¿Vale la pena?** Sí — pero solo si tienes una visión concreta para un negocio que quieres correr con agentes. Si lo ves como "experimento para ver qué pasa", es sobre-ingeniería. Si lo ves como "quiero lanzar una agencia de marketing corrida 80% por IA en 6 meses", es exactamente la herramienta.

## 💡 Tip práctico

No intentes diseñar toda tu empresa en Paperclip el primer día. Empieza con **una sola tarea repetitiva** que actualmente te quita tiempo y pruébala:

```bash
npx paperclipai onboard --yes
```

Define UN agente (ej: "Agente de research matutino") con UN solo heartbeat diario (ej: 7am), UN solo objetivo (ej: "revisar 3 fuentes RSS y hacer resumen de 200 palabras"), y UN presupuesto chico ($5 USD/mes).

Corre ese sistema por 2 semanas. Cuando te haya ahorrado tiempo real y confíes en él, añade el segundo agente. Escala con paciencia — Paperclip es un amplificador, y si amplifica un sistema mal diseñado, tendrás caos escalado. Si amplifica un sistema pensado, tendrás una empresa que corre sola.
