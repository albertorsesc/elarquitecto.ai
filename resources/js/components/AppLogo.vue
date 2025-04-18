<script setup lang="ts">
</script>

<template>
    <div class="flex size-20 items-center justify-center rounded-full text-sidebar-primary-foreground">
        <!-- <AppLogoIcon class="size-6 fill-current text-black" /> -->
        <img src="/img/logo.webp" alt="El Arquitecto AI Logo" class="w-12 h-12 object-contain rounded-full" />
    </div>
    <div class="lg:ml-1 grid flex md:flex-1 md:text-left text-base">
        <span class="mb-0.5 truncate font-semibold leading-none">
            A.I. <span class="gradient-text">Hub</span>
        </span>
    </div>
</template>

<style scoped>
.gradient-text {
  background: linear-gradient(to right, hsl(var(--primary)), hsl(var(--secondary)));
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
  text-fill-color: transparent;
  text-shadow: 0 0 3px rgba(var(--primary-rgb), 0.3);
}
</style>
