import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import { bunny } from "laravel-vite-plugin/fonts";
import tailwindcss from "@tailwindcss/vite";
import vue from "@vitejs/plugin-vue";
import inertia from "@inertiajs/vite";
import { fileURLToPath, URL } from "url"; //novo

const LOCAL_IP = "192.168.18.16";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
            fonts: [
                bunny("Instrument Sans", {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
        vue(),
        inertia({
            ssr: false,
        }),
    ],
    resolve: {
        alias: [
            {
                find: "@",
                replacement: fileURLToPath(
                    new URL("./resources/Pages", import.meta.url),
                ),
            },
            {
                find: "@js",
                replacement: fileURLToPath(
                    new URL("./resources/js", import.meta.url),
                ),
            },
        ],
    },
    server: {
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },
        host: "0.0.0.0",
        port: 5173,
        strictPort: true,
        origin: `http://${LOCAL_IP}:5173`, // <- força a URL correta dos assets
        cors:true,
        hmr: {
            host: "192.168.18.16",
        },
    },
});
