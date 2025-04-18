@php
// Sections data - extracted from sections.mdc
$sections = [
  [
    'id' => 1,
    'title' => 'Blog',
    'description' => 'Artículos sobre IA, tutoriales y mejores prácticas para mantenerte actualizado.',
    'icon' => 'carbon:blog',
    'link' => '/articulos'
  ],
  [
    'id' => 2,
    'title' => 'Prompts',
    'description' => 'Colección de prompts efectivos para diferentes modelos de IA y casos de uso.',
    'icon' => 'carbon:chat',
    'link' => '/prompts'
  ],
  [
    'id' => 3,
    'title' => 'Herramientas',
    'description' => 'Las mejores herramientas de IA recomendadas para potenciar tu productividad.',
    'icon' => 'carbon:tools',
    'link' => '/tools'
  ],
  [
    'id' => 4,
    'title' => 'Foro',
    'description' => 'Únete a la conversación y comparte tus experiencias con otros entusiastas de la IA.',
    'icon' => 'carbon:forum',
    'link' => '/forum'
  ],
  [
    'id' => 5,
    'title' => 'Recursos',
    'description' => 'Guías, libros, cursos y otros recursos para profundizar en el mundo de la IA.',
    'icon' => 'carbon:document',
    'link' => '/resources'
  ],
  [
    'id' => 6,
    'title' => 'Comunidad',
    'description' => 'Conecta con otros profesionales y entusiastas de la IA en Latinoamérica.',
    'icon' => 'carbon:group',
    'link' => '/community'
  ]
];
@endphp

<div class="relative">
  <!-- Using the existing section-card component with our sections data -->
  <x-section-card :sections="$sections" />
  
  <!-- Section corner accents from Vue implementation -->
  <x-corner-accent leftColor="primary" rightColor="cyan-400" />
</div> 