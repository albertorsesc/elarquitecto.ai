@props([
    'color' => 'primary',
    'opacity' => '10',
    'hoverEffect' => true,
    'size' => '80%',
    'blur' => '40px'
])

<div class="absolute inset-0 flex items-center justify-center pointer-events-none overflow-hidden opacity-{{ $opacity }} {{ $hoverEffect ? 'group-hover:opacity-20' : '' }} -z-5">
    <div class="absolute w-{{ $size }} h-{{ $size }} rounded-full animate-breath" 
         style="background: radial-gradient(circle, hsl(var(--{{ $color }})) 0%, transparent 70%); 
                filter: blur({{ $blur }});">
    </div>
</div> 