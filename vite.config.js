import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';

const path = require('path')

export default defineConfig({
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '$': 'jQuery'
        }
    },
    plugins: [
        laravel({
            input: ['resources/scss/app.scss', 'resources/js/app.js', 'resources/js/form.js'],
            refresh: true,
        }),
    ],
});
