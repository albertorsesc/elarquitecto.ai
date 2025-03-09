<script setup lang="ts">
import { getDefaultPlaylist, playPlaylist } from '@/services/spotify';
import { useSpotifyStore } from '@/stores/useSpotifyStore';
import { Icon } from '@iconify/vue';
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';

// Define the type for the store
type SpotifyStoreType = ReturnType<typeof useSpotifyStore>;

// Wrap store access in try-catch to handle potential Pinia initialization issues
let spotifyStore: SpotifyStoreType;
try {
  spotifyStore = useSpotifyStore();
} catch (error) {
  console.error('Failed to initialize Spotify store:', error);
  throw new Error('Spotify store initialization failed');
}

// Add new refs for fallback player
const isLoading = ref(true);
const isSpotifyAvailable = ref(true);
const progress = ref(0);
const progressInterval = ref<number | null>(null);
const isUserAuthenticated = ref(false);
const showFallbackPlayer = ref(false);
const fallbackIsPlaying = ref(false);
const fallbackAudio = ref<HTMLAudioElement | null>(null);

// Computed properties
const isAuthenticated = computed(() => spotifyStore.isAuthenticated);
const isReady = computed(() => spotifyStore.isReady);
const isPlaying = computed(() => spotifyStore.isPlaying);
const currentTrack = computed(() => spotifyStore.currentTrack);
const volume = computed({
  get: () => spotifyStore.volume,
  set: (value: number) => spotifyStore.setVolume(value)
});
const isExpanded = computed(() => spotifyStore.isExpanded);
const isMinimized = computed(() => spotifyStore.isMinimized);
const errorMessage = computed(() => spotifyStore.error);
const hasError = computed(() => !!spotifyStore.error);

// Initialize Spotify player
onMounted(async () => {
  try {
    isLoading.value = true;

    // Check if we're returning from Spotify auth
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('spotify_auth') && urlParams.get('spotify_auth') === 'success') {
      console.log('Detected successful Spotify authentication redirect');
      // Clear the URL parameters without reloading the page
      window.history.replaceState({}, document.title, window.location.pathname);
    }

    // Fetch token from backend
    const response = await fetch('/spotify/token');
    const data = await response.json();

    // Debug token response
    console.log('Token response:', {
      status: response.status,
      isError: !!data.error,
      requiresAuth: data.requires_authentication,
      hasToken: !!data.access_token,
      hasRefreshToken: !!data.refresh_token,
      isDefault: data.is_default || false
    });

    if (response.status === 401 || data.error) {
      console.log('No valid Spotify session:', data.error);
      isUserAuthenticated.value = false;
      showFallbackPlayer.value = true;
      // Initialize fallback audio player
      initializeFallbackPlayer();
      isLoading.value = false;
      return;
    }

    // Set token in store
    spotifyStore.setAccessToken(data.access_token);

    // If we have a refresh token, this is definitely a user token
    isUserAuthenticated.value = !!data.refresh_token;
    console.log('Is user authenticated with Spotify:', isUserAuthenticated.value);

    // Initialize player only if we have a user token
    if (isUserAuthenticated.value) {
      try {
        await spotifyStore.initializePlayer();
        console.log('Spotify player initialized successfully');
        // Start progress tracking
        startProgressTracking();
      } catch (playerError) {
        console.error('Error initializing Spotify player:', playerError);
        spotifyStore.setError('Error initializing Spotify player. Please try logging in again.');
      }
    } else {
      // For non-authenticated users, we'll show the login button
      console.log('No valid user token - playback not available');
      showFallbackPlayer.value = true;
    }

    isLoading.value = false;
  } catch (error) {
    console.error('Error initializing Spotify:', error);
    isSpotifyAvailable.value = false;
    showFallbackPlayer.value = true;
    // Initialize fallback audio player
    initializeFallbackPlayer();
    isLoading.value = false;
  }
});

// Add fallback player functions
async function initializeFallbackPlayer() {
  try {
    // Get the default playlist audio URL
    const response = await fetch('/spotify/default-playlist');
    const data = await response.json();

    if (data.preview_url) {
      fallbackAudio.value = new Audio(data.preview_url);

      // Add event listeners
      fallbackAudio.value.addEventListener('ended', () => {
        fallbackIsPlaying.value = false;
      });

      fallbackAudio.value.addEventListener('error', (e) => {
        console.error('Error playing audio:', e);
        fallbackIsPlaying.value = false;
      });
    }
  } catch (error) {
    console.error('Error initializing fallback player:', error);
  }
}

function toggleFallbackPlay() {
  if (!fallbackAudio.value) return;

  if (fallbackIsPlaying.value) {
    fallbackAudio.value.pause();
    fallbackIsPlaying.value = false;
  } else {
    fallbackAudio.value.play();
    fallbackIsPlaying.value = true;
  }
}

// Clean up on component unmount
onUnmounted(() => {
  stopProgressTracking();
  spotifyStore.disconnect();

  // Clean up fallback player
  if (fallbackAudio.value) {
    fallbackAudio.value.pause();
    fallbackAudio.value = null;
  }
});

// Watch for player state changes
watch(isPlaying, (newValue) => {
  if (newValue) {
    startProgressTracking();
  } else {
    stopProgressTracking();
  }
});

// Watch for device ID changes to play default playlist
watch(() => spotifyStore.deviceId, async (newDeviceId) => {
  if (newDeviceId && spotifyStore.accessToken && !isUserAuthenticated.value) {
    try {
      console.log('Device ID ready:', newDeviceId);
      const playlistUri = await getDefaultPlaylist();
      console.log('Attempting to play playlist:', playlistUri);
      await playPlaylist(spotifyStore.accessToken, newDeviceId, playlistUri);
      console.log('Playlist playback initiated');
    } catch (error) {
      console.error('Error playing default playlist:', error);
    }
  }
});

// Track playback progress
function startProgressTracking() {
  if (progressInterval.value) return;

  progressInterval.value = window.setInterval(() => {
    if (spotifyStore.player && isPlaying.value) {
      spotifyStore.player.getCurrentState().then((state: any) => {
        if (state) {
          progress.value = (state.position / state.duration) * 100;
        }
      });
    }
  }, 1000);
}

function stopProgressTracking() {
  if (progressInterval.value) {
    window.clearInterval(progressInterval.value);
    progressInterval.value = null;
  }
}

// Player controls
function togglePlay() {
  spotifyStore.togglePlay();
}

function previousTrack() {
  spotifyStore.previousTrack();
}

function nextTrack() {
  spotifyStore.nextTrack();
}

function setProgress(event: MouseEvent) {
  if (!spotifyStore.player || !currentTrack.value) return;

  const progressBar = event.currentTarget as HTMLDivElement;
  const clickPosition = event.offsetX / progressBar.offsetWidth;

  spotifyStore.player.getCurrentState().then((state: any) => {
    if (state) {
      const position = Math.floor(clickPosition * state.duration);
      spotifyStore.seekTo(position);
    }
  });
}

function setVolume(event: Event) {
  const input = event.target as HTMLInputElement;
  volume.value = parseFloat(input.value);
}

function toggleExpanded() {
  spotifyStore.toggleExpanded();
}

function toggleMinimized() {
  spotifyStore.toggleMinimized();
}

const logoutFromSpotify = async () => {
  try {
    // First, clear the local state
    spotifyStore.setAccessToken(null);
    isUserAuthenticated.value = false;

    // Disconnect the player
    spotifyStore.disconnect();

    // Use a GET request instead of POST since that's how the route is defined
    window.location.href = '/spotify/logout';
  } catch (error) {
    console.error('Error during Spotify logout:', error);
    // Still redirect to home page even if there's an error
    window.location.href = '/';
  }
};
</script>

<template>
  <div>
    <!-- Loading State -->
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

    <!-- Error State -->
    <div v-else-if="!isSpotifyAvailable || hasError" class="fixed bottom-4 right-4 z-40">
      <div class="glass-effect overflow-hidden rounded-lg border border-white/10 bg-background/80 p-4 shadow-lg backdrop-blur-sm">
        <div class="flex items-center">
          <Icon icon="carbon:warning" class="h-6 w-6 text-yellow-400" />
          <span class="ml-2 text-sm text-foreground/70">
            {{ hasError ? errorMessage : 'Spotify no está disponible' }}
          </span>
        </div>
        <div v-if="hasError" class="mt-2 text-xs text-foreground/50">
          <p>Posibles soluciones:</p>
          <ul class="ml-4 list-disc">
            <li>Asegúrate de tener una cuenta Premium de Spotify</li>
            <li>Intenta <a href="/spotify/login" class="text-primary hover:underline">iniciar sesión nuevamente</a></li>
            <li>Verifica que no tengas Spotify abierto en otro dispositivo</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Login Required State -->
    <div v-else-if="!isUserAuthenticated && !showFallbackPlayer" class="fixed bottom-4 right-4 z-40">
      <div class="glass-effect overflow-hidden rounded-lg border border-white/10 bg-background/80 p-4 shadow-lg backdrop-blur-sm">
        <div class="flex flex-col items-center gap-3">
          <div class="flex items-center gap-2">
            <Icon icon="carbon:warning" class="h-5 w-5 text-yellow-400" />
            <span class="text-sm font-medium text-foreground/90">Spotify Premium Requerido</span>
          </div>
          <p class="text-center text-xs text-foreground/70">Para reproducir música, necesitas iniciar sesión con tu cuenta Premium de Spotify</p>
          <a
            href="/spotify/login"
            class="flex w-full items-center justify-center gap-2 rounded-md bg-[#1DB954] px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-[#1ed760] hover:shadow-lg"
          >
            <Icon icon="mdi:spotify" class="h-5 w-5" />
            Conectar con Spotify Premium
          </a>
        </div>
      </div>
    </div>

    <!-- Fallback Player -->
    <div v-if="showFallbackPlayer" class="fixed bottom-4 right-4 z-40">
      <div class="glass-effect overflow-hidden rounded-lg border border-white/10 bg-background/80 p-4 shadow-lg backdrop-blur-sm">
        <div class="flex flex-col items-center gap-3">
          <div class="flex items-center gap-2">
            <Icon icon="carbon:music" class="h-5 w-5 text-primary" />
            <span class="text-sm font-medium text-foreground/90">Reproductor de Música</span>
          </div>
          <div class="flex items-center gap-4">
            <button class="rounded-full p-2 text-foreground/30">
              <Icon icon="carbon:previous-filled" class="h-5 w-5" />
            </button>
            <button
              @click="toggleFallbackPlay"
              class="flex h-10 w-10 items-center justify-center rounded-full bg-primary text-white transition-all hover:bg-primary/80 hover:shadow-[0_0_20px_rgba(124,58,237,0.5)] button-hover"
            >
              <Icon :icon="fallbackIsPlaying ? 'carbon:pause-filled' : 'carbon:play-filled'" class="h-5 w-5" />
            </button>
            <button class="rounded-full p-2 text-foreground/30">
              <Icon icon="carbon:next-filled" class="h-5 w-5" />
            </button>
          </div>
          <div class="mt-2 text-center">
            <a href="/spotify/login" class="text-xs text-primary hover:underline">
              Conectar con Spotify Premium para control total
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Minimized Player Button (Only show if authenticated) -->
    <button
      v-if="!showFallbackPlayer && isMinimized && isReady && currentTrack"
      @click="toggleMinimized"
      class="fixed bottom-4 right-4 z-50 flex h-12 w-12 items-center justify-center rounded-full bg-primary text-white shadow-lg transition-all hover:scale-105 hover:bg-primary/90 hover:shadow-[0_0_20px_rgba(124,58,237,0.5)] pulse"
    >
      <Icon icon="carbon:music" class="h-6 w-6" />
      <!-- Playing indicator -->
      <div
        v-if="isPlaying"
        class="absolute -right-1 -top-1 h-3 w-3 rounded-full bg-green-400 shadow-[0_0_10px_rgba(74,222,128,0.5)]"
      ></div>
    </button>

    <!-- Full Player (Only show if authenticated) -->
    <div
      v-if="!showFallbackPlayer && !isMinimized"
      :class="[
        'fixed z-50 transition-all duration-500 ease-in-out slide-fade-enter-active',
        isExpanded ? 'bottom-0 left-0 right-0' : 'bottom-4 right-4 w-80'
      ]"
    >
      <!-- Player Container -->
      <div
        class="glass-effect relative overflow-hidden border-t border-white/10 bg-background/80 shadow-lg backdrop-blur-sm"
        :class="{ 'rounded-lg': !isExpanded, 'border-l border-r': !isExpanded }"
      >
        <!-- Minimize Button -->
        <button
          @click="toggleMinimized"
          class="absolute right-2 top-2 z-10 rounded-full p-1.5 text-foreground/50 transition-colors hover:bg-white/10 hover:text-foreground button-hover"
        >
          <Icon icon="carbon:minimize" class="h-4 w-4" />
        </button>

        <!-- Expand/Collapse Button -->
        <button
          @click="toggleExpanded"
          class="absolute right-9 top-2 z-10 rounded-full p-1.5 text-foreground/50 transition-colors hover:bg-white/10 hover:text-foreground button-hover"
        >
          <Icon :icon="isExpanded ? 'carbon:minimize' : 'carbon:maximize'" class="h-4 w-4" />
        </button>

        <div class="p-4">
          <!-- Track Info -->
          <div :class="['mb-4 flex items-center gap-4', isExpanded ? 'justify-center' : '']">
            <img
              v-if="currentTrack?.album?.images?.[0]?.url"
              :src="currentTrack.album.images[0].url"
              :alt="currentTrack.name"
              :class="[
                'rounded object-cover shadow-lg album-rotate',
                isExpanded ? 'h-24 w-24' : 'h-12 w-12',
                isPlaying ? 'playing' : ''
              ]"
            />
            <div class="flex-1 overflow-hidden">
              <h3 class="mb-1 truncate font-medium neon-text" :class="[isExpanded ? 'text-lg' : 'text-sm']">
                {{ currentTrack?.name }}
              </h3>
              <p class="truncate text-sm text-foreground/70">
                {{ currentTrack?.artists?.map((a: any) => a.name).join(', ') }}
              </p>
            </div>
          </div>

          <!-- Progress Bar -->
          <div
            class="group mb-4 h-1 cursor-pointer rounded-full bg-white/10"
            @click="setProgress"
          >
            <div
              :class="[
                'relative h-full rounded-full bg-gradient-to-r from-primary via-cyan-400 to-secondary transition-all',
                isPlaying ? 'progress-active' : ''
              ]"
              :style="{ width: `${progress}%` }"
            >
              <div class="absolute right-0 top-1/2 h-3 w-3 -translate-y-1/2 scale-0 rounded-full border-2 border-white bg-primary transition-transform group-hover:scale-100"></div>
            </div>
          </div>

          <!-- Controls -->
          <div :class="['flex items-center', isExpanded ? 'justify-center gap-6' : 'justify-between gap-4']">
            <button
              @click="previousTrack"
              class="rounded-full p-2 text-foreground/70 transition-colors hover:text-primary button-hover"
            >
              <Icon icon="carbon:previous-filled" :class="[isExpanded ? 'h-6 w-6' : 'h-5 w-5']" />
            </button>

            <button
              @click="togglePlay"
              :class="[
                'flex items-center justify-center rounded-full bg-primary text-white transition-all hover:bg-primary/80 hover:shadow-[0_0_20px_rgba(124,58,237,0.5)] button-hover',
                isExpanded ? 'h-12 w-12' : 'h-10 w-10'
              ]"
            >
              <Icon :icon="isPlaying ? 'carbon:pause-filled' : 'carbon:play-filled'" :class="[isExpanded ? 'h-6 w-6' : 'h-5 w-5']" />
            </button>

            <button
              @click="nextTrack"
              class="rounded-full p-2 text-foreground/70 transition-colors hover:text-primary button-hover"
            >
              <Icon icon="carbon:next-filled" :class="[isExpanded ? 'h-6 w-6' : 'h-5 w-5']" />
            </button>
          </div>

          <!-- Volume Control (Only in expanded view) -->
          <div v-if="isExpanded" class="mt-4 flex items-center justify-center gap-2">
            <Icon icon="carbon:volume-down" class="h-4 w-4 text-foreground/70" />
            <input
              type="range"
              min="0"
              max="1"
              step="0.1"
              :value="volume"
              @input="setVolume"
              class="h-1 w-48 cursor-pointer appearance-none rounded-lg bg-white/10"
            />
            <Icon icon="carbon:volume-up" class="h-4 w-4 text-foreground/70" />
          </div>

          <!-- User Controls -->
          <div v-if="isAuthenticated && isExpanded" class="mt-4 text-center">
            <button
              @click="logoutFromSpotify"
              class="text-xs text-foreground/50 hover:text-foreground/80 button-hover"
            >
              Desconectar Spotify
            </button>
          </div>
        </div>

        <!-- Neon border effects -->
        <div class="pointer-events-none absolute inset-0" :class="{ 'rounded-lg': !isExpanded }">
          <div class="absolute -inset-[0.5px] opacity-30">
            <!-- Top edge -->
            <div class="absolute left-0 top-0 h-[1px] w-full animate-neon-slide-right-color bg-gradient-to-r from-transparent via-[#FF1CF7] to-transparent"></div>
            <!-- Right edge -->
            <div class="absolute right-0 top-0 h-full w-[1px] animate-neon-slide-down-color bg-gradient-to-b from-transparent via-[#00FFE1] to-transparent"></div>
            <!-- Bottom edge -->
            <div class="absolute bottom-0 left-0 h-[1px] w-full animate-neon-slide-left-color bg-gradient-to-r from-transparent via-[#01FF88] to-transparent"></div>
            <!-- Left edge -->
            <div class="absolute left-0 top-0 h-full w-[1px] animate-neon-slide-up-color bg-gradient-to-b from-transparent via-[#5B6EF7] to-transparent"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes glow {
  0%, 100% { opacity: 0.3; }
  50% { opacity: 0.8; }
}

.animate-glow {
  animation: glow 3s infinite;
}

/* Slide and fade animations */
.slide-fade-enter-active,
.slide-fade-leave-active {
  transition: all 0.3s ease;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateY(20px);
  opacity: 0;
}

/* Scale animation for minimized button */
@keyframes pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.05); }
  100% { transform: scale(1); }
}

.pulse {
  animation: pulse 2s infinite;
}

/* Hover animations */
.hover-scale {
  transition: transform 0.2s ease;
}

.hover-scale:hover {
  transform: scale(1.05);
}

/* Progress bar animation */
@keyframes progress-glow {
  0% { box-shadow: 0 0 5px rgba(124, 58, 237, 0.5); }
  50% { box-shadow: 0 0 15px rgba(124, 58, 237, 0.8); }
  100% { box-shadow: 0 0 5px rgba(124, 58, 237, 0.5); }
}

.progress-active {
  animation: progress-glow 2s infinite;
}

/* Customize range input for volume */
input[type="range"] {
  -webkit-appearance: none;
  appearance: none;
  background: transparent;
}

input[type="range"]::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  height: 12px;
  width: 12px;
  border-radius: 50%;
  background: rgb(124, 58, 237);
  cursor: pointer;
  margin-top: -5px;
  transition: all 0.2s;
}

input[type="range"]::-webkit-slider-thumb:hover {
  transform: scale(1.2);
  box-shadow: 0 0 10px rgb(124, 58, 237);
}

input[type="range"]::-webkit-slider-runnable-track {
  height: 2px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 2px;
}

input[type="range"]:focus {
  outline: none;
}

/* Neon text effect */
.neon-text {
  text-shadow: 0 0 10px rgba(124, 58, 237, 0.5);
}

/* Button hover effects */
.button-hover {
  transition: all 0.3s ease;
}

.button-hover:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(124, 58, 237, 0.3);
}

/* Track image rotation */
@keyframes slow-spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.album-rotate {
  animation: slow-spin 20s linear infinite;
  animation-play-state: paused;
}

.album-rotate.playing {
  animation-play-state: running;
}
</style>