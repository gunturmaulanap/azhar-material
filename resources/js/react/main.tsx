import React from "react";
import { createRoot } from "react-dom/client";
import "./bootstrap";
import App from "./App";

const mountEl =
  document.getElementById("app") || document.getElementById("root");

if (!mountEl) {
  // Jika div mount tidak ditemukan, log agar mudah dilacak di devtools.
  console.error("Mount node not found: expected #app or #root");
} else {
  // Cegah ekstensi/Google Translate menginjeksi span di dalam tree React.
  try {
    document.documentElement.setAttribute("translate", "no");
    document.documentElement.classList.add("notranslate");
    mountEl.setAttribute("translate", "no");
    mountEl.classList.add("notranslate");
  } catch {
    // noop
  }

  const isDev = (import.meta as any)?.env?.MODE !== "production";
  const root = createRoot(mountEl);

  root.render(
    isDev ? (
      <React.StrictMode>
        <App />
      </React.StrictMode>
    ) : (
      <App />
    )
  );

  // Sembunyikan konten fallback setelah React berhasil mount
  const fallback = document.getElementById("fallback-content");
  if (fallback) fallback.style.display = "none";
}
