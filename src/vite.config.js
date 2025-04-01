import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { viteStaticCopy } from 'vite-plugin-static-copy'
import path from 'path'

const filesWithoutHash = [
    "resources/js/track.js",
];

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js', 'resources/js/track.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        viteStaticCopy({
            targets: [
                {
                    src: path.resolve(__dirname, './public/build/assets/track.js'),
                    dest: path.resolve(__dirname, './public/js')
                }
            ]
        })
    ],
    server: {
        https: false,
        host: true,
        strictPort: true,
        port: 3009,
        hmr: {host: 'localhost', protocol: 'ws'},
        watch: {
            usePolling:true,
        }
    },
    build: {
        rollupOptions: {
            output: {
                entryFileNames: (chunkInfo) => {
                    return filesWithoutHash.some(item => chunkInfo.facadeModuleId.endsWith(item))
                        ? `assets/[name].js`
                        : `assets/[name]-[hash].js`;
                },
            },
        },
    },
});
