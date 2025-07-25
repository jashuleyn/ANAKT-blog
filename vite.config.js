import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/admin.js'
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
            '@css': '/resources/css',
            '@images': '/resources/images',
        },
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    'radix-ui': [
                        '@radix-ui/react-dialog',
                        '@radix-ui/react-dropdown-menu',
                        '@radix-ui/react-tabs',
                        '@radix-ui/react-toast',
                        '@radix-ui/react-tooltip',
                        '@radix-ui/react-select',
                        '@radix-ui/react-switch',
                        '@radix-ui/react-avatar',
                        '@radix-ui/react-separator'
                    ],
                    'react-vendor': ['react', 'react-dom'],
                    'utils': ['clsx', 'tailwind-merge', 'class-variance-authority']
                }
            }
        },
        chunkSizeWarningLimit: 1000,
    },
    css: {
        postcss: {
            plugins: [
                require('tailwindcss'),
                require('autoprefixer'),
            ],
        },
    },
    server: {
        hmr: {
            host: 'localhost',
        },
        watch: {
            usePolling: true,
        }
    },
    optimizeDeps: {
        include: [
            'alpinejs',
            'axios'
        ]
    }
});