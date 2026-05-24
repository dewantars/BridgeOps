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
            },
        },
    },

    // Safelist dynamic color classes used in Blade templates
    safelist: [
        // Risk level colors
        { pattern: /bg-(emerald|amber|orange|red|blue|purple|indigo|gray)-(400|500|600|700|800|900)\/(10|20|30)/ },
        { pattern: /text-(emerald|amber|orange|red|blue|purple|indigo|gray)-(300|400|500|600)/ },
        { pattern: /border-(emerald|amber|orange|red|blue|purple|indigo|gray)-(500|600|700|800|900)\/(30|40|50)/ },
        { pattern: /bg-(emerald|amber|orange|red|blue|purple|indigo|gray)-(400|500|600)/ },
        // Peer-checked states for radio buttons (use arbitrary values in templates instead)
    ],

    plugins: [forms],
};
