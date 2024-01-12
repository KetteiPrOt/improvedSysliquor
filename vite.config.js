import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/components/InvoiceNumberInput/styles.css',
                'resources/js/components/InvoiceNumberInput/InvoiceNumber.js',
                'resources/js/components/InvoiceNumberInput/main.js',
            ],
            refresh: true,
        }),
    ],
});
