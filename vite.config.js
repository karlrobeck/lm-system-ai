import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import solid from 'vite-plugin-solid';	
import path from "path";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/entry-client.tsx'],
            refresh: true,
        }),
        solid()
    ],
    resolve: {
        alias: {
            "~": path.resolve(__dirname, "./resources/js"),
        }
    }
});
