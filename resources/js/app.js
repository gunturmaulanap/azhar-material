import "./bootstrap";
import "flowbite";
import Alpine from "alpinejs";

import "izitoast/dist/css/iziToast.min.css";
import iziToast from "izitoast";

window.Alpine = Alpine;
window.iziToast = iziToast;

// start Alpine sekali
window.__ALPINE_STARTED__ = window.__ALPINE_STARTED__ || false;
const startAlpineOnce = () => {
  if (window.__ALPINE_STARTED__) return;
  window.__ALPINE_STARTED__ = true;
  Alpine.start();
};

window.deferLoadingAlpine = (callback) => {
  document.addEventListener("livewire:load", () => callback());
};
document.addEventListener("livewire:load", startAlpineOnce);
document.addEventListener("livewire:init", startAlpineOnce);
window.addEventListener("DOMContentLoaded", startAlpineOnce);

if (!window.__IZI_CONFIGURED__) {
  window.__IZI_CONFIGURED__ = true;
  iziToast.settings({
    timeout: 4000,
    progressBar: true,
    position: "topCenter",
    transitionIn: "flipInX",
    transitionOut: "flipOutX",
  });
}

window.len = (v) => (v ? String(v).length : 0);
