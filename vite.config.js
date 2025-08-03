import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import react from "@vitejs/plugin-react";
import { resolve } from "path";

export default defineConfig({
  plugins: [
    laravel({
      input: ["resources/css/app.css", "resources/js/react/main.tsx"],
      refresh: true,
    }),
    react({
      include: /resources\/js\/react\/.*\.(jsx|tsx)$/,
    }),
  ],
  resolve: {
    alias: {
      "@": resolve(__dirname, "resources/js/react"),
    },
    extensions: [".js", ".ts", ".jsx", ".tsx"],
  },
});
