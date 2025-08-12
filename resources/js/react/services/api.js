// resources/js/react/services/api.js
import axios from "axios";
import Cookies from "js-cookie";
import { apiConfig, getCSRFToken, endpoints } from "../config/api";

// --- Global axios XSRF setup ---
axios.defaults.withCredentials = true;
axios.defaults.xsrfCookieName = "XSRF-TOKEN";
axios.defaults.xsrfHeaderName = "X-XSRF-TOKEN";

// --- Instance untuk endpoint /api ---
const api = axios.create(apiConfig);

// --- Interceptor REQUEST ---
api.interceptors.request.use(
  async (config) => {
    // Untuk method yang mengubah state, pastikan cookie XSRF ada
    if (
      ["post", "put", "patch", "delete"].includes(
        (config.method || "").toLowerCase()
      )
    ) {
      try {
        // PENTING: hit endpoint root, bukan /api
        await axios.get("/sanctum/csrf-cookie", {
          withCredentials: true,
          timeout: 3000,
        });
      } catch (_) {
        /* ignore */
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
api.interceptors.response.use(
  (res) => res,
  (error) => {
    if (error?.response?.status === 401) {
      Cookies.remove("token");
    }
    return Promise.reject(error);
  }
);

// ===== Services =====
export const authService = {
  // pakai axios root untuk set cookie XSRF dari Sanctum
  ensureCsrf: () =>
    axios.get("/sanctum/csrf-cookie", { withCredentials: true }),
  login: (payload) => api.post(endpoints.login, payload),
  logout: () => api.post(endpoints.logout),
  getUser: () => api.get(endpoints.user),
  register: (payload) => api.post(endpoints.register, payload),
};

export const productService = {
  getAll: (params = {}) => api.get(endpoints.products, { params }),
  getById: (id) => api.get(endpoints.product(id)),
  getFeatured: () => api.get(endpoints.featuredProducts),
  getCategories: () => api.get(endpoints.categories),
};

export const brandService = {
  getAll: (params = {}) => api.get(endpoints.brands, { params }),
  getById: (id) => api.get(endpoints.brand(id)),
  getActive: () => api.get(endpoints.activeBrands),
  create: (data) => api.post(endpoints.brands, data),
  update: (id, data) => api.put(endpoints.brand(id), data),
  delete: (id) => api.delete(endpoints.brand(id)),
};

export const heroSectionService = {
  getAll: (params = {}) => api.get(endpoints.heroSections, { params }),
  getById: (id) => api.get(endpoints.heroSection(id)),
  getActive: () => api.get(endpoints.activeHeroSection),
  create: (data) => api.post(endpoints.heroSections, data),
  update: (id, data) => api.put(endpoints.heroSection(id), data),
  delete: (id) => api.delete(endpoints.heroSection(id)),
};

export const contactService = {
  send: (data) => api.post(endpoints.contact, data),
};

export default api;
