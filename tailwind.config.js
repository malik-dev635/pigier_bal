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
        // Angles nets partout — aucun arrondi.
        borderRadius: {
            none: '0',
            sm: '0',
            DEFAULT: '0',
            md: '0',
            lg: '0',
            xl: '0',
            '2xl': '0',
            '3xl': '0',
            full: '0',
        },
        extend: {
            colors: {
                bg: {
                    primary: '#0A0A0B',
                    card: '#101012',
                    surface: '#161619',
                },
                line: '#23232A',
                gold: {
                    main: '#C9A24B',
                    light: '#E3C878',
                    dark: '#6E5418',
                },
                offwhite: '#ECECEE',
                muted: '#86868F',
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['"Cormorant Garamond"', 'Georgia', 'serif'],
            },
        },
    },
    plugins: [],
};
