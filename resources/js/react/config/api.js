// API Configuration for Laravel Integration
const API_BASE_URL = "/api"; // Same domain, no CORS issues

export const apiConfig = {
  baseURL: API_BASE_URL,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
    "X-Requested-With": "XMLHttpRequest",
  },
  timeout: 10000,
  withCredentials: true, // Enable cookies for Laravel session
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
  login: "/auth/login",
  register: "/auth/register", 
  logout: "/auth/logout",
  user: "/user",
  verifyToken: "/auth/verify",

  // Content management endpoints (for content-admin)
  heroSections: "/hero-sections",
  heroSection: (id) => `/hero-sections/${id}`,
  activeHeroSection: "/hero-sections/active",
  
  brands: "/brands",
  brand: (id) => `/brands/${id}`,
  activeBrands: "/brands/active",
  
  products: "/products",
  product: (id) => `/products/${id}`,
  featuredProducts: "/products/featured",
  categories: "/categories",
  
  services: "/services",
  service: (id) => `/services/${id}`,
  
  teams: "/teams",
  team: (id) => `/teams/${id}`,
  
  about: "/about",
  contact: "/contact",

  // Analytics endpoints
  analytics: "/analytics",
  visitors: "/analytics/visitors",
  pageViews: "/analytics/page-views",
};

export default apiConfig;
