import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/react/main.tsx'
            ],
            refresh: true,
        }),
        react({
            include: "**/*.{jsx,tsx}",
        })
    ],
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources/js/react'),
            '@components': resolve(__dirname, 'resources/js/react/components'),
            '@pages': resolve(__dirname, 'resources/js/react/pages'),
            '@services': resolve(__dirname, 'resources/js/react/services'),
            '@hooks': resolve(__dirname, 'resources/js/react/hooks'),
            '@config': resolve(__dirname, 'resources/js/react/config'),
            '@utils': resolve(__dirname, 'resources/js/react/utils'),
        }
    },
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: 'localhost',
            port: 5173,
        },
        watch: {
            usePolling: true,
        },
    },
    build: {
        outDir: 'public/build',
        assetsDir: 'assets',
        manifest: true,
        sourcemap: true,
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['react', 'react-dom'],
                    ui: ['@radix-ui/react-dialog', '@radix-ui/react-dropdown-menu'],
                }
            }
        }
    }
});
