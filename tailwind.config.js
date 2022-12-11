/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [],
    theme: {
        extend: {},
    },
    plugins: [],
    purge: [
        './resources/js/**/*.{vue,js}',
        './resources/views/**/*.blade.php'
    ],
}
