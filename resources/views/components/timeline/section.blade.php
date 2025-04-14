@props(['items' => []])

<div class="w-full">
  <!-- Cards grid layout with spacing -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($items as $index => $item)
      <x-timeline.item 
        :item="$item"
        :position="$index % 2 === 0 ? 'left' : 'right'"
      />
    @empty
      <div class="col-span-full flex flex-col items-center justify-center gap-4 p-8 text-center">
        <div class="rounded-full p-3 bg-muted">
          <div class="h-10 w-10 text-muted-foreground"></div>
        </div>
        <div class="space-y-2">
          <h3 class="text-xl font-semibold">No hay elementos para mostrar</h3>
          <p class="text-muted-foreground">Pronto se añadirán nuevos elementos.</p>
        </div>
      </div>
    @endforelse
  </div>
</div> 