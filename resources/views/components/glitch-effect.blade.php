@props([
    'color1' => 'cyan-400',
    'color2' => 'primary',
    'color3' => 'accent',
    'defaultOpacity' => '0',
    'hoverOpacity' => '30',
    'zIndex' => '20'
])

<!-- Glitch effect lines -->
<div class="absolute inset-0 z-{{ $zIndex }} overflow-hidden opacity-{{ $defaultOpacity }} mix-blend-screen transition-opacity duration-300 group-hover:opacity-{{ $hoverOpacity }}">
  <div class="absolute inset-x-0 top-1/4 h-[1px] animate-glitch-line-1 bg-{{ $color1 }}"></div>
  <div class="absolute inset-x-0 top-1/3 h-[1px] animate-glitch-line-2 bg-{{ $color2 }}"></div>
  <div class="absolute inset-x-0 top-1/2 h-[1px] animate-glitch-line-3 bg-{{ $color3 }}"></div>
</div> 