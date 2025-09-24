import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            // Point to your React entry file
            input: 'resources/js/App.jsx',
            refresh: true,
        }),
        react(),
    ],
});
