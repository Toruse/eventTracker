import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy'
import path from 'path'

const filesWithoutHash = [
    "resources/js/track.js",
];

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/track.js'],
            refresh: true,
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
