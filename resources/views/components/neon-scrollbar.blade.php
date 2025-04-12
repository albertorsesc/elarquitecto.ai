@props([
    'height' => null,
    'width' => null, 
    'class' => '',
    'colorCycle' => false
])

<div
    class="neon-scrollbar {{ $class }} {{ $colorCycle ? 'color-cycle' : '' }}"
    style="{{ $height ? "height: {$height};" : '' }} {{ $width ? "width: {$width};" : '' }}"
>
    {{ $slot }}
</div>

<style>
.neon-scrollbar {
    overflow-y: auto;
    position: relative;
}

/* Webkit (Chrome, Safari, newer versions of Opera) */
.neon-scrollbar::-webkit-scrollbar {
    width: 12px;
    background: transparent;
}

.neon-scrollbar::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.95);
    margin: 2px;
}

.neon-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(var(--color-primary-rgb, 0, 255, 255), 0.2);
    border: 3px solid rgba(0, 0, 0, 0.95);
    border-radius: 20px;
    box-shadow:
        inset 0 0 5px rgba(255, 255, 255, 0.1),
        0 0 5px rgba(var(--color-primary-rgb, 0, 255, 255), 0.1);
}

.neon-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(var(--color-primary-rgb, 0, 255, 255), 0.3);
    box-shadow:
        inset 0 0 6px rgba(255, 255, 255, 0.2),
        0 0 6px rgba(var(--color-primary-rgb, 0, 255, 255), 0.2);
}

/* Firefox */
.neon-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: rgba(var(--color-primary-rgb, 0, 255, 255), 0.2) rgba(0, 0, 0, 0.95);
}

/* Remove the pseudo-elements that might be interfering with scrolling */
.neon-scrollbar::before,
.neon-scrollbar::after {
    display: none;
}

/* Simplified color cycle animation */
.neon-scrollbar.color-cycle::-webkit-scrollbar-thumb {
    animation: simpleColorCycle 10s infinite;
}

@keyframes simpleColorCycle {
    0%, 100% {
        background: rgba(var(--color-primary-rgb, 0, 255, 255), 0.2);
    }
    33% {
        background: rgba(var(--color-secondary-rgb, 255, 0, 255), 0.2);
    }
    66% {
        background: rgba(var(--color-accent-rgb, 255, 255, 0), 0.2);
    }
}
</style>