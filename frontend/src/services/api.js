import axios from 'axios';
import { apiConfig, getCSRFToken, endpoints } from '../config/api';

// Create axios instance with default config
const api = axios.create(apiConfig);

// Request interceptor to add CSRF token
api.interceptors.request.use(
  (config) => {
    const token = getCSRFToken();
    if (token) {
      config.headers['X-CSRF-TOKEN'] = token;
    }
    
    // Add Authorization header if user is authenticated
    const authToken = localStorage.getItem('auth_token');
    if (authToken) {
      config.headers['Authorization'] = `Bearer ${authToken}`;
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
      localStorage.removeItem('auth_token');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

// Auth services
export const authService = {
  login: (credentials) => api.post(endpoints.login, credentials),
  register: (userData) => api.post(endpoints.register, userData),
  logout: () => api.post(endpoints.logout),
  getUser: () => api.get(endpoints.user),
};

// Product services (from Laravel Goods model)
export const productService = {
  getAll: (params = {}) => api.get(endpoints.products, { params }),
  getById: (id) => api.get(endpoints.product(id)),
  getFeatured: () => api.get(endpoints.featuredProducts),
  getCategories: () => api.get(endpoints.categories),
  getBrands: () => api.get(endpoints.brands),
};

// Hero Section services
export const heroSectionService = {
  getAll: () => api.get(endpoints.heroSections),
  getById: (id) => api.get(endpoints.heroSection(id)),
  getActive: () => api.get(endpoints.activeHeroSection),
};

// Brand services
export const brandService = {
  getAll: () => api.get(endpoints.brands),
  getById: (id) => api.get(endpoints.brand(id)),
  getActive: () => api.get(endpoints.activeBrands),
};

// Contact services
export const contactService = {
  send: (data) => api.post(endpoints.contact, data),
};

export default api; 