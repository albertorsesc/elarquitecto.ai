<script setup lang="ts">
import { ref } from 'vue';
import NeonBorders from './NeonBorders.vue';
import NeonCorners from './NeonCorners.vue';

interface Props {
  placeholder?: string;
  variant?: 'default' | 'spotlight';
}

const props = withDefaults(defineProps<Props>(), {
  placeholder: 'Search...',
  variant: 'default'
});

const isActive = ref(false);
const searchQuery = ref('');

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void;
  (e: 'search', value: string): void;
  (e: 'focus'): void;
  (e: 'blur'): void;
}>();

function handleInput(event: Event) {
  const value = (event.target as HTMLInputElement).value;
  searchQuery.value = value;
  emit('update:modelValue', value);
}

function handleFocus() {
  isActive.value = true;
  emit('focus');
}

function handleBlur() {
  if (!searchQuery.value) {
    isActive.value = false;
  }
  emit('blur');
}

function handleKeydown(event: KeyboardEvent) {
  if (event.key === 'Enter') {
    emit('search', searchQuery.value);
  } else if (event.key === 'Escape') {
    searchQuery.value = '';
    isActive.value = false;
    emit('update:modelValue', '');
  }
}
</script>

<template>
  <div class="search-container group relative">
    <!-- Default Search Bar -->
    <template v-if="props.variant === 'default'">
      <input
        type="text"
        :placeholder="props.placeholder"
        v-model="searchQuery"
        class="peer relative z-10 w-full rounded-xl border border-white/10 bg-background/50 py-1.5 pl-8 pr-4 text-sm text-foreground placeholder:text-foreground/50 focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300"
        @focus="handleFocus"
        @blur="handleBlur"
        @input="handleInput"
        @keydown="handleKeydown"
      />
      <div class="absolute inset-y-0 left-0 z-10 flex items-center pl-2.5 text-foreground/50">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
        </svg>
      </div>

      <!-- Animated border effect -->
      <div class="absolute bottom-0 left-0 h-[1px] w-0 bg-gradient-to-r from-primary via-cyan-400 to-secondary transition-all duration-300 group-focus-within:w-full"></div>

      <!-- Sliding neon lights (visible on focus) -->
      <div class="pointer-events-none absolute -inset-1 opacity-0 group-focus-within:opacity-100">
        <NeonBorders color="gradient" :opacity="0.3" />
      </div>

      <!-- Corner accents -->
      <div class="opacity-0 transition-opacity duration-300 group-focus-within:opacity-100">
        <NeonCorners size="sm" />
      </div>
    </template>

    <!-- Spotlight Search -->
    <template v-else>
      <div class="glass-effect relative overflow-hidden rounded-xl border border-white/20 bg-background/80 shadow-[0_0_30px_rgba(124,58,237,0.3)]">
        <!-- Search Input -->
        <div class="relative">
          <div class="absolute inset-y-0 left-0 z-10 flex items-center pl-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-cyan-400" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
          </div>
          <input
            type="text"
            :placeholder="props.placeholder"
            v-model="searchQuery"
            class="relative z-10 w-full border-b border-white/10 bg-transparent py-4 pl-12 pr-4 text-lg text-foreground placeholder:text-foreground/50 focus:outline-none"
            @focus="handleFocus"
            @blur="handleBlur"
            @input="handleInput"
            @keydown="handleKeydown"
          />
          <div v-if="searchQuery" class="absolute inset-y-0 right-0 z-10 flex items-center pr-4">
            <button
              class="rounded-full p-1 text-foreground/50 hover:bg-white/10 hover:text-foreground"
              @click="searchQuery = ''"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Neon effects -->
        <NeonBorders color="gradient" :opacity="0.3" />
        <NeonCorners />
      </div>
    </template>
  </div>
</template>

<style scoped>
.search-container {
  width: 100%;
}

/* Glass effect from theme */
.glass-effect {
  backdrop-filter: blur(10px);
}
</style>