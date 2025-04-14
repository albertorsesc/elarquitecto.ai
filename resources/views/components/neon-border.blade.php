@props([
    'opacity' => '30',
    'hoverEffect' => true
])

<div class="absolute -inset-1 opacity-{{ $opacity }} {{ $hoverEffect ? 'group-hover:opacity-100' : '' }}">
  <!-- Top edge -->
  <div class="absolute left-0 top-0 h-[2px] w-full animate-neon-slide-right bg-gradient-to-r from-transparent via-primary to-transparent"></div>
  <!-- Right edge -->
  <div class="absolute right-0 top-0 h-full w-[2px] animate-neon-slide-down bg-gradient-to-b from-transparent via-secondary to-transparent"></div>
  <!-- Bottom edge -->
  <div class="absolute bottom-0 left-0 h-[2px] w-full animate-neon-slide-left bg-gradient-to-r from-transparent via-accent to-transparent"></div>
  <!-- Left edge -->
  <div class="absolute left-0 top-0 h-full w-[2px] animate-neon-slide-up bg-gradient-to-b from-transparent via-primary to-transparent"></div>
</div> 