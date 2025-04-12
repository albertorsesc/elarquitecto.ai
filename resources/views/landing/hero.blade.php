<section class="relative min-h-[80vh] w-full overflow-hidden pt-20 sm:pt-10">
    <!-- Cyberpunk background with gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary/20 via-secondary/20 to-accent/20">
        <div class="cyberpunk-grid-bg absolute inset-0 opacity-20"></div>
        
        <!-- Animated neon lines -->
        <div class="hidden sm:block">
            <!-- Horizontal neon lines -->
            <div class="absolute left-0 top-1/4 h-[1px] w-1/3 animate-neon-slide-right bg-gradient-to-r from-transparent via-cyan-400 to-transparent opacity-30"></div>
            <div class="absolute right-0 top-2/3 h-[1px] w-1/3 animate-neon-slide-left bg-gradient-to-r from-transparent via-primary to-transparent opacity-30"></div>
            
            <!-- Vertical neon lines -->
            <div class="absolute left-1/4 top-0 h-1/3 w-[1px] animate-neon-slide-down bg-gradient-to-b from-transparent via-secondary to-transparent opacity-30"></div>
            <div class="absolute right-1/3 top-0 h-1/3 w-[1px] animate-neon-slide-down-delayed bg-gradient-to-b from-transparent via-accent to-transparent opacity-30"></div>
        </div>
    </div>
    
    <!-- Glass container -->
    <div class="mx-auto max-w-6xl px-4 py-12 sm:py-20 flex items-center justify-center min-h-[60vh]">
        <div class="glass-effect relative overflow-hidden rounded-2xl border border-white/10 bg-background/70 p-4 shadow-[0_0_15px_rgba(0,0,0,0.2)] backdrop-blur-xl transition-all duration-300 hover:shadow-[0_0_30px_rgba(124,58,237,0.3)] sm:p-8 w-full">
            <!-- Animated corner accents -->
            <div class="absolute left-0 top-0 h-12 w-12 animate-pulse-slow">
                <div class="absolute left-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-primary via-transparent to-transparent"></div>
                <div class="absolute left-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-r from-primary via-transparent to-transparent"></div>
            </div>
            <div class="absolute right-0 top-0 h-12 w-12 animate-pulse-slow">
                <div class="absolute right-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-cyan-400 via-transparent to-transparent"></div>
                <div class="absolute right-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-l from-cyan-400 via-transparent to-transparent"></div>
            </div>
            <div class="absolute bottom-0 left-0 h-12 w-12 animate-pulse-slow">
                <div class="absolute bottom-0 left-0 h-full w-[1px] animate-glow bg-gradient-to-t from-secondary via-transparent to-transparent"></div>
                <div class="absolute bottom-0 left-0 h-[1px] w-full animate-glow bg-gradient-to-r from-secondary via-transparent to-transparent"></div>
            </div>
            <div class="absolute bottom-0 right-0 h-12 w-12 animate-pulse-slow">
                <div class="absolute bottom-0 right-0 h-full w-[1px] animate-glow bg-gradient-to-t from-accent via-transparent to-transparent"></div>
                <div class="absolute bottom-0 right-0 h-[1px] w-full animate-glow bg-gradient-to-l from-accent via-transparent to-transparent"></div>
            </div>
            
            <!-- Background Logo -->
            <div
            class="absolute inset-0 z-0 opacity-10"
            style="
                background-image: url('{{ asset('img/logo.png') }}');
                background-size: 150%;
                background-position: center;
                background-repeat: no-repeat;
                filter: blur(2px) brightness(1.2)
            "
            ></div>
            
            <div class="relative z-10 mx-auto max-w-3xl">
                <h1 class="mb-4 text-center text-3xl font-bold sm:mb-6 sm:text-4xl md:text-6xl">
                    <span class="mr-2 text-primary animate-text-glow">El Arquitecto</span>
                    <span class="text-cyan-400 animate-text-glow-delayed">A.I.</span>
                </h1>
                <p class="text-center text-lg text-foreground/90 sm:text-xl md:text-2xl">
                    Democtratizando I.A. para el beneficio de Latinoamérica
                </p>
                
                <!-- Subscription form -->
                <form class="mt-8 flex justify-center sm:mt-10">
                    <div class="group relative w-full max-w-2xl">
                        <input
                        v-model="form.email"
                        type="email"
                        placeholder="Tu correo electrónico"
                        class="relative z-10 w-full rounded-xl border border-white/10 bg-background/50 py-3 pl-4 pr-36 text-sm text-foreground placeholder:text-foreground/50 focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300"
                        :class="{ 'border-red-500': form.errors.email }"
                        required
                        />
                        <button
                        type="submit"
                        class="absolute right-2 top-1/2 z-10 -translate-y-1/2 rounded-lg bg-primary px-4 py-1.5 text-sm font-semibold text-white transition-all hover:bg-primary/80 hover:shadow-[0_0_20px_rgba(124,58,237,0.5)]"
                        :disabled="isSubmitting"
                        >
                        {{-- <span>Enviando...</span> --}}
                        <span v-else>Suscribirse</span>
                    </button>
                    
                    <!-- Error message -->
                    {{-- <div v-if="form.errors.email" class="absolute -bottom-6 left-0 text-sm text-red-400">
                        {{ form.errors.email }}
                    </div> --}}
                    
                    <!-- Animated border effect -->
                    <div class="absolute bottom-0 left-0 h-[1px] w-0 bg-gradient-to-r from-primary via-cyan-400 to-secondary transition-all duration-300 group-focus-within:w-full"></div>
                    
                    <!-- Sliding neon lights -->
                    <x-sliding-neon topColor="primary" rightColor="secondary" bottomColor="accent" leftColor="cyan-400" />
                    
                    <!-- Corner accents -->
                    <x-corner-accent leftColor="primary" rightColor="cyan-400" transition="true" />
                </div>
            </form>
            
            <!-- Cyberpunk-styled buttons -->
            <!--          <div class="mt-6 flex flex-wrap justify-center gap-3 sm:mt-8 sm:gap-4">
                <button class="neon-border group relative rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-white transition-all hover:bg-primary/80 hover:shadow-[0_0_20px_rgba(124,58,237,0.5)] sm:px-6 sm:py-3">
                    <span class="relative z-10">Explorar</span>
                    <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-primary via-secondary to-accent opacity-0 transition-opacity duration-300 group-hover:opacity-20"></div>
                </button>
                <button class="group relative rounded-xl border border-primary/50 bg-transparent px-4 py-2 text-sm font-semibold text-primary transition-all hover:border-primary hover:bg-primary/10 hover:shadow-[0_0_20px_rgba(124,58,237,0.3)] sm:px-6 sm:py-3">
                    <span class="relative z-10">Más Info</span>
                    <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-primary/20 via-secondary/20 to-accent/20 opacity-0 transition-opacity duration-300 group-hover:opacity-20"></div>
                </button>
            </div>-->
        </div>
    </div>
</div>

<!-- Enhanced decorative elements -->
<div class="absolute bottom-0 left-0 h-32 w-32 rotate-45 animate-pulse-slow bg-accent/20 blur-3xl"></div>
<div class="absolute right-0 top-0 h-32 w-32 rotate-45 animate-pulse-slow bg-secondary/20 blur-3xl"></div>
<div 
    class="absolute h-48 w-48 z-0 pointer-events-none blur-sm"
    style="
        mask-image: radial-gradient(circle at center, black 100%, transparent 100%);
        -webkit-mask-image: radial-gradient(circle at center, black 100%, transparent 100%);
        animation: wall-bounce 25s infinite linear;
        top: 10%;
        left: 10%;
    "
>
    <div
        class="w-full h-full rounded-full"
        style="
            background-image: url('{{ asset('img/logo.png') }}');
            background-size: 70% 70%;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.15;
            background-color: rgba(124, 58, 237, 0.03);
            filter: drop-shadow(0 0 5px rgba(124, 58, 237, 0.2));
        "
    ></div>
</div>

<style>
@keyframes wall-bounce {
    0% {
        transform: translate(0%, 0%);
    }
    12.5% {
        transform: translate(calc(80vw - 48px), calc(40vh - 48px));
    }
    25% {
        transform: translate(0%, calc(60vh - 48px));
    }
    37.5% {
        transform: translate(calc(70vw - 48px), 0%);
    }
    50% {
        transform: translate(calc(20vw - 48px), calc(70vh - 48px));
    }
    62.5% {
        transform: translate(calc(80vw - 48px), calc(30vh - 48px));
    }
    75% {
        transform: translate(calc(10vw - 48px), calc(50vh - 48px));
    }
    87.5% {
        transform: translate(calc(60vw - 48px), calc(10vh - 48px));
    }
    100% {
        transform: translate(0%, 0%);
    }
}
</style>
</section>