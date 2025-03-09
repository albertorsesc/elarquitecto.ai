<script setup lang="ts">
import { Icon } from '@iconify/vue';
import { onMounted, ref } from 'vue';

// Music service interface - can be easily swapped for different providers
interface Track {
  title: string;
  artist: string;
  url: string;
  coverArt?: string;
}

// Playlist of synthwave/retro lofi tracks using reliable sources
const playlist: Track[] = [
  {
    title: 'Lofi Chill',
    artist: 'FASSounds',
    url: 'https://cdn.pixabay.com/download/audio/2023/09/27/audio_7ecac3e8f5.mp3',
    coverArt: 'https://cdn.pixabay.com/user/2021/06/14/09-55-48-337_250x250.png'
  },
  {
    title: 'Electronic Ambient',
    artist: 'SoulProdMusic',
    url: 'https://cdn.pixabay.com/download/audio/2023/06/13/audio_b89e2ac088.mp3',
    coverArt: 'https://cdn.pixabay.com/user/2022/05/20/07-23-28-581_250x250.jpg'
  },
  {
    title: 'Calm Background',
    artist: 'Music_Unlimited',
    url: 'https://cdn.pixabay.com/download/audio/2023/05/15/audio_1b0b6e1607.mp3',
    coverArt: 'https://cdn.pixabay.com/user/2022/03/15/09-51-28-329_250x250.jpg'
  }
];

// Player state
const currentTrack = ref(0);
const isPlaying = ref(false);
const audio = ref<HTMLAudioElement | null>(null);
const progress = ref(0);
const volume = ref(0.5);
const showPlayer = ref(true);
const audioLoaded = ref(false);
const audioError = ref(false);
const isLoading = ref(false);
const showConnectButton = ref(true);
const isAuthenticated = ref(false);
const showAnyPlayer = ref(false);
const spotifyLoginUrl = ref('/spotify/login');

// Get current track
const getCurrentTrack = (): Track => {
  return playlist[currentTrack.value % playlist.length];
};

// Initialize audio and check authentication
onMounted(async () => {
  try {
    // Initially hide everything until we check authentication
    showAnyPlayer.value = false;

    // Check if we have a valid token
    const response = await fetch('/spotify/token');
    const data = await response.json();

    console.log('Spotify token response:', data);

    if (data.error || data.requires_authentication) {
      console.log('No valid Spotify token, showing connect button');
      showConnectButton.value = true;
      isAuthenticated.value = false;
      showAnyPlayer.value = true; // Show the connect button
      return;
    }

    isAuthenticated.value = !data.is_default;
    showConnectButton.value = false; // We have a token, don't show connect button
    showAnyPlayer.value = true; // Show the player

    // Initialize audio player
    initializeAudio();
  } catch (error) {
    console.error('Error checking authentication:', error);
    showConnectButton.value = true;
    isAuthenticated.value = false;
    showAnyPlayer.value = true; // Show the connect button on error
  }
});

// Initialize audio function
function initializeAudio() {
  // Create audio element
  audio.value = new Audio();

  // Set up event listeners
  audio.value.addEventListener('timeupdate', updateProgress);
  audio.value.addEventListener('ended', playNext);
  audio.value.addEventListener('loadeddata', () => {
    console.log('Audio data loaded');
    audioLoaded.value = true;
    isLoading.value = false;
    audioError.value = false;
  });
  audio.value.addEventListener('error', (e) => {
    console.error('Audio error:', e);
    audioError.value = true;
    isLoading.value = false;
  });

  // Set initial volume
  audio.value.volume = volume.value;

  // Load first track
  loadCurrentTrack();
}

// Load the current track
function loadCurrentTrack() {
  if (!audio.value) return;

  try {
    isLoading.value = true;
    audioLoaded.value = false;
    audioError.value = false;

    const track = getCurrentTrack();
    console.log('Loading track:', track.title, track.url);

    audio.value.src = track.url;
    audio.value.load();
  } catch (error) {
    console.error('Error loading track:', error);
    audioError.value = true;
    isLoading.value = false;
  }
}

function updateProgress() {
  if (!audio.value || isNaN(audio.value.duration)) return;
  progress.value = (audio.value.currentTime / audio.value.duration) * 100;
}

function setProgress(event: MouseEvent) {
  if (!audio.value || isNaN(audio.value.duration)) return;
  const progressBar = event.currentTarget as HTMLDivElement;
  const clickPosition = event.offsetX / progressBar.offsetWidth;
  audio.value.currentTime = clickPosition * audio.value.duration;
}

function togglePlay() {
  console.log('Toggle play clicked');

  if (!audio.value) {
    console.log('No audio element found');
    return;
  }

  try {
    if (isPlaying.value) {
      console.log('Pausing audio');
      audio.value.pause();
      isPlaying.value = false;
    } else {
      console.log('Starting playback');
      isLoading.value = true;

      const playPromise = audio.value.play();
      if (playPromise !== undefined) {
        playPromise
          .then(() => {
            console.log('Playback started successfully');
            isPlaying.value = true;
            isLoading.value = false;
          })
          .catch(error => {
            console.error('Playback failed:', error);
            isPlaying.value = false;
            isLoading.value = false;
            audioError.value = true;
          });
      }
    }
  } catch (error) {
    console.error('Error in togglePlay:', error);
    isPlaying.value = false;
    isLoading.value = false;
    audioError.value = true;
  }
}

function playNext() {
  if (!audio.value) return;

  currentTrack.value = (currentTrack.value + 1) % playlist.length;
  loadCurrentTrack();

  if (isPlaying.value) {
    setTimeout(() => {
      audio.value?.play().catch(error => {
        console.error('Error playing next track:', error);
      });
    }, 100);
  }
}

function playPrevious() {
  if (!audio.value) return;

  currentTrack.value = (currentTrack.value - 1 + playlist.length) % playlist.length;
  loadCurrentTrack();

  if (isPlaying.value) {
    setTimeout(() => {
      audio.value?.play().catch(error => {
        console.error('Error playing previous track:', error);
      });
    }, 100);
  }
}

function setVolume(event: Event) {
  const input = event.target as HTMLInputElement;
  if (!audio.value) return;
  volume.value = parseFloat(input.value);
  audio.value.volume = volume.value;
}

function togglePlayer() {
  showPlayer.value = !showPlayer.value;
}

// Fix the image error handling with proper type checking
function handleImageError(event: Event) {
  const imgElement = event.target as HTMLImageElement;
  if (imgElement && imgElement instanceof HTMLImageElement) {
    imgElement.style.display = 'none';
  }
}

// Function to handle Spotify login
function handleSpotifyLogin(event: Event) {
  event.preventDefault();
  console.log('Redirecting to Spotify login...');
  window.location.href = spotifyLoginUrl.value;
}
</script>

<template>
  <div
    v-if="showAnyPlayer"
    class="fixed bottom-4 right-4 z-50 transition-all duration-500"
    :class="showPlayer ? 'translate-x-0' : 'translate-x-[calc(100%+1rem)]'"
  >
    <!-- Toggle Button -->
    <button
      v-if="!showConnectButton"
      @click="togglePlayer"
      class="absolute -left-12 bottom-0 flex h-10 w-10 items-center justify-center rounded-l-lg bg-background/80 backdrop-blur-sm transition-colors hover:bg-background"
    >
      <Icon
        :icon="showPlayer ? 'carbon:music' : 'carbon:play-filled'"
        class="h-5 w-5 animate-pulse text-primary"
      />
    </button>

    <!-- Connect Spotify Button -->
    <div v-if="showConnectButton" class="glass-effect overflow-hidden rounded-lg border border-white/10 bg-background/80 p-4 shadow-lg backdrop-blur-sm">
      <div class="flex flex-col items-center gap-3">
        <div class="flex items-center gap-2">
          <Icon icon="mdi:spotify" class="h-5 w-5 text-[#1DB954]" />
          <span class="text-sm font-medium text-foreground/90">Spotify Premium Requerido</span>
        </div>
        <p class="text-center text-xs text-foreground/70">
          Para reproducir música, necesitas iniciar sesión con tu cuenta Premium de Spotify
        </p>
        <button
          @click="handleSpotifyLogin"
          class="flex w-full items-center justify-center gap-2 rounded-md bg-[#1DB954] px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-[#1ed760] hover:shadow-lg"
        >
          <Icon icon="mdi:spotify" class="h-5 w-5" />
          Conectar con Spotify Premium
        </button>
      </div>
    </div>

    <!-- Player Card -->
    <div v-else class="glass-effect relative w-72 overflow-hidden rounded-lg border border-white/10 bg-background/80 p-4 shadow-lg backdrop-blur-sm">
      <!-- Loading Overlay -->
      <div v-if="isLoading" class="absolute inset-0 z-10 flex items-center justify-center bg-background/50 backdrop-blur-sm">
        <div class="flex flex-col items-center">
          <Icon icon="carbon:circle-dash" class="h-8 w-8 animate-spin text-primary" />
          <p class="mt-2 text-xs text-primary">Cargando...</p>
        </div>
      </div>

      <!-- Track Info with Cover Art -->
      <div class="mb-4 flex items-center">
        <!-- Cover Art (if available) -->
        <div v-if="getCurrentTrack().coverArt" class="relative mr-3 h-12 w-12 flex-shrink-0 overflow-hidden rounded-md">
          <img
            :src="getCurrentTrack().coverArt"
            :alt="getCurrentTrack().title"
            class="h-full w-full object-cover"
            @error="handleImageError"
          />
        </div>

        <!-- Track Info -->
        <div class="flex-1 overflow-hidden">
          <h3 class="text-glow-multi mb-1 truncate text-sm font-semibold">
            {{ getCurrentTrack().title }}
          </h3>
          <p class="truncate text-xs text-foreground/70">{{ getCurrentTrack().artist }}</p>
        </div>
      </div>

      <!-- Play Message (when not playing) -->
      <div v-if="!isPlaying && audioLoaded && !audioError" class="mb-4 text-center">
        <p class="text-xs text-cyan-400">Haz clic en play para comenzar la música</p>
      </div>

      <!-- Error Message -->
      <div v-if="audioError" class="mb-4 text-center">
        <p class="text-xs text-red-400">Error al cargar el audio. Verifica tu conexión.</p>
      </div>

      <!-- Progress Bar -->
      <div
        class="group mb-4 h-1 cursor-pointer rounded-full bg-white/10"
        @click="setProgress"
      >
        <div
          class="relative h-full rounded-full bg-gradient-to-r from-primary via-cyan-400 to-secondary transition-all"
          :style="{ width: `${progress}%` }"
        >
          <div class="absolute right-0 top-1/2 h-3 w-3 -translate-y-1/2 scale-0 rounded-full border-2 border-white bg-primary transition-transform group-hover:scale-100"></div>
        </div>
      </div>

      <!-- Controls -->
      <div class="mb-4 flex items-center justify-center space-x-4">
        <button
          @click="playPrevious"
          :disabled="isLoading"
          class="rounded-full p-2 text-foreground/70 transition-colors hover:text-primary disabled:opacity-50"
        >
          <Icon icon="carbon:previous-filled" class="h-5 w-5" />
        </button>

        <button
          @click="togglePlay"
          :disabled="isLoading"
          class="flex h-10 w-10 items-center justify-center rounded-full bg-primary text-white transition-all hover:bg-primary/80 hover:shadow-[0_0_20px_rgba(124,58,237,0.5)] disabled:opacity-50"
        >
          <Icon :icon="isPlaying ? 'carbon:pause-filled' : 'carbon:play-filled'" class="h-5 w-5" />
        </button>

        <button
          @click="playNext"
          :disabled="isLoading"
          class="rounded-full p-2 text-foreground/70 transition-colors hover:text-primary disabled:opacity-50"
        >
          <Icon icon="carbon:next-filled" class="h-5 w-5" />
        </button>
      </div>

      <!-- Volume Control -->
      <div class="flex items-center space-x-2">
        <Icon
          icon="carbon:volume-down"
          class="h-4 w-4 text-foreground/70"
        />
        <input
          type="range"
          min="0"
          max="1"
          step="0.1"
          :value="volume"
          @input="setVolume"
          :disabled="isLoading"
          class="h-1 w-full cursor-pointer appearance-none rounded-lg bg-white/10 disabled:opacity-50"
        />
        <Icon
          icon="carbon:volume-up"
          class="h-4 w-4 text-foreground/70"
        />
      </div>
    </div>
  </div>
</template>

<style scoped>
input[type="range"]::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 12px;
  height: 12px;
  background: theme('colors.primary.DEFAULT');
  border-radius: 50%;
  cursor: pointer;
  transition: all 0.3s;
}

input[type="range"]::-webkit-slider-thumb:hover {
  transform: scale(1.2);
  box-shadow: 0 0 10px theme('colors.primary.DEFAULT');
}

.text-glow-multi {
  animation: textGlow 6s infinite;
}

@keyframes textGlow {
  0%, 100% {
    color: theme('colors.primary.DEFAULT');
    text-shadow: 0 0 10px theme('colors.primary.DEFAULT / 50%');
  }
  33% {
    color: theme('colors.cyan.400');
    text-shadow: 0 0 10px theme('colors.cyan.400 / 50%');
  }
  66% {
    color: theme('colors.secondary.DEFAULT');
    text-shadow: 0 0 10px theme('colors.secondary.DEFAULT / 50%');
  }
}
</style>