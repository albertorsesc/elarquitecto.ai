@props([
    'leftFromColor' => 'primary',
    'leftViaColor' => 'cyan-400',
    'rightFromColor' => 'secondary',
    'rightViaColor' => 'accent',
    'opacity' => '30',
    'hoverEffect' => true
])

<!-- Animated corner accents with color animation -->
<div class="absolute left-0 top-0 h-8 w-8 opacity-{{ $opacity }} {{ $hoverEffect ? 'group-hover:opacity-100' : '' }}">
  <div class="absolute left-0 top-0 h-full w-[1px] animate-glow-color bg-gradient-to-b from-{{ $leftFromColor }} via-{{ $leftViaColor }} to-transparent"></div>
  <div class="absolute left-0 top-0 h-[1px] w-full animate-glow-color bg-gradient-to-r from-{{ $leftFromColor }} via-{{ $leftViaColor }} to-transparent"></div>
</div>
<div class="absolute right-0 top-0 h-8 w-8 opacity-{{ $opacity }} {{ $hoverEffect ? 'group-hover:opacity-100' : '' }}">
  <div class="absolute right-0 top-0 h-full w-[1px] animate-glow-color-delayed bg-gradient-to-b from-{{ $rightFromColor }} via-{{ $rightViaColor }} to-transparent"></div>
  <div class="absolute right-0 top-0 h-[1px] w-full animate-glow-color-delayed bg-gradient-to-l from-{{ $rightFromColor }} via-{{ $rightViaColor }} to-transparent"></div>
</div> 