import axios from "axios";
import { apiConfig, getCSRFToken, endpoints } from "../config/api";
import Cookies from "js-cookie";

// Create axios instance with configuration
const api = axios.create(apiConfig);

const isDev = typeof import.meta !== 'undefined' ? import.meta.env.DEV : (process.env.NODE_ENV !== 'production');

// Request interceptor for CSRF
api.interceptors.request.use(
  async (config) => {
    try {
      const method = config.method?.toLowerCase?.() || '';
      const isStateChanging = ['post', 'put', 'patch', 'delete'].includes(method);

      // For SPA using Laravel session, do NOT attach Authorization header.
      // We rely on HttpOnly session cookies sent via withCredentials.

      // Get CSRF token only when needed and not already present
      if (isStateChanging) {
        const hasXsrfCookie = !!Cookies.get('XSRF-TOKEN');
        if (!hasXsrfCookie) {
          try {
            // Try to get fresh CSRF token from Laravel only if missing
            await axios.get(`${window.location.origin}/api/sanctum/csrf-cookie`, {
              withCredentials: true,
              timeout: 2500 // Reduced timeout for mobile
            });
          } catch (csrfError) {
            if (isDev) console.warn('Failed to get CSRF cookie:', csrfError);
            // Continue with request even if CSRF fetch fails
          }
        }
      }
      
      // Add CSRF token from meta tag or cookie
      const csrfToken = getCSRFToken() || getCsrfFromCookie();
      if (csrfToken) {
        config.headers["X-CSRF-TOKEN"] = csrfToken;
        config.headers["X-XSRF-TOKEN"] = csrfToken;
      }

      // Strengthen no-store headers on auth-sensitive requests
      if (config.url && (
        config.url.includes(endpoints.login) ||
        config.url.includes(endpoints.logout) ||
        config.url.includes(endpoints.me) ||
        config.url.includes(endpoints.user)
      )) {
        config.headers['Cache-Control'] = 'no-store, no-cache, must-revalidate';
        config.headers['Pragma'] = 'no-cache';
      }

      if (isDev) {
        // Light request log in development only
        console.log('API Request:', {
          url: config.url,
          method: config.method,
          hasCsrf: !!csrfToken
        });
      }
      
      return config;
    } catch (error) {
      if (isDev) console.error('Request interceptor error:', error);
      return config;
    }
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Helper function to get CSRF token from cookie
function getCsrfFromCookie() {
  return Cookies.get('XSRF-TOKEN');
}

// Response interceptor for error handling
api.interceptors.response.use(
  (response) => {
    return response;
  },
  (error) => {
    if (error.response?.status === 401) {
      // Handle unauthorized access; session likely expired. Let caller refresh state.
      if (isDev) console.warn("Authentication failed (401)");
    }
    return Promise.reject(error);
  }
);

// Auth services
export const authService = {
  getCsrf: () => api.get(endpoints.csrf),
  getSanctumCookie: () => api.get(endpoints.sanctumCookie, { headers: { 'Cache-Control': 'no-store, no-cache, must-revalidate', 'Pragma': 'no-cache' } }),
  login: (credentials) => api.post(endpoints.login, credentials),
  register: (userData) => api.post(endpoints.register, userData),
  logout: () => api.post(endpoints.logout),
  // Hydrate auth state from the server session
  getUser: () => api.get(endpoints.me, { headers: { 'Cache-Control': 'no-store, no-cache, must-revalidate', 'Pragma': 'no-cache' } }),
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
