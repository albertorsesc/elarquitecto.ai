@props([
    'isFooterExpanded' => false
])

<footer 
    class="fixed bottom-0 left-0 right-0 border-t border-white/10 bg-background/90 transition-all duration-300 z-10"
    x-data="{ isExpanded: {{ $isFooterExpanded ? 'true' : 'false' }} }"
    :class="{ 'h-[160px]': isExpanded, 'h-[64px]': !isExpanded }"
>
    <!-- Animated bottom border -->
    <div class="absolute inset-x-0 bottom-0 h-[1px]">
        <div class="absolute inset-0 animate-neon-slide-left bg-gradient-to-r from-transparent via-primary to-transparent opacity-30"></div>
    </div>

    <!-- Toggle button -->
    <div class="absolute -top-4 left-1/2 -translate-x-1/2 transform z-30">
        <button
            @click="isExpanded = !isExpanded"
            class="group flex h-8 w-8 items-center justify-center rounded-full bg-background border border-white/10 transition-all hover:bg-primary/20"
            :aria-expanded="isExpanded"
            aria-label="Toggle footer"
        >
            <i :class="isExpanded ? 'fa-solid fa-chevron-down' : 'fa-solid fa-chevron-up'" class="text-lg text-primary transition-transform"></i>
            <!-- Glow effect on hover -->
            <div class="absolute -inset-1 rounded-full opacity-0 transition-opacity group-hover:opacity-100">
                <x-multi-color-sliding-neon 
                    topColor="#FF1CF7" 
                    rightColor="#00FFE1" 
                    bottomColor="#01FF88" 
                    leftColor="#5B6EF7" 
                    opacity="30" 
                    :hoverEffect="true" 
                />
            </div>
        </button>
    </div>

    <!-- Footer content container - positioned from bottom -->
    <div 
        class="absolute bottom-0 left-0 right-0 w-full transition-all duration-300 backdrop-blur-sm"
        :style="{ height: isExpanded ? '160px' : '64px' }"
    >
        <!-- Expanded footer content -->
        <div x-show="isExpanded" class="mx-auto max-w-6xl px-4 h-full flex flex-col justify-center">
            <!-- Footer links with text labels -->
            <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 md:grid-cols-6">
                @php
                    $links = [
                        [
                            'title' => 'Prompts',
                            'href' => '/prompts',
                            'icon' => 'fa-regular fa-comment-dots'
                        ],
                        [
                            'title' => 'Herramientas',
                            'href' => '/tools',
                            'icon' => 'fa-solid fa-screwdriver-wrench'
                        ],
                        [
                            'title' => 'Foro',
                            'href' => '/forum',
                            'icon' => 'fa-solid fa-comments'
                        ],
                        [
                            'title' => 'Recursos',
                            'href' => '/resources',
                            'icon' => 'fa-solid fa-book'
                        ],
                        [
                            'title' => 'Comunidad',
                            'href' => '/community',
                            'icon' => 'fa-solid fa-users'
                        ]
                    ];
                @endphp

                @foreach ($links as $link)
                    <a
                        href="{{ $link['href'] }}"
                        class="group flex flex-col items-center justify-center gap-1 rounded-lg p-2 transition-all hover:bg-white/5"
                    >
                        <div class="relative flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 transition-all group-hover:bg-primary/20">
                            <i class="{{ $link['icon'] }} text-lg text-primary"></i>
                            <!-- Glow effect on hover -->
                            <div class="absolute -inset-1 rounded-full opacity-0 transition-opacity group-hover:opacity-100">
                                <x-multi-color-sliding-neon 
                                    topColor="#FF1CF7" 
                                    rightColor="#00FFE1" 
                                    bottomColor="#01FF88" 
                                    leftColor="#5B6EF7" 
                                    opacity="30" 
                                    :hoverEffect="true" 
                                />
                            </div>
                        </div>
                        <span class="text-xs text-foreground/80 transition-colors group-hover:text-primary">{{ $link['title'] }}</span>
                    </a>
                @endforeach
            </div>

            <!-- Full copyright -->
            <div class="text-center text-xs text-foreground/60 mt-4">
                <p>
                    &copy; {{ date('Y') }} El Arquitecto A.I. <br/> Democratizando I.A. para el beneficio de LATAM.
                </p>
            </div>
        </div>

        <!-- Collapsed footer with icons only -->
        <div x-show="!isExpanded" class="mx-auto max-w-3xl px-4 h-full flex flex-col justify-center">
            <div class="flex flex-col items-center">
                <!-- Icons only in collapsed state -->
                <div class="flex flex-1 justify-between w-full items-center">
                    <a href="/blog"
                        class="group relative"
                        title="Blog"
                    >
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 transition-all group-hover:bg-primary/20">
                            <i class="fa-solid fa-blog text-lg text-primary"></i>
                        </div>
                        <!-- Glow effect on hover -->
                        <div class="absolute -inset-1 rounded-full opacity-0 transition-opacity group-hover:opacity-100">
                            <x-multi-color-sliding-neon 
                                topColor="#FF1CF7" 
                                rightColor="#00FFE1" 
                                bottomColor="#01FF88" 
                                leftColor="#5B6EF7" 
                                opacity="30" 
                                :hoverEffect="true" 
                            />
                        </div>
                    </a>

                    @php
                        $links = [
                            [
                                'title' => 'Prompts',
                                'href' => '/prompts',
                                'icon' => 'fa-regular fa-comment-dots'
                            ],
                            [
                                'title' => 'Herramientas',
                                'href' => '/tools',
                                'icon' => 'fa-solid fa-screwdriver-wrench'
                            ],
                            [
                                'title' => 'Foro',
                                'href' => '/forum',
                                'icon' => 'fa-solid fa-comments'
                            ],
                            [
                                'title' => 'Recursos',
                                'href' => '/resources',
                                'icon' => 'fa-solid fa-book'
                            ],
                            [
                                'title' => 'Comunidad',
                                'href' => '/community',
                                'icon' => 'fa-solid fa-users'
                            ]
                        ];
                    @endphp
                    
                    @foreach ($links as $link)
                        <a
                            href="{{ $link['href'] }}"
                            class="group relative"
                            title="{{ $link['title'] }}"
                        >
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 transition-all group-hover:bg-primary/20">
                                <i class="{{ $link['icon'] }} text-lg text-primary"></i>
                            </div>
                            <!-- Glow effect on hover -->
                            <div class="absolute -inset-1 rounded-full opacity-0 transition-opacity group-hover:opacity-100">
                                <x-multi-color-sliding-neon 
                                    topColor="#FF1CF7" 
                                    rightColor="#00FFE1" 
                                    bottomColor="#01FF88" 
                                    leftColor="#5B6EF7" 
                                    opacity="30" 
                                    :hoverEffect="true" 
                                />
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Subtle copyright -->
                <div class="text-[10px] text-foreground/40 mt-0.5">
                    &copy; {{ date('Y') }} El Arquitecto A.I.
                </div>
            </div>
        </div>
    </div>
</footer> 