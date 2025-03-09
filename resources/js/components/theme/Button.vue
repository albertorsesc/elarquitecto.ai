<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { PropType } from 'vue';

defineProps({
    to: {
        type: String,
        required: true,
    },
    text: {
        type: String,
        required: true,
    },
    variant: {
        type: String as PropType<'primary' | 'secondary' | 'accent' | 'cyan'>,
        default: 'primary',
    },
    size: {
        type: String as PropType<'sm' | 'md' | 'lg'>,
        default: 'md',
    },
});
</script>

<template>
    <Link
        :href="to"
        class="neon-border rounded transition-all"
        :class="[
            variant === 'primary' ? 'bg-primary hover:bg-primary/80 text-white' : '',
            variant === 'secondary' ? 'bg-secondary hover:bg-secondary/80 text-white' : '',
            variant === 'accent' ? 'bg-accent hover:bg-accent/80 text-white' : '',
            variant === 'cyan' ? 'bg-cyan-400 hover:bg-cyan-400/80 text-white' : '',
            size === 'sm' ? 'px-2 py-1 text-xs' : '',
            size === 'md' ? 'px-3 py-1.5 text-sm' : '',
            size === 'lg' ? 'px-4 py-2 text-base' : '',
            'font-semibold'
        ]"
    >
        {{ text }}
    </Link>
</template>

<style scoped>
.neon-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    font-weight: medium;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    text-decoration: none;
    color: white;
    border: 2px solid transparent;
}

.neon-button:hover {
    background-color: rgba(255, 255, 255, 0.1);
    border-color: currentColor;
}

.content {
    position: relative;
    z-index: 2;
}

.primary {
    background-color: var(--color-primary);
}

.secondary {
    background-color: var(--color-secondary);
}

.accent {
    background-color: var(--color-accent);
}

.cyan {
    background-color: var(--color-cyan);
}

.neon-button:hover .primary {
    border-color: var(--color-primary);
}

.neon-button:hover .secondary {
    border-color: var(--color-secondary);
}

.neon-button:hover .accent {
    border-color: var(--color-accent);
}

.neon-button:hover .cyan {
    border-color: var(--color-cyan);
}
</style>