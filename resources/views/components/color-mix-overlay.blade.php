@props([
    'fromColor' => 'primary/20',
    'toColor' => 'secondary/20',
    'viaColor' => 'transparent',
    'direction' => 'l',
    'opacity' => '30',
    'zIndex' => '10',
    'mixBlend' => 'overlay'
])

<!-- Color mix overlay effect -->
<div class="absolute inset-0 z-{{ $zIndex }} bg-gradient-to-{{ $direction }} from-{{ $fromColor }} via-{{ $viaColor }} to-{{ $toColor }} opacity-{{ $opacity }} mix-blend-{{ $mixBlend }}"></div> 