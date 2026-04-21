---
title: "Project NOMAD"
slug: "project-nomad"
excerpt: "El conocimiento que nunca se queda sin internet — servidor offline con IA, Wikipedia, Khan Academy, mapas y más en una caja"
business_model: "open_source"

website_url: "https://github.com/Crosstalk-Solutions/project-nomad"
pricing_url: ""
documentation_url: "https://www.projectnomad.us"

# Monetización — Apache 2.0, self-hosted, sin afiliados ni suscripciones.

research:
  score: 9
  validated_at: "2026-04-20"
  sources:
    - "https://github.com/Crosstalk-Solutions/project-nomad"
    - "https://www.projectnomad.us"
  alternatives_considered:
    - "IPFS + manual curation (más técnico, sin UI)"
    - "Kiwix standalone (solo contenido, sin IA)"
    - "LocalAI solo (solo IA, sin conocimiento)"
  why_include: "Encaje excepcional para LATAM — apagones en Venezuela/Argentina/Ecuador/Cuba, internet intermitente en zonas rurales, censura/vigilancia en algunos países, acceso educativo limitado. NOMAD resuelve los cuatro problemas con un solo box. 24.6K estrellas, v1.31.0 estable, comunidad activa en Discord. Apache 2.0 = sin riesgo de vendor lock-in."

meta_title: "Project NOMAD — Servidor de conocimiento offline para LATAM"
meta_description: "Apache 2.0. Docker Compose que empaqueta IA local, Wikipedia, Khan Academy, mapas y herramientas de datos. Funciona sin internet, sin cloud, sin depender de nadie."
meta_keywords: ["offline AI", "offline Wikipedia", "servidor local", "sobrevivencia digital", "Ollama"]

categories: ["ai", "productividad"]
tags: ["codigo-abierto", "llm", "aprendizaje"]

is_featured: true
featured_image: "logo.webp"

published_at: "2026-04-20 15:00:00"
timezone: "America/Tijuana"
---

## ¿Qué es Project NOMAD?

Project NOMAD (Node for Offline Media, Archives, and Data) es un **servidor de conocimiento que funciona completamente sin internet**. Instalas todo el paquete en una Raspberry Pi, un mini PC o un servidor viejo, y tienes:

- **Todo Wikipedia en español e inglés** (lectura completa)
- **Khan Academy** con cursos de matemáticas, ciencias y programación
- **Chat con IA local** (vía Ollama — GPT-level en tu máquina, sin enviar datos a nadie)
- **Miles de ebooks** (PG-rated, colecciones educativas curadas)
- **Mapas regionales descargados** (navegación sin GPS ni conexión)
- **Herramientas de datos** (CyberChef para encriptar, decodificar, analizar)
- **Notas locales** y una app de estudio con trackeo de progreso

Todo esto accesible desde cualquier navegador en tu WiFi local. Sin cuenta, sin cloud, sin rastreo.

Es **open source (Apache 2.0)**, lo mantiene Crosstalk Solutions, tiene **24,600 estrellas en GitHub** y versión estable v1.31.0 (abril 2026). 60 releases en menos de 2 años — desarrollo muy activo.

## Características principales

- **Setup wizard con colecciones curadas**: eliges qué tipo de uso quieres (educación familiar, emergencias, privacidad, investigación) y el instalador baja el paquete correcto
- **IA local con RAG**: puedes subir tus propios documentos (manuales, papers, notas) y la IA responde preguntas usándolos — sin enviar nada a la nube
- **Base de datos vectorial (Qdrant)**: búsqueda semántica sobre todo tu contenido offline
- **Kiwix integrado**: Wikipedia, StackOverflow, Project Gutenberg, referencias médicas, todo descargable como archivos ZIM
- **Kolibri para educación**: frontend pulido para Khan Academy y cursos que funcionan sin internet, incluso para niños
- **ProtoMaps**: mapas vectoriales de países enteros que puedes navegar offline
- **Benchmark público**: puedes medir tu hardware contra una tabla global, útil para saber si tu Raspberry Pi aguanta IA local
- **Docker Compose transparente**: cada servicio corre en su propio contenedor, fácil de entender y modificar si sabes Linux

## ¿Para quién es?

**Si vives en un país con apagones frecuentes** (Venezuela, Cuba, Argentina en picos críticos, Ecuador recientemente): NOMAD es un seguro. Cuando se va la luz o el internet, sigues teniendo acceso a toda Wikipedia, Khan Academy para los niños, y asistente de IA. Un UPS + Raspberry Pi + NOMAD = biblioteca funcionando durante horas.

**Si estás en zonas rurales de LATAM** (pueblos, fincas, comunidades indígenas): una sola instalación en el pueblo da acceso a educación e información a toda la comunidad. La inversión es de $150-300 USD total.

**Si valoras privacidad o sovereignty de datos**: tu conversación con la IA, tus notas, tus búsquedas — todo queda en tu red local. Útil para periodistas, activistas, médicos con datos sensibles.

**Para preparación familiar (prepper light)**: los prepper estadounidenses ya lo usan para emergencias. En LATAM tiene más aplicaciones — terremotos, huracanes, inestabilidad política.

**Para educadores**: una caja con Khan Academy + Wikipedia + IA que ayuda a resolver dudas, instalada en una escuela, sin necesidad de internet confiable.

**NO es para ti si**: solo quieres un asistente de IA en tu WhatsApp (usa [OpenClaw](https://elarquitecto.ai/herramientas/openclaw)) o si nunca has tocado Linux — el instalador es guiado pero requiere nivel básico de comodidad con terminal.

## Precio

**Gratis** — Apache 2.0, sin suscripción ni licencia.

Costo real de tener un NOMAD funcionando:

- **Hardware mínimo**: Raspberry Pi 5 (8GB) + disco SSD de 2TB = $150-200 USD. Corre bien para 1-3 usuarios simultáneos con IA en modelos chicos (Llama 3.3 8B).
- **Hardware recomendado**: Mini PC con 32GB RAM + SSD 4TB = $400-600 USD. Aguanta IA con modelos de 70B parameters y 10+ usuarios concurrentes.
- **Energía**: ~10-20 watts en idle. Con panel solar y batería chica puede correr 24/7 sin conexión a la red.
- **Contenido**: los packages son gratuitos pero pesados. Wikipedia completa en español son ~90GB, Khan Academy son ~150GB. Necesitas un SSD grande.

**Total una sola vez**: $150-600 USD. Sin costos mensuales. La alternativa cloud con la misma funcionalidad sería $50-100 USD/mes, cada mes, para siempre.

## Mi veredicto

**Lo bueno:**
- Resuelve problemas REALES de LATAM (apagones, conectividad, acceso educativo) mejor que cualquier herramienta cloud
- Una vez instalado, no te cobra nada ni depende de que nadie te siga dando servicio
- La curaduría de contenido es excelente — no es solo "aquí están 100 herramientas", es un paquete pensado
- Apache 2.0 significa que puedes modificar, redistribuir, y hasta usar comercialmente sin problema legal
- Hardware viejo funciona — un Raspberry Pi de $80 USD o una laptop descartada corre el sistema completo
- Desarrollo activo con 60 releases — no es un proyecto abandonado

**Lo no tan bueno:**
- La instalación requiere Linux y terminal. Si nunca has visto una terminal, la curva es real. Plan para 1-2 fines de semana de setup inicial
- El almacenamiento necesario es sustancial (Wikipedia + Kolibri + modelos de IA son cientos de gigas) — necesitas SSDs grandes
- IA local en hardware modesto (Raspberry Pi) es más lenta que ChatGPT. Modelos pequeños (3-8B parameters) son usables, modelos grandes requieren mini PC o servidor
- Algunos componentes específicos (mapas ProtoMaps, ciertos idiomas de Kolibri) tienen menos contenido en español que en inglés. Mejora año con año
- El hardware tiene un costo inicial. Aunque se amortiza, son $200+ USD de entrada

**¿Vale la pena?** Para LATAM específicamente — sí, más que cualquier tool que hayamos cubierto. La combinación de educación + IA + información + resiliencia en un solo box open source es única. Si tienes familia con niños estudiando, vives en zona con internet poco confiable, o simplemente quieres un "backup civilizatorio" en tu casa, NOMAD es la respuesta más madura que existe hoy.

## 💡 Tip práctico

Empieza **chico** y con un caso de uso **concreto**. No instales el paquete completo de 500GB el primer día — vas a sentirte abrumado.

Recomendación: compra un Raspberry Pi 5 (8GB) + un SSD de 500GB (~$150 USD total). Instala NOMAD y empieza con SOLO Kiwix + Wikipedia en español + Ollama con Llama 3.2 (3B parameters, ~2GB).

Úsalo una semana para probar preguntas reales: "explícame la fotosíntesis para un niño de 8 años", "qué dice Wikipedia sobre [tema]", "ayúdame a repasar inglés". Si le encuentras valor genuino a esa semana, invierte en SSD más grande y añade Khan Academy + mapas. Si no lo usas, el gasto total fue de $150 USD y aprendiste sobre Docker/Linux en el camino.

La mayoría de la gente falla porque intenta instalar todo de una vez, no lo usa, y siente que desperdició el dinero. Empieza chico, valida el valor, escala.
