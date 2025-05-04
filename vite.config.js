import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.jsx', // Có thể vẫn giữ lại cho các import chung
                'resources/js/forgot-password.js',
                'resources/js/reset-password.js',
            ],
            refresh: true,
        }),
        react(),
    ],
    build: {
        outDir: 'public/build',
    },
});