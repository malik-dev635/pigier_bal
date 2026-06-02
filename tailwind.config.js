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
                    primary: '#0A0A0B',
                    card: '#111113',
                    surface: '#17171A',
                },
                line: '#26262C',
                gold: {
                    main: '#D4A843',
                    light: '#E6C46A',
                    dark: '#7A5C18',
                },
                offwhite: '#ECECEE',
                muted: '#8A8A93',
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            borderRadius: {
                xl: '0.75rem',
            },
        },
    },
    plugins: [],
};
