// resources/js/react/services/api.js
import axios from "axios";
import Cookies from "js-cookie";
import { apiConfig, getCSRFToken, endpoints } from "../config/api";

// --- Global axios XSRF setup (untuk semua instance) ---
axios.defaults.withCredentials = true;
axios.defaults.xsrfCookieName = "XSRF-TOKEN";
axios.defaults.xsrfHeaderName = "X-XSRF-TOKEN";

// --- Instance utama untuk endpoint /api ---
const api = axios.create(apiConfig);
api.defaults.withCredentials = true;

// --- Interceptor REQUEST ---
api.interceptors.request.use(
  async (config) => {
    // Pastikan cookie XSRF tersedia untuk method yang mengubah state
    const method = (config.method || "").toLowerCase();
    if (["post", "put", "patch", "delete"].includes(method)) {
      try {
        // PENTING: pukul endpoint ROOT (bukan /api) agar Sanctum set cookie domain dengan benar
        await axios.get("/sanctum/csrf-cookie", {
          withCredentials: true,
          timeout: 3000,
        });
      } catch (_) {
        // abaikan; header fallback tetap dicoba
      }
    }

    // Sisipkan CSRF header (fallback dari <meta> atau cookie)
    const csrfToken = getCSRFToken() || Cookies.get("XSRF-TOKEN");
    if (csrfToken) config.headers["X-CSRF-TOKEN"] = csrfToken;

    // Sisipkan Bearer token jika ada
    const authToken = Cookies.get("token");
    if (authToken) config.headers["Authorization"] = `Bearer ${authToken}`;

    return config;
  },
  (error) => Promise.reject(error)
);

// --- Interceptor RESPONSE ---
// Auto retry sekali jika 419 (CSRF mismatch)
api.interceptors.response.use(
  (res) => res,
  async (error) => {
    const status = error?.response?.status;
    const original = error?.config;

    // 401 → buang token
    if (status === 401) {
      Cookies.remove("token");
    }

    // 419 (CSRF mismatch) → coba refresh cookie & ulangi sekali
    if (status === 419 && original && !original._retried) {
      try {
        original._retried = true;
        await axios.get("/sanctum/csrf-cookie", { withCredentials: true });
        const csrfToken = getCSRFToken() || Cookies.get("XSRF-TOKEN");
        if (csrfToken) {
          original.headers = original.headers || {};
          original.headers["X-CSRF-TOKEN"] = csrfToken;
        }
        return api(original);
      } catch (_) {
        // jatuhkan ke caller
      }
    }

    return Promise.reject(error);
  }
);

// ===== Services =====
export const authService = {
  // pakai axios root untuk set cookie XSRF dari Sanctum
  ensureCsrf: () =>
    axios.get("/sanctum/csrf-cookie", { withCredentials: true }),
  login: (payload, cfg = {}) => api.post(endpoints.login, payload, cfg),
  logout: (cfg = {}) => api.post(endpoints.logout, null, cfg),
  getUser: (cfg = {}) => api.get(endpoints.user, cfg),
  register: (payload, cfg = {}) => api.post(endpoints.register, payload, cfg),
};

export const productService = {
  // Tambahkan argumen cfg agar bisa mengirim { signal } dari AbortController
  getAll: (params = {}, cfg = {}) =>
    api.get(endpoints.products, { params, ...cfg }),
  getById: (id, cfg = {}) => api.get(endpoints.product(id), { ...cfg }),
  getFeatured: (cfg = {}) => api.get(endpoints.featuredProducts, { ...cfg }),
  getCategories: (cfg = {}) => api.get(endpoints.categories, { ...cfg }),
  // Penting: disediakan juga di productService karena store memanggilnya di sini
  getBrands: (cfg = {}) => api.get(endpoints.brands, { ...cfg }),
};

export const brandService = {
  getAll: (params = {}, cfg = {}) =>
    api.get(endpoints.brands, { params, ...cfg }),
  getById: (id, cfg = {}) => api.get(endpoints.brand(id), { ...cfg }),
  getActive: (cfg = {}) => api.get(endpoints.activeBrands, { ...cfg }),
  create: (data, cfg = {}) => api.post(endpoints.brands, data, cfg),
  update: (id, data, cfg = {}) => api.put(endpoints.brand(id), data, cfg),
  delete: (id, cfg = {}) => api.delete(endpoints.brand(id), { ...cfg }),
};

export const heroSectionService = {
  getAll: (params = {}, cfg = {}) =>
    api.get(endpoints.heroSections, { params, ...cfg }),
  getById: (id, cfg = {}) => api.get(endpoints.heroSection(id), { ...cfg }),
  getActive: (cfg = {}) => api.get(endpoints.activeHeroSection, { ...cfg }),
  create: (data, cfg = {}) => api.post(endpoints.heroSections, data, cfg),
  update: (id, data, cfg = {}) => api.put(endpoints.heroSection(id), data, cfg),
  delete: (id, cfg = {}) => api.delete(endpoints.heroSection(id), { ...cfg }),
};

export const contactService = {
  send: (data, cfg = {}) => api.post(endpoints.contact, data, cfg),
};

export default api;
