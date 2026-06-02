import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './app/Livewire/**/*.php',
        './app/View/**/*.php',
    ],
    theme: {
        extend: {
            colors: {
                bg: {
                    primary: '#050608',
                    card: '#0D0D0F',
                    surface: '#141418',
                },
                gold: {
                    main: '#D4A843',
                    light: '#F0CC6E',
                    dark: '#7A5C18',
                    bright: '#FAE08A',
                },
                offwhite: '#F5EDD6',
                muted: '#7A7A8A',
            },
            fontFamily: {
                serif: ['Georgia', ...defaultTheme.fontFamily.serif],
                display: ['"Cinzel"', '"Playfair Display"', 'Georgia', 'serif'],
                sans: ['"Inter"', 'Calibri', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                gold: '0 0 20px rgba(212, 168, 67, 0.19)',
                'gold-lg': '0 0 35px rgba(212, 168, 67, 0.28)',
            },
            keyframes: {
                'fade-slide-up': {
                    '0%': { opacity: '0', transform: 'translateY(12px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'fade-in': {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
            },
            animation: {
                'fade-slide-up': 'fade-slide-up 0.2s ease both',
                'fade-in': 'fade-in 0.2s ease both',
            },
        },
    },
    plugins: [],
};
