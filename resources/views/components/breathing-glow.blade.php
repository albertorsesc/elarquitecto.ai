@props([
    'fromColor' => 'primary/5',
    'viaColor' => 'cyan-400/5',
    'toColor' => 'accent/5',
    'direction' => 'br',
    'hoverEffect' => true,
    'rounded' => 'xl'
])

<!-- Breathing glow effect -->
<div class="absolute inset-0 animate-breath rounded-{{ $rounded }} bg-gradient-to-{{ $direction }} from-{{ $fromColor }} via-{{ $viaColor }} to-{{ $toColor }} {{ $hoverEffect ? 'opacity-0 group-hover:opacity-100' : 'opacity-100' }}"></div> 