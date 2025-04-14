@props([
    'href' => '#',
    'variant' => 'primary', 
    'size' => 'md',
    'animated' => true,
    'glow' => true,
    'fullWidth' => false,
    'disabled' => false
])

@php
    $baseClasses = 'inline-flex items-center justify-center rounded-lg font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:pointer-events-none disabled:opacity-50';
    
    // Size styles
    $sizeClasses = [
        'sm' => 'px-3 py-1 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base'
    ][$size];
    
    // Variant styles
    $variantClasses = [
        'primary' => 'border border-primary/30 bg-primary/10 text-primary-foreground hover:bg-primary/90 focus:ring-primary/50',
        'secondary' => 'border border-secondary/30 bg-secondary/10 text-secondary-foreground hover:bg-secondary/90 focus:ring-secondary/50',
        'accent' => 'border border-accent/30 bg-accent/10 text-accent-foreground hover:bg-accent/90 focus:ring-accent/50',
        'outline' => 'border border-border bg-transparent text-foreground hover:bg-muted hover:text-foreground focus:ring-muted'
    ][$variant];
    
    // Effect classes
    $effectClasses = [];
    if ($animated) $effectClasses[] = 'neon-border';
    if ($glow) $effectClasses[] = 'group';
    if ($fullWidth) $effectClasses[] = 'w-full';
    
    $classes = "$baseClasses $sizeClasses $variantClasses " . implode(' ', $effectClasses);
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }} {{ $disabled ? 'disabled' : '' }}>
    <div class="relative overflow-hidden">
        {{ $slot }}
        
        @if($glow)
        <!-- Glowing accents that appear on hover -->
        <div class="absolute -inset-px opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/50 via-cyan-400/50 to-secondary/50 opacity-0 group-hover:opacity-20 backdrop-blur-sm rounded-md"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="h-[1px] w-[70%] bg-gradient-to-r from-primary via-cyan-400 to-secondary transform translate-y-[8px] blur-[2px] animate-neon-slide-right-slow"></div>
            </div>
        </div>
        @endif
    </div>
</a> 