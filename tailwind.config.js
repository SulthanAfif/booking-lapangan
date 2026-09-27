import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['"Space Grotesk"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                pitch: {
                    700: '#223324',
                    800: '#172518',
                    900: '#101A14',
                },
                flood: {
                    300: '#F7CB43',
                    400: '#F2B705',
                    500: '#D9A200',
                },
                turf: {
                    50: '#EEFBF1',
                    500: '#2F9E44',
                    600: '#268039',
                },
                clay: {
                    50: '#FDF0EA',
                    500: '#C1440E',
                    600: '#9E370B',
                },
                chalk: {
                    50: '#F7F6F2',
                },
                ink: {
                    900: '#14171A',
                },
            },
        },
    },

    plugins: [forms],
};
