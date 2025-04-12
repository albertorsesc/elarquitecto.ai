@props(['item', 'position' => 'left'])

<div class="timeline-item {{ $position === 'left' ? 'timeline-item-left' : 'timeline-item-right' }}">
  <div class="timeline-content">
    @if(isset($item['title']))
      <h3 class="timeline-title">{{ $item['title'] }}</h3>
    @endif
    
    @if(isset($item['date']))
      <div class="timeline-date">{{ $item['date'] }}</div>
    @endif
    
    @if(isset($item['content']))
      <div class="timeline-body">{{ $item['content'] }}</div>
    @endif
    
    @if(isset($item['image']))
      <div class="timeline-image">
        <img src="{{ $item['image'] }}" alt="{{ $item['title'] ?? 'Timeline image' }}">
      </div>
    @endif
  </div>
</div> 