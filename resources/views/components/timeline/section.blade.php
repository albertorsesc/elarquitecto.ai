@props(['items' => []])

<div class="relative pb-10">
  <!-- Timeline line -->
  <div class="timeline-line"></div>

  <!-- Timeline items -->
  <div class="timeline-items">
    @forelse($items as $index => $item)
      <x-timeline.item 
        :item="$item"
        :position="$index % 2 === 0 ? 'left' : 'right'"
      />
    @empty
      <div class="text-center py-8">No timeline items to display</div>
    @endforelse
  </div>
</div> 