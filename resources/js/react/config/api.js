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
  csrf: "/sanctum/csrf-cookie",
  login: "/auth/login",
  register: "/auth/register", 
  logout: "/auth/logout",
  user: "/user",

  // Content management endpoints (for content-admin)
  heroSections: "/hero-sections",
  brands: "/brands",
  products: "/products",
  services: "/services",
  teams: "/teams",
  about: "/about",
  contact: "/contact",

  // Analytics endpoints
  analytics: "/analytics",
  visitors: "/analytics/visitors",
  pageViews: "/analytics/page-views",
};

export default apiConfig;
