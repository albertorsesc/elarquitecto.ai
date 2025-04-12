@props([
    'topColor' => 'accent',
    'rightColor' => 'primary',
    'bottomColor' => 'secondary',
    'leftColor' => 'cyan-400',
    'visible' => false
])

<!-- Sliding neon lights (visible on focus) -->
<div class="pointer-events-none absolute -inset-1 {{ $visible ? '' : 'opacity-0 group-focus-within:opacity-100' }}">
    <!-- Top edge -->
    <div class="absolute left-0 top-0 h-[2px] w-full animate-neon-slide-right-slow bg-gradient-to-r from-transparent via-[rgba(var(--{{ $topColor }}-rgb),1)] to-transparent"></div>
    <!-- Right edge -->
    <div class="absolute right-0 top-0 h-full w-[2px] animate-neon-slide-down-slow bg-gradient-to-b from-transparent via-[rgba(var(--{{ $rightColor }}-rgb),1)] to-transparent"></div>
    <!-- Bottom edge -->
    <div class="absolute bottom-0 left-0 h-[2px] w-full animate-neon-slide-left-slow bg-gradient-to-r from-transparent via-[rgba(var(--{{ $bottomColor }}-rgb),1)] to-transparent"></div>
    <!-- Left edge -->
    <div class="absolute left-0 top-0 h-full w-[2px] animate-neon-slide-up-slow bg-gradient-to-b from-transparent via-[rgba(var(--{{ $leftColor }}-rgb),1)] to-transparent"></div>
</div> 