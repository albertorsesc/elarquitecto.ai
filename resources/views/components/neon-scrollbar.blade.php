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

/* Custom scrollbar - isolated from animations */
/* These styles isolate the scrollbar from animation effects */
/* For modern WebKit browsers (Chrome, Safari, Edge) */
::-webkit-scrollbar {
    width: {{ $width }};
    height: {{ $width }};
}

::-webkit-scrollbar-track {
    background: {{ $trackColor }};
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: {{ $thumbColor }};
    border-radius: 10px;
    transition: background-color 0.3s ease;
}

::-webkit-scrollbar-thumb:hover {
    background: {{ $thumbHoverColor }};
}

/* For Firefox */
* {
    scrollbar-width: thin;
    scrollbar-color: {{ $thumbColor }} {{ $trackColor }};
}

/* Ensure the scrollbar doesn't inherit animation properties */
::-webkit-scrollbar-thumb, 
::-webkit-scrollbar-track {
    -webkit-animation: none !important;
    animation: none !important;
    backdrop-filter: none !important;
    filter: none !important;
    transform: none !important;
}

/* Avoid unnecessary repaints on scrollbar */
* {
    will-change: auto !important;
}

/* Make sure scrollbar doesn't get affected by neon effects */
::-webkit-scrollbar-corner {
    background: transparent;
}
</style>