<script setup lang="ts">
// NeonEffects.vue - Core animations and effects for the application
</script>

<template>
  <!-- This component provides styling classes and doesn't render anything -->
</template>

<style>
/* Sliding animations */
@keyframes neon-slide-right {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(100%); }
}

@keyframes neon-slide-left {
  0% { transform: translateX(100%); }
  100% { transform: translateX(-100%); }
}

@keyframes neon-slide-up {
  0% { transform: translateY(100%); }
  100% { transform: translateY(-100%); }
}

@keyframes neon-slide-down {
  0% { transform: translateY(-100%); }
  100% { transform: translateY(100%); }
}

/* Glow and pulse effects */
@keyframes glow {
  0%, 100% { opacity: 0.3; }
  50% { opacity: 0.8; }
}

@keyframes pulse-slow {
  0%, 100% { opacity: 0.5; }
  50% { opacity: 1; }
}

/* Animation classes */
.animate-neon-slide-right {
  animation: neon-slide-right 3s linear infinite;
}

.animate-neon-slide-left {
  animation: neon-slide-left 3s linear infinite;
}

.animate-neon-slide-up {
  animation: neon-slide-up 3s linear infinite;
}

.animate-neon-slide-down {
  animation: neon-slide-down 3s linear infinite;
}

.animate-neon-slide-right-slow {
  animation: neon-slide-right 6s linear infinite;
}

.animate-neon-slide-left-slow {
  animation: neon-slide-left 6s linear infinite;
}

.animate-neon-slide-up-slow {
  animation: neon-slide-up 6s linear infinite;
}

.animate-neon-slide-down-slow {
  animation: neon-slide-down 6s linear infinite;
}

.animate-glow {
  animation: glow 3s infinite;
}

.animate-pulse-slow {
  animation: pulse-slow 4s infinite;
}

/* Glass effect */
.glass-effect {
  background-color: rgba(17, 24, 39, 0.7);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Text glow effects */
.text-glow-light {
  text-shadow: 0 0 10px rgba(124, 58, 237, 0.5);
}

.text-glow-medium {
  text-shadow: 0 0 15px rgba(124, 58, 237, 0.6);
}

.text-glow-strong {
  text-shadow: 0 0 20px rgba(124, 58, 237, 0.7);
}

/* Neon border utility */
.neon-border {
  position: relative;
  overflow: hidden;
}

.neon-border::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  border: 1px solid rgba(124, 58, 237, 0.5);
  border-radius: inherit;
  box-shadow: 0 0 10px rgba(124, 58, 237, 0.3);
  pointer-events: none;
}

/* Cyberpunk grid background */
.cyberpunk-grid-bg {
  background-image:
    linear-gradient(rgba(124, 58, 237, 0.1) 1px, transparent 1px),
    linear-gradient(90deg, rgba(124, 58, 237, 0.1) 1px, transparent 1px);
  background-size: 20px 20px;
}
</style>