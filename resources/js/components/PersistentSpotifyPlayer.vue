<script setup lang="ts">
import config from '@/config';
import { useSpotifyStore } from '@/stores/useSpotifyStore';
import { Icon } from '@iconify/vue';
import { computed, onMounted, onUnmounted, ref } from 'vue';

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

// State for the player
const isCollapsed = ref(localStorage.getItem('spotify_collapsed') === 'true');
const isLoading = ref(true);

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
const hasError = computed(() => !!spotifyStore.error);
const shuffleEnabled = computed(() => spotifyStore.shuffleEnabled);
const trackProgress = computed(() => spotifyStore.playbackPosition);
const trackDuration = computed(() => spotifyStore.trackDuration);
const progressPercentage = computed(() => {
  if (!trackDuration.value) return 0;
  return (trackProgress.value / trackDuration.value) * 100;
});

// Format time in mm:ss
function formatTime(ms: number): string {
  const seconds = Math.floor(ms / 1000);
  const minutes = Math.floor(seconds / 60);
  const remainingSeconds = seconds % 60;
  return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
}

// Update progress
function updateProgress(event: Event) {
  const input = event.target as HTMLInputElement;
  const position = Math.floor((parseFloat(input.value) / 100) * trackDuration.value);
  spotifyStore.updatePlaybackPosition(position);
}

// Toggle collapsed state
function toggleCollapsed() {
  isCollapsed.value = !isCollapsed.value;
  localStorage.setItem('spotify_collapsed', isCollapsed.value.toString());
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

function toggleExpanded() {
  spotifyStore.toggleExpanded();
}

function toggleShuffle() {
  spotifyStore.toggleShuffle();
}

// Error recovery function
async function retryConnection() {
  try {
    spotifyStore.setError(null);
    isLoading.value = true;

    // Cleanup and reinitialize
    spotifyStore.cleanup();
    await spotifyStore.initialize();
  } catch (error) {
    console.error('Retry attempt failed:', error);
    spotifyStore.setError('Failed to reconnect to Spotify');
    window.location.href = '/spotify/login';
  } finally {
    isLoading.value = false;
  }
}

// Initialize Spotify player
onMounted(async () => {
  try {
    isLoading.value = true;

    // Check if we're returning from Spotify auth
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('spotify_connected') && urlParams.get('spotify_connected') === 'true') {
      console.log('Detected successful Spotify authentication redirect');
      // Clear the URL parameters without reloading the page
      window.history.replaceState({}, document.title, window.location.pathname);
    }

    // Initialize the store
    await spotifyStore.initialize();
  } catch (error) {
    console.error('Error initializing Spotify:', error);
    spotifyStore.setError('Failed to initialize Spotify player');
  } finally {
    isLoading.value = false;
  }
});

// Cleanup on component unmount
onUnmounted(() => {
  spotifyStore.cleanup();
});

// Extract the base domain from the API URL for interceptor matching
const spotifyApiDomain = new URL(config.spotify.apiUrl).hostname;

// Fix for Spotify API errors and add interceptor for debugging
onMounted(() => {
  // Define the interceptor function
  const setupSpotifyApiInterceptor = () => {
    const originalFetch = window.fetch;
    window.fetch = async function(input, init) {
      // Only intercept Spotify API calls
      if (typeof input === 'string' && input.includes(spotifyApiDomain)) {
        console.debug(`Intercepting Spotify API request:`, input);

        try {
          const response = await originalFetch.apply(this, [input, init]);
          const clonedResponse = response.clone();

          // Handle specific error cases
          if (!response.ok) {
            const errorText = await clonedResponse.text();
            let errorData;

            try {
              errorData = JSON.parse(errorText);
            } catch {
              errorData = { error: errorText };
            }

            console.error(`Spotify API error (${response.status}):`, errorData);

            // Handle specific error cases
            switch (response.status) {
              case 401:
                console.log('Token expired, redirecting to login...');
                window.location.href = '/spotify/login';
                break;
              case 404:
                if (input.includes('/event/')) {
                  console.log('Intercepting 404 for event endpoint');
                  return new Response(JSON.stringify({
                    event_id: Date.now().toString(),
                    status: 'ok'
                  }), {
                    status: 200,
                    headers: {
                      'Content-Type': 'application/json'
                    }
                  });
                }
                break;
              case 429:
                const retryAfter = response.headers.get('Retry-After');
                console.warn(`Rate limited. Retry after ${retryAfter} seconds`);
                break;
            }
          }

          return response;
        } catch (error) {
          console.error('Spotify API request failed:', error);
          throw error;
        }
      }

      // Pass through non-Spotify requests
      return originalFetch.apply(this, [input, init]);
    };
  };

  // Set up the interceptor
  setupSpotifyApiInterceptor();
});
</script>

<template>
  <div
    class="fixed bottom-0 right-0 z-50 transition-all duration-300"
    :class="{
      'w-96': !isCollapsed && isExpanded,
      'w-64': !isCollapsed && !isExpanded,
      'w-12': isCollapsed
    }"
  >
    <!-- Collapsed Tab -->
    <div
      v-if="isCollapsed"
      @click="toggleCollapsed"
      class="glass-effect flex h-12 w-12 cursor-pointer items-center justify-center rounded-tl-lg border border-white/10 bg-background/80 text-primary backdrop-blur-md hover:bg-background/90"
    >
      <Icon icon="mdi:spotify" class="h-6 w-6" />
    </div>

    <!-- Expanded Player -->
    <div
      v-else
      class="glass-effect relative overflow-hidden rounded-tl-lg border border-white/10 bg-background/80 backdrop-blur-md"
    >
      <!-- Header with collapse button -->
      <div class="flex items-center justify-between border-b border-white/10 px-3 py-2">
        <div class="flex items-center">
          <Icon icon="mdi:spotify" class="mr-2 h-5 w-5 text-primary" />
          <span class="text-sm font-medium text-foreground">Spotify Player</span>
        </div>
        <div class="flex items-center space-x-2">
          <button
            @click="toggleExpanded"
            class="rounded p-1 text-foreground/70 hover:bg-white/10 hover:text-foreground"
          >
            <Icon :icon="isExpanded ? 'mdi:chevron-down' : 'mdi:chevron-up'" class="h-4 w-4" />
          </button>
          <button
            @click="toggleCollapsed"
            class="rounded p-1 text-foreground/70 hover:bg-white/10 hover:text-foreground"
          >
            <Icon icon="mdi:chevron-right" class="h-4 w-4" />
          </button>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="isLoading" class="p-3 text-center">
        <div class="flex items-center justify-center">
          <div class="relative h-6 w-6">
            <div class="absolute inset-0 animate-spin rounded-full border-2 border-transparent border-t-cyan-400 opacity-70"></div>
          </div>
          <span class="ml-2 text-sm text-foreground/70">Connecting to Spotify...</span>
        </div>
      </div>

      <!-- Player Content -->
      <div v-else-if="isExpanded" class="p-3">
        <!-- Track Info -->
        <div v-if="currentTrack" class="mb-3 flex items-center">
          <img
            :src="currentTrack.album?.images?.[0]?.url"
            alt="Album Art"
            class="mr-3 h-12 w-12 rounded-md"
          >
          <div class="overflow-hidden">
            <div class="truncate text-sm font-medium text-foreground">{{ currentTrack.name }}</div>
            <div class="truncate text-xs text-foreground/70">{{ currentTrack.artists?.[0]?.name }}</div>
          </div>
        </div>

        <!-- Progress Slider -->
        <div class="mb-3">
          <div class="flex items-center justify-between text-xs text-foreground/70 mb-1">
            <span>{{ formatTime(trackProgress) }}</span>
            <span>{{ formatTime(trackDuration) }}</span>
          </div>
          <input
            type="range"
            min="0"
            max="100"
            step="0.1"
            :value="progressPercentage"
            @input="updateProgress"
            class="progress-slider h-1 w-full appearance-none rounded-full bg-white/20"
          >
        </div>

        <!-- Controls -->
        <div class="mb-3 flex items-center justify-center space-x-4">
          <button
            @click="toggleShuffle"
            class="rounded p-1"
            :class="[
              shuffleEnabled ? 'text-primary' : 'text-foreground/70',
              'hover:bg-white/10 hover:text-foreground'
            ]"
            :disabled="!isReady"
          >
            <Icon icon="mdi:shuffle-variant" class="h-5 w-5" />
          </button>
          <button
            @click="previousTrack"
            class="rounded p-1 text-foreground/70 hover:bg-white/10 hover:text-foreground"
            :disabled="!isReady"
          >
            <Icon icon="mdi:skip-previous" class="h-6 w-6" />
          </button>
          <button
            @click="togglePlay"
            class="flex h-10 w-10 items-center justify-center rounded-full bg-primary text-white hover:bg-primary/90"
            :disabled="!isReady"
          >
            <Icon :icon="isPlaying ? 'mdi:pause' : 'mdi:play'" class="h-6 w-6" />
          </button>
          <button
            @click="nextTrack"
            class="rounded p-1 text-foreground/70 hover:bg-white/10 hover:text-foreground"
            :disabled="!isReady"
          >
            <Icon icon="mdi:skip-next" class="h-6 w-6" />
          </button>
        </div>

        <!-- Volume Slider -->
        <div class="flex items-center space-x-2">
          <Icon icon="mdi:volume-medium" class="h-4 w-4 text-foreground/70" />
          <input
            type="range"
            min="0"
            max="1"
            step="0.01"
            v-model="volume"
            class="h-1 w-full appearance-none rounded-full bg-white/20"
          >
        </div>

        <!-- Error Message -->
        <div v-if="hasError" class="mt-2 flex flex-col items-center">
          <div class="mb-1 text-xs text-red-400">
            {{ spotifyStore.error }}
          </div>
          <div class="flex space-x-2">
            <button
              @click="retryConnection"
              class="text-xs px-2 py-1 rounded bg-primary/80 text-white hover:bg-primary"
            >
              Retry Connection
            </button>
            <a
              href="/spotify/login"
              class="text-xs px-2 py-1 rounded bg-green-600/80 text-white hover:bg-green-600"
            >
              Reconnect to Spotify
            </a>
          </div>
        </div>

        <!-- Not Authenticated Message -->
        <div v-if="!isAuthenticated" class="mt-2 text-center">
          <a
            href="/spotify/login"
            class="inline-block rounded bg-primary px-3 py-1 text-xs font-medium text-white hover:bg-primary/90"
          >
            Connect to Spotify
          </a>
        </div>
      </div>

      <!-- Minimized Player -->
      <div v-else-if="!isLoading" class="flex items-center justify-between p-2">
        <div v-if="currentTrack" class="flex items-center overflow-hidden">
          <img
            :src="currentTrack.album?.images?.[0]?.url"
            alt="Album Art"
            class="mr-2 h-8 w-8 rounded-md"
          >
          <div class="overflow-hidden">
            <div class="truncate text-xs font-medium text-foreground">{{ currentTrack.name }}</div>
          </div>
        </div>
        <div class="flex items-center space-x-1">
          <button
            @click="toggleShuffle"
            class="rounded p-1"
            :class="[
              shuffleEnabled ? 'text-primary' : 'text-foreground/70',
              'hover:bg-white/10 hover:text-foreground'
            ]"
            :disabled="!isReady"
          >
            <Icon icon="mdi:shuffle-variant" class="h-4 w-4" />
          </button>
          <button
            @click="togglePlay"
            class="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-white hover:bg-primary/90"
            :disabled="!isReady"
          >
            <Icon :icon="isPlaying ? 'mdi:pause' : 'mdi:play'" class="h-4 w-4" />
          </button>
        </div>
      </div>

      <!-- Neon border effect -->
      <div class="absolute inset-x-0 bottom-0 h-[1px] bg-gradient-to-r from-transparent via-primary to-transparent opacity-50"></div>
    </div>
  </div>
</template>

<style scoped>
/* Custom styling for range input */
input[type="range"] {
  -webkit-appearance: none;
  height: 4px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 2px;
  background-image: linear-gradient(to right, rgba(124, 58, 237, 1) 0%, rgba(124, 58, 237, 1) 50%, rgba(255, 255, 255, 0.2) 50%, rgba(255, 255, 255, 0.2) 100%);
  background-size: 200% 100%;
  background-position: var(--volume-position, 0%) 0;
}

input[type="range"]::-webkit-slider-thumb {
  -webkit-appearance: none;
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: white;
  cursor: pointer;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
}

input[type="range"]::-moz-range-thumb {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: white;
  cursor: pointer;
  border: none;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
}

/* Update volume slider background position based on value */
input[type="range"] {
  --volume-position: v-bind('volume * 100 + "%"');
}

/* Progress slider styling */
.progress-slider {
  -webkit-appearance: none;
  height: 4px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 2px;
  background-image: linear-gradient(to right, rgba(124, 58, 237, 1) 0%, rgba(124, 58, 237, 1) 50%, rgba(255, 255, 255, 0.2) 50%, rgba(255, 255, 255, 0.2) 100%);
  background-size: 200% 100%;
  background-position: var(--progress-position, 0%) 0;
}

.progress-slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: white;
  cursor: pointer;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
  transition: transform 0.1s;
}

.progress-slider::-webkit-slider-thumb:hover {
  transform: scale(1.2);
}

.progress-slider::-moz-range-thumb {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: white;
  cursor: pointer;
  border: none;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
  transition: transform 0.1s;
}

.progress-slider::-moz-range-thumb:hover {
  transform: scale(1.2);
}

/* Update progress slider position based on value */
.progress-slider {
  --progress-position: v-bind('progressPercentage + "%"');
}
</style>