import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    build:{
        manifest: true,
    },
    ssr: {
        noExternal: [
            //'laravel-vite-plugin',
            '@inertiajs/server',
        ],
    },
    server: {
        host: 'laravel.test',
    },
    plugins: [
        laravel({
            input: "resources/js/app.js",
            ssr: "resources/js/ssr.js",
        //    refresh: true,
        }),
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
