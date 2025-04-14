@props([
    'opacity' => '70',
    'blur' => 'xl',
    'border' => true,
    'borderOpacity' => '50',
    'color' => null
])

<div class="absolute inset-0 -z-10 backdrop-blur-{{ $blur }} {{ $border ? 'border border-border/'.$borderOpacity : '' }} rounded-xl overflow-hidden"
     style="{{ $color ? 'background-color: ' . $color . ' !important;' : 'background-color: hsl(var(--card)) !important;' }}">
</div> 