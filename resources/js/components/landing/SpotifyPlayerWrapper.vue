<script setup lang="ts">
import { defineAsyncComponent, onMounted, ref } from 'vue';

// Dynamically import the SpotifyPlayer component to ensure Pinia is initialized
const SpotifyPlayer = defineAsyncComponent(() =>
  import('./SpotifyPlayer.vue')
);

const isPiniaAvailable = ref(false);
const isLoading = ref(true);

onMounted(() => {
  // Short delay to ensure Pinia is initialized
  setTimeout(() => {
    try {
      // Try to import and use Pinia to check if it's available
      import('pinia').then(() => {
        isPiniaAvailable.value = true;
        isLoading.value = false;
      }).catch(error => {
        console.error('Error importing Pinia:', error);
        isPiniaAvailable.value = false;
        isLoading.value = false;
      });
    } catch (error) {
      console.error('Error checking Pinia availability:', error);
      isPiniaAvailable.value = false;
      isLoading.value = false;
    }
  }, 100);
});
</script>

<template>
  <div>
    <!-- Loading state -->
    <div v-if="isLoading" class="fixed bottom-4 right-4 z-40">
      <div class="glass-effect overflow-hidden rounded-lg border border-white/10 bg-background/80 p-4 shadow-lg backdrop-blur-sm">
        <div class="flex items-center">
          <div class="relative h-6 w-6">
            <div class="absolute inset-0 animate-spin rounded-full border-2 border-transparent border-t-cyan-400 opacity-70"></div>
          </div>
          <span class="ml-2 text-sm text-foreground/70">Cargando reproductor...</span>
        </div>
      </div>
    </div>

    <!-- Use SpotifyPlayer if Pinia is available -->
    <Suspense v-else-if="isPiniaAvailable">
      <template #default>
        <SpotifyPlayer />
      </template>
      <template #fallback>
        <div class="fixed bottom-4 right-4 z-40">
          <div class="glass-effect overflow-hidden rounded-lg border border-white/10 bg-background/80 p-4 shadow-lg backdrop-blur-sm">
            <div class="flex items-center">
              <div class="relative h-6 w-6">
                <div class="absolute inset-0 animate-spin rounded-full border-2 border-transparent border-t-cyan-400 opacity-70"></div>
              </div>
              <span class="ml-2 text-sm text-foreground/70">Cargando Spotify...</span>
            </div>
          </div>
        </div>
      </template>
    </Suspense>

    <!-- Error state if Pinia is not available -->
    <div v-else class="fixed bottom-4 right-4 z-40">
      <div class="glass-effect overflow-hidden rounded-lg border border-white/10 bg-background/80 p-4 shadow-lg backdrop-blur-sm">
        <div class="flex items-center">
          <div class="text-red-400 mr-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <span class="text-sm text-foreground/70">Error al cargar el reproductor</span>
        </div>
      </div>
    </div>
  </div>
</template>