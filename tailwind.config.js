import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: ['class'],
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.{vue,js,ts,jsx,tsx}',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Instrument Sans', ...defaultTheme.fontFamily.sans],
            },
            keyframes: {
                'neon-slide-right': {
                    '0%': { transform: 'translateX(-100%)' },
                    '100%': { transform: 'translateX(100%)' }
                },
                'neon-slide-left': {
                    '0%': { transform: 'translateX(100%)' },
                    '100%': { transform: 'translateX(-100%)' }
                },
                'neon-slide-down': {
                    '0%': { transform: 'translateY(-100%)' },
                    '100%': { transform: 'translateY(100%)' }
                },
                'neon-slide-down-delayed': {
                    '0%': { transform: 'translateY(-100%)' },
                    '100%': { transform: 'translateY(100%)' }
                },
                'neon-slide-up': {
                    '0%': { transform: 'translateY(100%)' },
                    '100%': { transform: 'translateY(-100%)' }
                },
                'pulse': {
                    '0%, 100%': { opacity: '1' },
                    '50%': { opacity: '0.5' }
                },
                'pulse-slow': {
                    '0%, 100%': { opacity: '0.7' },
                    '50%': { opacity: '0.3' }
                },
                'glitch-line-1': {
                    '0%, 100%': { transform: 'translateX(-100%)' },
                    '50%': { transform: 'translateX(100%)' }
                },
                'glitch-line-2': {
                    '0%, 100%': { transform: 'translateX(100%)' },
                    '50%': { transform: 'translateX(-100%)' }
                },
                'glitch-line-3': {
                    '0%, 100%': { transform: 'translateX(-50%)' },
                    '50%': { transform: 'translateX(50%)' }
                },
                'glow': {
                    '0%, 100%': { opacity: '0.3' },
                    '50%': { opacity: '0.8' }
                },
                'text-glow': {
                    '0%, 100%': { 
                        textShadow: '0 0 5px rgba(var(--primary-rgb), 0.4)' 
                    },
                    '50%': { 
                        textShadow: '0 0 15px rgba(var(--primary-rgb), 0.8)' 
                    }
                },
                'text-glow-delayed': {
                    '0%, 100%': { 
                        textShadow: '0 0 3px rgba(var(--accent-rgb), 0.3)' 
                    },
                    '50%': { 
                        textShadow: '0 0 10px rgba(var(--accent-rgb), 0.6)' 
                    }
                },
                'float': {
                    '0%, 100%': { 
                        transform: 'translateY(0)' 
                    },
                    '50%': { 
                        transform: 'translateY(-10px)' 
                    }
                }
            },
            animation: {
                'neon-slide-right': 'neon-slide-right 3s linear infinite',
                'neon-slide-left': 'neon-slide-left 3s linear infinite',
                'neon-slide-down': 'neon-slide-down 3s linear infinite',
                'neon-slide-down-delayed': 'neon-slide-down 4s linear infinite 1s',
                'neon-slide-up': 'neon-slide-up 3s linear infinite',
                'neon-slide-right-slow': 'neon-slide-right 8s linear infinite',
                'neon-slide-left-slow': 'neon-slide-left 8s linear infinite',
                'neon-slide-down-slow': 'neon-slide-down 8s linear infinite',
                'neon-slide-up-slow': 'neon-slide-up 8s linear infinite',
                'pulse': 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'pulse-slow': 'pulse-slow 4s ease-in-out infinite',
                'glitch-line-1': 'glitch-line-1 3s linear infinite',
                'glitch-line-2': 'glitch-line-2 4s linear infinite',
                'glitch-line-3': 'glitch-line-3 2.5s linear infinite',
                'glow': 'glow 3s infinite',
                'text-glow': 'text-glow 3s ease-in-out infinite',
                'text-glow-delayed': 'text-glow-delayed 3s ease-in-out infinite 1.5s',
                'float': 'float 5s ease-in-out infinite'
            },
            textShadow: {
                sm: '0 0 2px rgba(124,58,237,0.3)',
                DEFAULT: '0 0 4px rgba(124,58,237,0.4)',
                lg: '0 0 8px rgba(124,58,237,0.5)',
            },
            borderRadius: {
                lg: 'var(--radius)',
                md: 'calc(var(--radius) - 2px)',
                sm: 'calc(var(--radius) - 4px)',
            },
            colors: {
                background: 'hsl(var(--background))',
                foreground: 'hsl(var(--foreground))',
                card: {
                    DEFAULT: 'hsl(var(--card))',
                    foreground: 'hsl(var(--card-foreground))',
                },
                popover: {
                    DEFAULT: 'hsl(var(--popover))',
                    foreground: 'hsl(var(--popover-foreground))',
                },
                primary: {
                    DEFAULT: 'hsl(var(--primary))',
                    foreground: 'hsl(var(--primary-foreground))',
                },
                secondary: {
                    DEFAULT: 'hsl(var(--secondary))',
                    foreground: 'hsl(var(--secondary-foreground))',
                },
                muted: {
                    DEFAULT: 'hsl(var(--muted))',
                    foreground: 'hsl(var(--muted-foreground))',
                },
                accent: {
                    DEFAULT: 'hsl(var(--accent))',
                    foreground: 'hsl(var(--accent-foreground))',
                },
                destructive: {
                    DEFAULT: 'hsl(var(--destructive))',
                    foreground: 'hsl(var(--destructive-foreground))',
                },
                border: 'hsl(var(--border))',
                input: 'hsl(var(--input))',
                ring: 'hsl(var(--ring))',
                chart: {
                    1: 'hsl(var(--chart-1))',
                    2: 'hsl(var(--chart-2))',
                    3: 'hsl(var(--chart-3))',
                    4: 'hsl(var(--chart-4))',
                    5: 'hsl(var(--chart-5))',
                },
                sidebar: {
                    DEFAULT: 'hsl(var(--sidebar-background))',
                    foreground: 'hsl(var(--sidebar-foreground))',
                    primary: 'hsl(var(--sidebar-primary))',
                    'primary-foreground': 'hsl(var(--sidebar-primary-foreground))',
                    accent: 'hsl(var(--sidebar-accent))',
                    'accent-foreground': 'hsl(var(--sidebar-accent-foreground))',
                    border: 'hsl(var(--sidebar-border))',
                    ring: 'hsl(var(--sidebar-ring))',
                },
            },
        },
    },
    plugins: [
        require('tailwindcss-animate'),
        function({ addUtilities, theme, e }) {
            const utilities = {
                '.text-shadow': {
                    textShadow: theme('textShadow.DEFAULT'),
                },
                '.text-shadow-sm': {
                    textShadow: theme('textShadow.sm'),
                },
                '.text-shadow-lg': {
                    textShadow: theme('textShadow.lg'),
                },
                '.text-shadow-none': {
                    textShadow: 'none',
                },
                '.text-glow': {
                    textShadow: '0 0 5px rgba(var(--primary) / 0.4)',
                },
                '.text-glow-light': {
                    textShadow: '0 0 3px rgba(var(--accent) / 0.3)',
                },
            }
            addUtilities(utilities)
        }
    ],
};
