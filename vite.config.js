import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import react from "@vitejs/plugin-react";
import { resolve } from "path";

export default defineConfig({
  plugins: [
    laravel({
      input: [
        "resources/css/app.css",
        "resources/js/app.js",
        "resources/js/react/main.tsx",
      ],
      refresh: true,
    }),
    react(),
  ],
  resolve: {
    alias: {
      "@": resolve(__dirname, "resources/js/react"),
      "@components": resolve(__dirname, "resources/js/react/components"),
      "@pages": resolve(__dirname, "resources/js/react/pages"),
      "@services": resolve(__dirname, "resources/js/react/services"),
      "@hooks": resolve(__dirname, "resources/js/react/hooks"),
      "@config": resolve(__dirname, "resources/js/react/config"),
      "@utils": resolve(__dirname, "resources/js/react/utils"),
    },
    extensions: [".js", ".ts", ".jsx", ".tsx"],
  },
  define: {
    global: "globalThis",
  },
  server: {
    host: "localhost",
    port: 5173,
    hmr: {
      host: "localhost",
    },
  },
  build: {
    outDir: "public/build",
    manifest: true,
    sourcemap: true,
  },
});
