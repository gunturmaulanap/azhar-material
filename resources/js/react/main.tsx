import React from "react";
import ReactDOM from "react-dom/client";
import "./bootstrap"; // Import React-specific axios configuration
import App from "./App"; // pastikan path sesuai

const root = document.getElementById("app");
if (root) {
  ReactDOM.createRoot(root).render(<App />);
  // Hide fallback content once React app mounts
  const fallback = document.getElementById("fallback-content");
  if (fallback) {
    fallback.style.display = "none";
  }
}
