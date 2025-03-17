<script setup lang="ts">
defineProps<{
  title: string;
  color: 'primary' | 'secondary' | 'accent' | 'cyan';
  src: string;
}>();
</script>

<template>
  <div class="glass-effect rounded-lg border border-white/10 bg-background/70 p-4 backdrop-blur-xl">
    <div class="mb-4 text-center">
      <h3 class="text-xl font-bold" :class="`text-${color}`">{{ title }}</h3>
      <div
        class="mt-1 h-0.5 w-16 mx-auto"
        :class="[
          color === 'primary' ? 'bg-gradient-to-r from-primary via-cyan-400 to-secondary' :
          color === 'secondary' ? 'bg-gradient-to-r from-secondary via-accent to-cyan-400' :
          'bg-gradient-to-r from-accent via-primary to-cyan-400'
        ]"
      ></div>
    </div>
    <iframe
      :src="src"
      class="w-full h-[400px] rounded-md"
      frameborder="no"
      allowfullscreen="true"
      webkitallowfullscreen="true"
      mozallowfullscreen="true"
      scrolling="no"
    ></iframe>
  </div>
</template>