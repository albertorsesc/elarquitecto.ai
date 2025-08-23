<!-- Cookie Consent Banner -->
<div x-data="{ 
    show: !localStorage.getItem('cookie-consent-choice'),
    animate: true,
    choice: null,
    accept() {
        this.choice = 'accepted';
        this.saveChoice('accepted');
    },
    reject() {
        this.choice = 'rejected';
        this.saveChoice('rejected');
    },
    saveChoice(choice) {
        this.animate = false;
        setTimeout(() => {
            localStorage.setItem('cookie-consent-choice', choice);
            this.show = false;
            window.dispatchEvent(new CustomEvent('cookie-consent-updated', { detail: { choice } }));
        }, 500);
    }
}" 
    x-show="show" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-10"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-10"
    class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-2xl glass-effect rounded-xl overflow-hidden shadow-lg"
    :class="{ 'neon-border': animate }">
    
    <!-- Animated Border -->
    <div class="neon-border-wrapper" x-show="animate">
        <div class="absolute inset-x-0 top-0 h-[1px] bg-gradient-to-r from-primary via-secondary to-accent animate-neon-slide-right"></div>
        <div class="absolute inset-x-0 bottom-0 h-[1px] bg-gradient-to-l from-primary via-secondary to-accent animate-neon-slide-left"></div>
        <div class="absolute inset-y-0 left-0 w-[1px] bg-gradient-to-b from-primary via-secondary to-accent animate-neon-slide-down"></div>
        <div class="absolute inset-y-0 right-0 w-[1px] bg-gradient-to-t from-primary via-secondary to-accent animate-neon-slide-up"></div>
    </div>
    
    <!-- Scanline effect -->
    <div class="absolute inset-0 pointer-events-none bg-scanline"></div>
    
    <!-- Content -->
    <div class="p-4 md:p-6 flex flex-col md:flex-row md:items-center gap-4">
        <div class="flex-1">
            <div class="flex items-center gap-3 mb-2">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-primary/40 bg-primary/10 text-primary animate-pulse-slow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5" />
                        <path d="M8.5 8.5v.01" />
                        <path d="M16 15.5v.01" />
                        <path d="M12 12v.01" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold animate-text-glow">Consentimiento de Cookies</h3>
            </div>
            <p class="text-muted-foreground text-sm">
                Este sitio web utiliza cookies para mejorar tu experiencia. Elige aceptar o rechazar las cookies para este sitio.
            </p>
        </div>
        <div class="flex gap-3 md:flex-col lg:flex-row">
            <button 
                @click="reject()"
                class="px-4 py-2 text-sm font-medium transition-all rounded-lg border border-primary/30 bg-background/90 hover:bg-primary/10 hover:shadow-[0_0_10px_rgba(var(--primary-rgb),0.5)] text-foreground"
                :class="{ 'cyber-button-container': animate }">
                Rechazar Todas
            </button>
            <button 
                @click="accept()"
                class="px-4 py-2 text-sm font-medium transition-all rounded-lg border border-primary/30 bg-primary/80 hover:bg-primary text-primary-foreground hover:shadow-[0_0_15px_rgba(var(--primary-rgb),0.7)]"
                :class="{ 'cyber-button-container': animate }">
                Aceptar Todas
            </button>
        </div>
    </div>
    
    <!-- Glitch lines -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="h-[1px] w-full bg-primary/30 absolute top-[30%] left-0 animate-glitch-line-1"></div>
        <div class="h-[1px] w-full bg-secondary/30 absolute top-[60%] left-0 animate-glitch-line-2"></div>
        <div class="h-[1px] w-full bg-accent/30 absolute top-[85%] left-0 animate-glitch-line-3"></div>
    </div>
</div> 