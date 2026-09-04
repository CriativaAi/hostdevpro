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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    olive: '#5F6F52',
                    laurel: '#A9B388',
                    cornsilk: '#FEFAE0',
                    lemon: '#F9EBC7',
                    camel: '#B99470',
                    orange: '#C4661F',
                    russet: '#783D19',
                    dark: '#1c2417',
                }
            }
        },
    },

    plugins: [forms],
};
