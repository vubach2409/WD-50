import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
<<<<<<< HEAD
import react from '@vitejs/plugin-react';
=======
>>>>>>> 3d6d0c78435cd13ac3db14b2a9e3384a04296e23

export default defineConfig({
    plugins: [
        laravel({
            input: [
<<<<<<< HEAD
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
=======
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
>>>>>>> 3d6d0c78435cd13ac3db14b2a9e3384a04296e23
