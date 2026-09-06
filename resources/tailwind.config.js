import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                ink: '#0F2A4D',
                paper: '#F5F8FC',
                amber: '#60A5FA',
                teal: '#2563EB',
                coral: '#1D4ED8',
                line: '#DCE6F5',
            },
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
                display: ['Fraunces', 'serif'],
                mono: ['IBM Plex Mono', 'monospace'],
            },
        },
    },

    plugins: [forms],
};