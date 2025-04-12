@props([
    'fromColor' => 'background',
    'toColor' => 'transparent',
    'viaColor' => 'transparent',
    'direction' => 't',
    'opacity' => '60',
    'zIndex' => '10'
])

<!-- Cyberpunk overlay effect -->
<div class="absolute inset-0 z-{{ $zIndex }} bg-gradient-to-{{ $direction }} from-{{ $fromColor }} via-{{ $viaColor }} to-{{ $toColor }} opacity-{{ $opacity }}"></div> 