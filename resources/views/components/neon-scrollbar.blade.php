@props([
    'height' => null,
    'width' => '8px',
    'class' => '',
    'colorCycle' => false,
    'trackColor' => 'rgba(255, 255, 255, 0.05)',
    'thumbColor' => 'hsl(var(--primary) / 0.5)',
    'thumbHoverColor' => 'hsl(var(--primary) / 0.8)'
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
    width: {{ $width }};
    height: {{ $width }};
    background: transparent;
}

.neon-scrollbar::-webkit-scrollbar-track {
    background: {{ $trackColor }};
    border-radius: 10px;
}

.neon-scrollbar::-webkit-scrollbar-thumb {
    background: {{ $thumbColor }};
    border-radius: 10px;
    transition: background-color 0.3s ease;
}

.neon-scrollbar::-webkit-scrollbar-thumb:hover {
    background: {{ $thumbHoverColor }};
}

/* Firefox */
.neon-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: {{ $thumbColor }} {{ $trackColor }};
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

/* Mobile optimization */
@media screen and (max-width: 768px) {
    .neon-scrollbar::-webkit-scrollbar {
        width: 3px !important;
        height: 3px !important;
    }
}
</style>