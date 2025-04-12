<template>
  <div class="pointer-events-none absolute -inset-1">
    <!-- Top edge -->
    <div class="absolute left-0 top-0 h-[2px] w-full animate-neon-slide-right-slow bg-gradient-to-r from-transparent via-primary to-transparent opacity-50"></div>
    <!-- Right edge -->
    <div class="absolute right-0 top-0 h-full w-[2px] animate-neon-slide-down-slow bg-gradient-to-b from-transparent via-secondary to-transparent opacity-50"></div>
    <!-- Bottom edge -->
    <div class="absolute bottom-0 left-0 h-[2px] w-full animate-neon-slide-left-slow bg-gradient-to-r from-transparent via-accent to-transparent opacity-50"></div>
    <!-- Left edge -->
    <div class="absolute left-0 top-0 h-full w-[2px] animate-neon-slide-up-slow bg-gradient-to-b from-transparent via-cyan-400 to-transparent opacity-50"></div>
  </div>
</template> 