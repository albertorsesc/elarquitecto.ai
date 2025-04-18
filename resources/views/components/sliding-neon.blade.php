@props([
    'topColor' => 'accent',
    'rightColor' => 'primary',
    'bottomColor' => 'secondary',
    'leftColor' => 'cyan-400',
    'visible' => false,
    'opacity' => '30',
    'showOn' => 'focus' // Options: focus, hover, both, or always
])

<!-- Sliding neon lights -->
<div class="pointer-events-none absolute -inset-1 {{
    $visible || $showOn === 'always' ? '' : 
    ($showOn === 'focus' ? 'opacity-0 group-focus-within:opacity-100' : 
     ($showOn === 'hover' ? 'opacity-0 transition-opacity group-hover:opacity-100' : 
      'opacity-0 transition-opacity group-hover:opacity-100 group-focus-within:opacity-100'))
}}">
    <!-- Top edge -->
    <div class="absolute inset-x-0 top-0 h-[2px]">
        <div class="absolute inset-0 animate-neon-slide-right-slow bg-gradient-to-r from-transparent via-{{ $topColor }} to-transparent opacity-{{ $opacity }}"></div>
    </div>
    <!-- Right edge -->
    <div class="absolute right-0 top-0 h-full w-[2px]">
        <div class="absolute inset-0 animate-neon-slide-down-slow bg-gradient-to-b from-transparent via-{{ $rightColor }} to-transparent opacity-{{ $opacity }}"></div>
    </div>
    <!-- Bottom edge -->
    <div class="absolute bottom-0 inset-x-0 h-[2px]">
        <div class="absolute inset-0 animate-neon-slide-left-slow bg-gradient-to-r from-transparent via-{{ $bottomColor }} to-transparent opacity-{{ $opacity }}"></div>
    </div>
    <!-- Left edge -->
    <div class="absolute left-0 top-0 h-full w-[2px]">
        <div class="absolute inset-0 animate-neon-slide-up-slow bg-gradient-to-b from-transparent via-{{ $leftColor }} to-transparent opacity-{{ $opacity }}"></div>
    </div>
</div> 