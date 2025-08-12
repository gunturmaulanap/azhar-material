// API Configuration for Laravel Integration
const API_BASE_URL = window.location.origin + "/api"; // Full URL to avoid CORS

export const apiConfig = {
  baseURL: API_BASE_URL,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
    "X-Requested-With": "XMLHttpRequest",
  },
  timeout: 10000, // Reduced timeout for better mobile experience
  withCredentials: true, // Enable cookies for Laravel session
};

// Configuration for public endpoints that don't need authentication
export const publicApiConfig = {
  ...apiConfig,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
    "X-Requested-With": "XMLHttpRequest",
  },
};

// CSRF Token configuration for Laravel
export const getCSRFToken = () => {
  return document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute("content");
};

// Add CSRF token to request headers
export const apiConfigWithCSRF = () => ({
  ...apiConfig,
  headers: {
    ...apiConfig.headers,
    "X-CSRF-TOKEN": getCSRFToken(),
  },
});

// API endpoints
export const endpoints = {
  // Auth endpoints
  csrf: "/csrf-token",
  sanctumCookie: "/sanctum/csrf-cookie",
  login: "/auth/login",
  register: "/auth/register", 
  logout: "/auth/logout",
  user: "/user",
  me: "/me",
  verifyToken: "/auth/verify",

  // Content management endpoints (for content-admin)
  heroSections: "/hero-sections",
  heroSection: (id: number | string) => `/hero-sections/${id}`,
  activeHeroSection: "/hero-sections/active",
  
  brands: "/brands",
  brand: (id: number | string) => `/brands/${id}`,
  activeBrands: "/brands/active",
  
  products: "/products",
  product: (id: number | string) => `/products/${id}`,
  featuredProducts: "/products/featured",
  categories: "/categories",
  
  services: "/services",
  service: (id: number | string) => `/services/${id}`,
  
  teams: "/teams",
  team: (id: number | string) => `/teams/${id}`,
  
  about: "/about",
  contact: "/contact",

  // Analytics endpoints
  analytics: "/analytics",
  visitors: "/analytics/visitors",
  pageViews: "/analytics/page-views",
};

export default apiConfig;
