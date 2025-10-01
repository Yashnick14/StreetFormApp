import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/admin/products.js",
                "resources/js/customer/wishlist.js",
                "resources/js/customer/order.js",
                "resources/js/customer/cart.js",
            ],
            refresh: true,
        }),
    ],
});
