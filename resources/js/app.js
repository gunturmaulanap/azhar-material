// resources/js/app.js
import "./bootstrap";
import "flowbite";
import Alpine from "alpinejs";

window.Alpine = Alpine;

// Start Alpine sekali saja.
// Jika halaman memakai Livewire v2, tunggu 'livewire:load'.
// Jika tidak, start saat DOM siap.
const startAlpineOnce = () => {
  if (window.__ALPINE_STARTED__) return; // guard agar tidak double-start
  window.__ALPINE_STARTED__ = true;
  Alpine.start();
};

if (window.Livewire) {
  document.addEventListener("livewire:load", startAlpineOnce);
} else {
  window.addEventListener("DOMContentLoaded", startAlpineOnce);
}

// (Opsional) helper aman untuk ekspresi Alpine di Blade
window.len = (v) => (v ? String(v).length : 0);
