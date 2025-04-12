<template>
  <div class="pointer-events-none absolute -inset-1 z-0">
    <!-- Top edge -->
    <div class="absolute left-0 top-0 h-[3px] w-full animate-neon-slide-right-slow bg-gradient-to-r from-transparent via-primary to-transparent opacity-80"></div>
    <!-- Right edge -->
    <div class="absolute right-0 top-0 h-full w-[3px] animate-neon-slide-down-slow bg-gradient-to-b from-transparent via-secondary to-transparent opacity-80"></div>
    <!-- Bottom edge -->
    <div class="absolute bottom-0 left-0 h-[3px] w-full animate-neon-slide-left-slow bg-gradient-to-r from-transparent via-accent to-transparent opacity-80"></div>
    <!-- Left edge -->
    <div class="absolute left-0 top-0 h-full w-[3px] animate-neon-slide-up-slow bg-gradient-to-b from-transparent via-cyan-400 to-transparent opacity-80"></div>
  </div>
</template>

<style scoped>
/* Ensure animations are visible with proper z-index */
.absolute {
  z-index: 1;
}
</style> 