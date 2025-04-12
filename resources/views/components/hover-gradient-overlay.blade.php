@props([
    'fromColor' => 'background/50',
    'viaColor' => 'transparent',
    'toColor' => 'transparent',
    'direction' => 't',
    'opacity' => '0',
    'hoverOpacity' => '100',
    'duration' => '300'
])

<!-- Hover gradient overlay effect -->
<div class="absolute inset-0 bg-gradient-to-{{ $direction }} from-{{ $fromColor }} via-{{ $viaColor }} to-{{ $toColor }} opacity-{{ $opacity }} transition-opacity duration-{{ $duration }} group-hover:opacity-{{ $hoverOpacity }}"></div> 