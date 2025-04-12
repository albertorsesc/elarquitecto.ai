@props([
    'leftColor' => 'primary',
    'rightColor' => 'cyan-400',
    'opacity' => '100',
    'transition' => false
])

<!-- Section corner accents -->
<div class="absolute left-0 top-0 h-8 w-8 {{ $transition ? 'opacity-0 transition-opacity duration-300 group-focus-within:opacity-100' : 'opacity-'.$opacity }}">
    <div class="absolute left-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-[rgba(var(--{{ $leftColor }}-rgb),1)] via-transparent to-transparent"></div>
    <div class="absolute left-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-r from-[rgba(var(--{{ $leftColor }}-rgb),1)] via-transparent to-transparent"></div>
</div>
<div class="absolute right-0 top-0 h-8 w-8 {{ $transition ? 'opacity-0 transition-opacity duration-300 group-focus-within:opacity-100' : 'opacity-'.$opacity }}">
    <div class="absolute right-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-[rgba(var(--{{ $rightColor }}-rgb),1)] via-transparent to-transparent"></div>
    <div class="absolute right-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-l from-[rgba(var(--{{ $rightColor }}-rgb),1)] via-transparent to-transparent"></div>
</div> 