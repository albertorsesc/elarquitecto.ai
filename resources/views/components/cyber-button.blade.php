@props([
    'href' => null,
    'variant' => 'outline', 
    'size' => 'md',
    'fullWidth' => false,
    'disabled' => false,
    'type' => 'button'
])

@php
    $baseClasses = 'inline-flex items-center justify-center rounded-md font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50';
    
    // Variant styles
    $variantClasses = [
        'default' => 'bg-primary text-primary-foreground hover:bg-primary/90',
        'destructive' => 'bg-destructive text-destructive-foreground hover:bg-destructive/90',
        'outline' => 'border border-border/50 hover:bg-primary/10 hover:text-primary',
        'secondary' => 'bg-secondary text-secondary-foreground hover:bg-secondary/80',
        'ghost' => 'hover:bg-accent hover:text-accent-foreground',
        'link' => 'text-primary underline-offset-4 hover:underline',
        'primary' => 'bg-primary text-primary-foreground hover:bg-primary/90 shadow-[0_0_10px_rgba(var(--primary-rgb),0.5)] hover:shadow-[0_0_15px_rgba(var(--primary-rgb),0.8)]'
    ][$variant];
    
    // Size styles
    $sizeClasses = [
        'sm' => 'h-8 px-3 text-xs',
        'md' => 'h-10 px-4 py-2',
        'lg' => 'h-12 px-8 text-lg'
    ][$size];
    
    $fullWidthClass = $fullWidth ? 'w-full' : '';
    
    $classes = "$baseClasses $variantClasses $sizeClasses $fullWidthClass";
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button 
        type="{{ $type }}" 
        {{ $disabled ? 'disabled' : '' }} 
        {{ $attributes->merge(['class' => $classes]) }}
    >
        {{ $slot }}
    </button>
@endif 