import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  //     base: '/build/', // ou o caminho correto
  // server: {
  //   https: true
  // },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    //  server: {
    //     host: '0.0.0.0',
    //     port: 5173,
    //     hmr: {
    //         host: process.env.VITE_ASSET_URL?.replace(/^https?:\/\//, ''),
    //     },
    // },
    // base: process.env.VITE_ASSET_URL ? process.env.VITE_ASSET_URL + '/' : '/',
});
