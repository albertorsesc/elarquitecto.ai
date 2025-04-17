@props(['item', 'position' => 'left'])

<div class="h-full">
  <!-- Prompt Card with cyberpunk styling -->
  <div class="group h-full overflow-hidden rounded-xl border border-border/50 glass-effect neon-border transition-all duration-300 hover:scale-[1.02] hover:shadow-[0_0_15px_rgba(var(--primary-rgb),0.5)]">
    
    <!-- Card Image -->
    <div class="relative h-40 w-full overflow-hidden">
      <img src="{{ $item['image'] ?? '/img/logo.webp' }}" alt="{{ $item['title'] ?? 'Prompt' }}"
          class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
          loading="lazy" />
      
      <!-- Category Badge -->
      <div class="absolute top-3 right-3 bg-black/50 backdrop-blur-sm text-xs font-medium px-2 py-1 rounded-full border border-primary/30 text-primary animate-pulse-slow">
        {{ ucfirst($item['type'] ?? 'Prompt') }}
      </div>
    </div>
    
    <!-- Card Content -->
    <div class="flex flex-col gap-2 p-4">
      <h3 class="text-lg font-semibold line-clamp-1 group-hover:text-primary transition-colors">
        {{ $item['title'] ?? 'Untitled' }}
      </h3>
      
      <p class="text-sm text-muted-foreground line-clamp-2">
        {{ $item['excerpt'] ?? $item['content'] ?? 'No description available' }}
      </p>
      
      <!-- Tags (if available) -->
      @if(isset($item['model']) && isset($item['model']['tags']) && count($item['model']['tags']) > 0)
        <div class="flex flex-wrap gap-1 mt-1">
          @foreach($item['model']['tags'] as $tag)
            <span class="text-xs px-2 py-0.5 rounded-full bg-sidebar-accent text-sidebar-accent-foreground">
              {{ $tag['name'] }}
            </span>
          @endforeach
        </div>
      @endif
      
      <!-- Footer -->
      <div class="mt-3 flex items-center justify-between text-xs text-muted-foreground">
        <span class="flex items-center gap-1">
          <span>{{ $item['word_count'] ?? 0 }} words</span>
        </span>
        <span>{{ $item['date'] ?? date('M d, Y') }}</span>
      </div>

      <!-- View Link -->
      <div class="mt-3 pt-3 border-t border-border/30">
        <a href="{{ $item['url'] ?? '/' }}" 
           class="block w-full text-center py-1.5 px-3 text-sm rounded-md border border-primary/30 bg-primary/10 hover:bg-primary/20 text-primary transition-colors">
          View Details
        </a>
      </div>
    </div>
  </div>
</div> 