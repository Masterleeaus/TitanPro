import vue from '@vitejs/plugin-vue';
import { resolve } from 'path';
import { defineConfig } from 'vitest/config';

export default defineConfig({
    plugins: [vue()],
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources/js'),
        },
    },
    test: {
        environment: 'happy-dom',
        globals: true,
        include: ['resources/js/**/*.spec.ts'],
    },
});
