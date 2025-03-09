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

// Playlist of synthwave/retro lofi tracks using free sources
// Using Free Music Archive tracks that don't require API tokens
const playlist: Track[] = [
  {
    title: 'Algorithms',
    artist: 'Chad Crouch',
    url: 'https://files.freemusicarchive.org/storage-freemusicarchive-org/music/ccCommunity/Chad_Crouch/Arps/Chad_Crouch_-_Algorithms.mp3',
    coverArt: 'https://freemusicarchive.org/image/?file=images%2Falbums%2FChad_Crouch_-_Arps_-_2019090857957670.jpg&width=290&height=290&type=album'
  },
  {
    title: 'Moonrise',
    artist: 'Chad Crouch',
    url: 'https://files.freemusicarchive.org/storage-freemusicarchive-org/music/ccCommunity/Chad_Crouch/Arps/Chad_Crouch_-_Moonrise.mp3',
    coverArt: 'https://freemusicarchive.org/image/?file=images%2Falbums%2FChad_Crouch_-_Arps_-_2019090857957670.jpg&width=290&height=290&type=album'
  },
  {
    title: 'Coil',
    artist: 'Ketsa',
    url: 'https://files.freemusicarchive.org/storage-freemusicarchive-org/music/no_curator/Ketsa/Raising_Frequency/Ketsa_-_13_-_Coil.mp3',
    coverArt: 'https://freemusicarchive.org/image/?file=images%2Falbums%2FKetsa_-_Raising_Frequency_-_20190130131425112.jpg&width=290&height=290&type=album'
  }
];

// Fallback playlist using alternative Free Music Archive tracks
const fallbackPlaylist: Track[] = [
  {
    title: 'Cylinder Nine',
    artist: 'Chris Zabriskie',
    url: 'https://files.freemusicarchive.org/storage-freemusicarchive-org/music/ccCommunity/Chris_Zabriskie/Cylinders/Chris_Zabriskie_-_09_-_Cylinder_Nine.mp3',
    coverArt: 'https://freemusicarchive.org/image/?file=images%2Falbums%2FChris_Zabriskie_-_Cylinders_-_20150310173731112.jpg&width=290&height=290&type=album'
  },
  {
    title: 'Cylinder Eight',
    artist: 'Chris Zabriskie',
    url: 'https://files.freemusicarchive.org/storage-freemusicarchive-org/music/ccCommunity/Chris_Zabriskie/Cylinders/Chris_Zabriskie_-_08_-_Cylinder_Eight.mp3',
    coverArt: 'https://freemusicarchive.org/image/?file=images%2Falbums%2FChris_Zabriskie_-_Cylinders_-_20150310173731112.jpg&width=290&height=290&type=album'
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
const useFallback = ref(false);

// Get current track based on fallback state
const getCurrentTrack = (): Track => {
  const currentList = useFallback.value ? fallbackPlaylist : playlist;
  return currentList[currentTrack.value % currentList.length];
};

// Initialize audio
onMounted(() => {
  initializeAudio();
});

// Audio initialization function - can be called to reinitialize with different sources
function initializeAudio() {
  try {
    // Create audio element if it doesn't exist
    if (!audio.value) {
      audio.value = new Audio();

      // Update progress bar
      audio.value.addEventListener('timeupdate', updateProgress);

      // Auto-play next track
      audio.value.addEventListener('ended', playNext);

      // Mark as loaded
      audio.value.addEventListener('loadeddata', () => {
        audioLoaded.value = true;
        audioError.value = false;
      });

      // Handle errors
      audio.value.addEventListener('error', (e) => {
        console.error('Audio error:', e);
        audioError.value = true;

        // Try fallback if main playlist fails
        if (!useFallback.value) {
          useFallback.value = true;
          currentTrack.value = 0;
          loadCurrentTrack();
        }
      });
    }

    // Set initial volume and load track
    if (audio.value) {
      audio.value.volume = volume.value;
      loadCurrentTrack();
    }
  } catch (error) {
    console.error('Error initializing audio:', error);
    audioError.value = true;
  }
}

// Load the current track
function loadCurrentTrack() {
  if (!audio.value) return;

  const track = getCurrentTrack();
  audio.value.src = track.url;
  audio.value.load();
  audioLoaded.value = false;
}

function updateProgress() {
  if (!audio.value) return;
  progress.value = (audio.value.currentTime / audio.value.duration) * 100;
}

function setProgress(event: MouseEvent) {
  if (!audio.value) return;
  const progressBar = event.currentTarget as HTMLDivElement;
  const clickPosition = event.offsetX / progressBar.offsetWidth;
  audio.value.currentTime = clickPosition * audio.value.duration;
}

function togglePlay() {
  if (!audio.value) return;

  try {
    if (isPlaying.value) {
      audio.value.pause();
      isPlaying.value = false;
    } else {
      // Make sure the source is set
      const track = getCurrentTrack();
      if (!audio.value.src || audio.value.src !== track.url) {
        loadCurrentTrack();
      }

      const playPromise = audio.value.play();

      if (playPromise !== undefined) {
        playPromise
          .then(() => {
            isPlaying.value = true;
            audioError.value = false;
          })
          .catch(error => {
            console.log('Playback prevented:', error);
            audioError.value = true;
          });
      }
    }
  } catch (error) {
    console.error('Error toggling play state:', error);
    audioError.value = true;
  }
}

function playNext() {
  if (!audio.value) return;

  const currentList = useFallback.value ? fallbackPlaylist : playlist;
  currentTrack.value = (currentTrack.value + 1) % currentList.length;
  loadCurrentTrack();

  if (isPlaying.value) {
    const playPromise = audio.value.play();
    if (playPromise !== undefined) {
      playPromise.catch(error => {
        console.log('Error playing next track:', error);
        isPlaying.value = false;
      });
    }
  }
}

function playPrevious() {
  if (!audio.value) return;

  const currentList = useFallback.value ? fallbackPlaylist : playlist;
  currentTrack.value = (currentTrack.value - 1 + currentList.length) % currentList.length;
  loadCurrentTrack();

  if (isPlaying.value) {
    const playPromise = audio.value.play();
    if (playPromise !== undefined) {
      playPromise.catch(error => {
        console.log('Error playing previous track:', error);
        isPlaying.value = false;
      });
    }
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
</script>

<template>
  <div
    class="fixed bottom-4 right-4 z-50 transition-all duration-500"
    :class="showPlayer ? 'translate-x-0' : 'translate-x-[calc(100%+1rem)]'"
  >
    <!-- Toggle Button -->
    <button
      @click="togglePlayer"
      class="absolute -left-12 bottom-0 flex h-10 w-10 items-center justify-center rounded-l-lg bg-background/80 backdrop-blur-sm transition-colors hover:bg-background"
    >
      <Icon
        :icon="showPlayer ? 'carbon:music' : 'carbon:play-filled'"
        class="h-5 w-5 animate-pulse text-primary"
      />
    </button>

    <!-- Player Card -->
    <div class="glass-effect w-72 overflow-hidden rounded-lg border border-white/10 bg-background/80 p-4 shadow-lg backdrop-blur-sm">
      <!-- Track Info with Cover Art -->
      <div class="mb-4 flex items-center">
        <!-- Cover Art (if available) -->
        <div v-if="getCurrentTrack().coverArt" class="mr-3 h-12 w-12 flex-shrink-0 overflow-hidden rounded-md">
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
        <p class="text-xs text-red-400">
          {{ useFallback ? 'Error al cargar el audio. Verifica tu conexión.' : 'Error al cargar el audio. Usando pistas alternativas.' }}
        </p>
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
          class="rounded-full p-2 text-foreground/70 transition-colors hover:text-primary"
        >
          <Icon icon="carbon:previous-filled" class="h-5 w-5" />
        </button>

        <button
          @click="togglePlay"
          class="flex h-10 w-10 items-center justify-center rounded-full bg-primary text-white transition-all hover:bg-primary/80 hover:shadow-[0_0_20px_rgba(124,58,237,0.5)]"
        >
          <Icon :icon="isPlaying ? 'carbon:pause-filled' : 'carbon:play-filled'" class="h-5 w-5" />
        </button>

        <button
          @click="playNext"
          class="rounded-full p-2 text-foreground/70 transition-colors hover:text-primary"
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
          class="h-1 w-full cursor-pointer appearance-none rounded-lg bg-white/10"
        />
        <Icon
          icon="carbon:volume-up"
          class="h-4 w-4 text-foreground/70"
        />
      </div>

      <!-- Neon border effects -->
      <div class="pointer-events-none absolute inset-0 rounded-lg">
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