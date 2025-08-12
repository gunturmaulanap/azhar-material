import axios from "axios";
import { apiConfig, getCSRFToken, endpoints } from "../config/api";
import Cookies from "js-cookie";

// Create axios instance with configuration
const api = axios.create(apiConfig);

const isDev = typeof import.meta !== 'undefined' ? import.meta.env.DEV : (process.env.NODE_ENV !== 'production');

// Request interceptor for CSRF and Authorization tokens
api.interceptors.request.use(
  async (config) => {
    try {
      const method = config.method?.toLowerCase?.() || '';
      const isStateChanging = ['post', 'put', 'patch', 'delete'].includes(method);

      // Add Authorization header if we have a token early (so CSRF preflight also has it if needed)
      const authToken = Cookies.get("token");
      if (authToken) {
        config.headers["Authorization"] = `Bearer ${authToken}`;
      }

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

      if (isDev) {
        // Light request log in development only
        console.log('API Request:', {
          url: config.url,
          method: config.method,
          hasToken: !!authToken,
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
      // Handle unauthorized access
      Cookies.remove("token"); // Hapus token jika 401
      if (isDev) console.warn("Authentication failed, token removed");
    }
    return Promise.reject(error);
  }
);

// Auth services
export const authService = {
  getCsrf: () => api.get(endpoints.csrf),
  login: (credentials) => {
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
