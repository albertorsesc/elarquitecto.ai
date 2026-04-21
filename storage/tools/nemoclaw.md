---
title: "NemoClaw"
slug: "nemoclaw"
excerpt: "La capa de seguridad para correr OpenClaw en producción — sandboxing, políticas de red y modelos Nemotron de NVIDIA, open source"
business_model: "open_source"

website_url: "https://github.com/NVIDIA/NemoClaw"
pricing_url: ""
documentation_url: "https://github.com/NVIDIA/NemoClaw#readme"

# Monetización — Apache 2.0, NVIDIA mantiene el proyecto, sin afiliados.

research:
  score: 7
  validated_at: "2026-04-20"
  sources:
    - "https://github.com/NVIDIA/NemoClaw"
    - "https://github.com/openclaw/openclaw"
  alternatives_considered:
    - "OpenClaw solo (sin capa de seguridad — riesgoso en producción)"
    - "Docker containers custom (más trabajo, menos opinionated)"
    - "Firecracker VMs (más seguro pero mucho más complejo)"
  why_include: "Resuelve el problema práctico de 'cómo corro OpenClaw sin exponerme a ataques'. Palo Alto Networks reportó 9 CVEs en OpenClaw recientemente — NemoClaw es la respuesta de NVIDIA a ese problema. Para desarrolladores LATAM que quieren poner OpenClaw frente a clientes o en producción, esta capa es esencial. 19.6K estrellas indica adopción real en la comunidad enterprise."

meta_title: "NemoClaw — Capa de seguridad para OpenClaw por NVIDIA"
meta_description: "Wrapper open source de NVIDIA para correr OpenClaw con sandboxing, Landlock, seccomp y políticas de red. Apache 2.0. Ideal para producción."
meta_keywords: ["OpenClaw security", "NVIDIA Nemotron", "agent sandboxing", "AI agents production"]

categories: ["ai", "agentes"]
tags: ["codigo-abierto", "llm-ops", "cli"]

is_featured: false
featured_image: "social.png"

published_at: "2026-04-20 16:00:00"
timezone: "America/Tijuana"
---

## ¿Qué es NemoClaw?

NemoClaw es la **capa de seguridad que envuelve a OpenClaw** para que lo puedas correr en producción sin que se convierta en un riesgo. Lo mantiene NVIDIA y es open source (Apache 2.0).

Para entender por qué existe: [OpenClaw](https://elarquitecto.ai/herramientas/openclaw) es potente pero amplio. Maneja 20+ canales, ejecuta código, conecta a tu sistema de archivos, manda mensajes por ti. Eso es útil para uso personal pero peligroso para uso frente a clientes. Palo Alto Networks reportó **9 CVEs (vulnerabilidades críticas)** en OpenClaw recientemente. Si tu agente envía un mensaje por WhatsApp que no debería, o ejecuta un comando que borra archivos, es tu responsabilidad.

NemoClaw resuelve esto con **aislamiento a nivel de kernel Linux**. Cada ejecución del agente corre en un sandbox con:

- Filesystem access controlado (Landlock)
- Syscalls limitados (seccomp)
- Red en un namespace aislado (netns)
- Políticas de egress explícitas (el agente solo puede hablar con lo que TÚ autorices)
- Inferencia routeada a través de NVIDIA Nemotron en vez de dar acceso directo a APIs de terceros

**19,600 estrellas en GitHub**, 2,400 forks, 23 releases. Activamente desarrollado por NVIDIA. Estado: Alpha — usable pero esperen cambios rompientes.

## Características principales

- **Sandbox en varias capas**: Landlock (filesystem) + seccomp (syscalls) + network namespace — los tres juntos hacen que un agente comprometido tenga superficie de ataque mínima
- **Políticas hot-reloadable**: cambias las reglas de seguridad sin reiniciar el agente. Útil cuando descubres que necesitas abrir un puerto o permiso nuevo
- **Onboarding guiado**: wizard interactivo que detecta tu OS y configura el setup correcto. Funciona en Ubuntu 22+, macOS Apple Silicon (vía Colima), Windows WSL, y NVIDIA DGX Spark
- **NVIDIA Nemotron routing**: en lugar de que el agente hable directo con OpenAI o Anthropic, pasa por un gateway de NVIDIA que maneja las llaves, rate limits y auditoría central
- **Operator approval flows**: para acciones sensibles (ej: borrar archivos, mandar mensajes masivos), el agente pausa y espera aprobación humana
- **TUI + CLI**: interfaz de terminal (TUI) para inspección visual, y CLI para scripting — usables en SSH sin desktop environment
- **Blueprints de deployment**: templates listos para Kubernetes, Docker Swarm, y máquinas individuales

## ¿Para quién es?

**Si eres desarrollador construyendo un SaaS con agentes**: esto es esencial. Si tu cliente le pide algo al agente y el agente hace algo dañino, NemoClaw te da las barreras técnicas y el audit log para defenderte legalmente.

**Si corres OpenClaw en un servidor compartido o VPS**: cualquier exposición accidental (un endpoint mal configurado, un prompt injection) se contiene dentro del sandbox. Sin NemoClaw, un agente comprometido tiene acceso a tu sistema operativo completo.

**Para equipos DevOps/SRE adoptando agentes de IA**: los audit logs, políticas declarativas y blueprints de deployment bajan masivamente la barrera para aprobar agentes en prod sin pelear con security teams.

**Si experimentas con OpenClaw para uso personal**: NemoClaw es sobre-ingeniería. Usa OpenClaw directo.

## Precio

**Gratis** — Apache 2.0.

Requisitos y costos reales:

- **API key gratuita de NVIDIA**: necesitas cuenta en [build.nvidia.com](https://build.nvidia.com) para acceder a Nemotron. Tienen tier gratuito con límites razonables
- **Hardware mínimo**: 4 vCPU + 8GB RAM + 20GB disco. Un VPS de $20 USD/mes aguanta uso moderado
- **Hardware recomendado**: 4+ vCPU + 16GB RAM + 40GB disco. VPS de $40-60 USD/mes
- **Inferencia extra**: si superas el tier gratuito de Nemotron, pagas por uso. Alternativa: puedes rutear a OpenAI/Anthropic y pagarles directo, perdiendo el beneficio de consolidación

**Costo total típico**: $20-60 USD/mes de hosting + posible consumo variable de LLM (~$20-100 USD/mes según tráfico).

## Mi veredicto

**Lo bueno:**
- Cierra el gap de seguridad más grande de OpenClaw — sin esta capa, OpenClaw no es responsable correrlo frente a clientes
- Landlock + seccomp + netns es sandboxing real, no cosmético. NVIDIA sabe hacer esto bien
- Apache 2.0, sin vendor lock-in. Si NVIDIA cambiara la dirección del proyecto mañana, podrías forkearlo
- Activamente mantenido por NVIDIA = estabilidad de largo plazo probable
- Los blueprints de deployment (Kubernetes, Docker Swarm) son un atajo enorme para adopción enterprise

**Lo no tan bueno:**
- Estado Alpha. No lo uses para algo que pierdas dinero si se rompe sin aviso
- Agrega complejidad. Si OpenClaw ya era "setup técnico", NemoClaw es "setup técnico al cuadrado". La barrera de entrada crece
- El routing por NVIDIA Nemotron es bueno para simplicidad pero te da una dependencia más (además del modelo LLM, ahora dependes del gateway NVIDIA)
- Documentación aún no tan madura como la que tienen proyectos con más años
- Solo funciona en Linux "de verdad" (Ubuntu 22+). macOS y Windows WSL son soportados pero con limitaciones

**¿Vale la pena?** Depende de tu caso de uso:
- **Para uso personal en tu máquina**: no. Es sobre-ingeniería.
- **Para uso frente a clientes o en VPS compartido**: sí, absolutamente.
- **Para una startup construyendo producto con agentes**: sí, de Day 1. Es más barato poner los raíles de seguridad ahora que retrofit cuando ya tengas 1000 clientes.

## 💡 Tip práctico

Antes de instalar NemoClaw, asegúrate de tener OpenClaw funcionando sin él durante al menos 2 semanas. Razón: necesitas entender qué permisos realmente necesita TU caso de uso antes de escribir políticas restrictivas.

Si pones NemoClaw encima de OpenClaw el día 1, vas a encontrarte peleando con políticas que bloquean cosas que no sabías que necesitabas. Si primero usas OpenClaw sin restricciones y anotas QUÉ hizo (archivos que tocó, dominios que llamó, canales que usó), después escribir una política NemoClaw que permita exactamente eso y nada más es trivial.

Instalación una vez que estés listo:

```bash
curl -fsSL https://www.nvidia.com/nemoclaw.sh | bash
```

El instalador te guía. Empieza con política "permit all" (todo permitido, solo audit log) por una semana más. Revisa los logs, escribe una política "permit only observed", y ahí sí tienes un setup de producción real.
