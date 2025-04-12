@props([
    'rounded' => 'xl',
    'opacity' => '10',
    'hoverOpacity' => '20',
    'transition' => true
])

<!-- White border that changes opacity on hover -->
<div class="absolute inset-0 rounded-{{ $rounded }} border border-white/{{ $opacity }} {{ $transition ? 'transition-all duration-300 group-hover:border-white/'.$hoverOpacity : '' }}"></div> 