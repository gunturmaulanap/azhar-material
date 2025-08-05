import axios from "axios";
import { apiConfig, getCSRFToken, endpoints } from "../config/api"; // Path diperbaiki
import Cookies from "js-cookie";

// Create axios instance with default config
const api = axios.create(apiConfig);

// Request interceptor to add CSRF token and Authorization header
api.interceptors.request.use(
  async (config) => {
    // --- Bagian Penanganan CSRF Token ---
    let csrfToken = getCSRFToken(); // Ambil CSRF token dari meta tag

    // Jika CSRF token belum ada dan ini bukan permintaan untuk mendapatkan CSRF token itu sendiri
    if (!csrfToken && config.url !== endpoints.csrf) {
      try {
        const response = await api.get(endpoints.csrf); // Minta CSRF token dari backend
        csrfToken = response.data.csrf_token;
        // Set CSRF token di meta tag untuk penggunaan di masa mendatang
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        if (metaTag) {
          metaTag.setAttribute("content", csrfToken);
        } else {
          const meta = document.createElement("meta");
          meta.name = "csrf-token";
          meta.content = csrfToken;
          document.head.appendChild(meta);
        }
      } catch (error) {
        console.error("Failed to get CSRF token:", error);
      }
    }

    // Tambahkan X-CSRF-TOKEN ke header jika ada
    if (csrfToken) {
      config.headers["X-CSRF-TOKEN"] = csrfToken;
    }

    // --- Bagian Penanganan Authorization Header (Bearer Token) ---
    const authToken = Cookies.get("token"); // Ambil token autentikasi dari cookie
    if (authToken) {
      config.headers["Authorization"] = `Bearer ${authToken}`; // Tambahkan header Authorization
      console.log(
        "Authorization header added:",
        config.headers["Authorization"]
      ); // Debug log
    } else {
      console.log("No auth token found in cookie for Authorization header."); // Debug log
    }

    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Response interceptor for error handling
api.interceptors.response.use(
  (response) => {
    return response;
  },
  (error) => {
    if (error.response?.status === 401) {
      // Handle unauthorized access
      Cookies.remove("token"); // Hapus token jika 401
      console.warn("Authentication failed, token removed");
    }
    return Promise.reject(error);
  }
);

// Auth services
export const authService = {
  getCsrf: () => api.get(endpoints.csrf),
  login: (credentials) => {
    console.log("Making login request to:", endpoints.login); // Debug log
    return api.post(endpoints.login, credentials);
  },
  register: (userData) => api.post(endpoints.register, userData),
  logout: () => api.post(endpoints.logout),
  getUser: () => api.get(endpoints.user),
  verifyToken: (token) => api.post(endpoints.verifyToken, { token }),
};

// Product services (from Laravel Goods model)
export const productService = {
  getAll: (params = {}) => api.get(endpoints.products, { params }),
  getById: (id) => api.get(endpoints.product(id)),
  getFeatured: () => api.get(endpoints.featuredProducts),
  getCategories: () => api.get(endpoints.categories),
  getBrands: () => api.get(endpoints.brands),
};

// Contact services
export const contactService = {
  send: (data) => api.post(endpoints.contact, data),
};

// Hero Section services
export const heroSectionService = {
  getAll: (params = {}) => api.get(endpoints.heroSections, { params }),
  getById: (id) => api.get(endpoints.heroSection(id)),
  getActive: () => api.get(endpoints.activeHeroSection),
  create: (data) => api.post(endpoints.heroSections, data),
  update: (id, data) => api.put(endpoints.heroSection(id), data),
  delete: (id) => api.delete(endpoints.heroSection(id)),
};

// Brand services
export const brandService = {
  getAll: (params = {}) => api.get(endpoints.brands, { params }),
  getById: (id) => api.get(endpoints.brand(id)),
  getActive: () => api.get(endpoints.activeBrands),
  create: (data) => api.post(endpoints.brands, data),
  update: (id, data) => api.put(endpoints.brand(id), data),
  delete: (id) => api.delete(endpoints.brand(id)),
};

export default api;
