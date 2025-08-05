import React from "react";
import ReactDOM from "react-dom/client";
import App from "./App"; // pastikan path sesuai

const root = document.getElementById("app");
if (root) {
  ReactDOM.createRoot(root).render(<App />);
}
