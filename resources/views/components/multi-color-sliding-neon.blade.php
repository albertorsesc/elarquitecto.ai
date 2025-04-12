@props([
    'topColor' => '#FF1CF7',
    'rightColor' => '#00FFE1',
    'bottomColor' => '#01FF88',
    'leftColor' => '#5B6EF7',
    'opacity' => '30',
    'hoverEffect' => true
])

<!-- Multi-colored sliding neon lights -->
<div class="absolute -inset-1 opacity-{{ $opacity }} {{ $hoverEffect ? 'group-hover:opacity-100' : '' }}">
  <!-- Top edge -->
  <div class="absolute left-0 top-0 h-[2px] w-full animate-neon-slide-right-color bg-gradient-to-r from-transparent via-[{{ $topColor }}] to-transparent"></div>
  <!-- Right edge -->
  <div class="absolute right-0 top-0 h-full w-[2px] animate-neon-slide-down-color bg-gradient-to-b from-transparent via-[{{ $rightColor }}] to-transparent"></div>
  <!-- Bottom edge -->
  <div class="absolute bottom-0 left-0 h-[2px] w-full animate-neon-slide-left-color bg-gradient-to-r from-transparent via-[{{ $bottomColor }}] to-transparent"></div>
  <!-- Left edge -->
  <div class="absolute left-0 top-0 h-full w-[2px] animate-neon-slide-up-color bg-gradient-to-b from-transparent via-[{{ $leftColor }}] to-transparent"></div>
</div> 