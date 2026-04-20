import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: 5174,
        strictPort: true,
        hmr: {
            host: 'localhost',
        },
    },
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            // ssr: 'resources/js/ssr.ts', // Disabled SSR for now
            refresh: true,
        }),
        tailwindcss(),
        // wayfinder({
        //     formVariants: true,
        // }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
