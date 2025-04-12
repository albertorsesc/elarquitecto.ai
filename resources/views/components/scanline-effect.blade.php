@props([
    'opacity' => '10',
    'zIndex' => '30'
])

<!-- Scanline effect -->
<div class="absolute inset-0 z-{{ $zIndex }} bg-scanline opacity-{{ $opacity }}"></div> 