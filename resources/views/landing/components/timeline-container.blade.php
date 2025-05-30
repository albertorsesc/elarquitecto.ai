{{-- The variables $items and $withScrollbar are passed from the include statement --}}

<div class="relative w-full px-4">
    <!-- Background effect -->
    <div class="absolute inset-0 bg-gradient-to-t from-background/50 to-transparent">
      <div class="cyberpunk-grid-bg absolute inset-0 opacity-10"></div>
    </div>

    @if($withScrollbar ?? false)
      <!-- Timeline wrapper with fixed height and scrolling -->
      <x-neon-scrollbar height="500px">
        <x-timeline.section :items="$items" />
      </x-neon-scrollbar>
    @else
      <x-timeline.section :items="$items" />
    @endif
  </div>