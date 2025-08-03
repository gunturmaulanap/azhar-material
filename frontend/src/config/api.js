// API Configuration for Laravel Integration
const API_BASE_URL =
  process.env.REACT_APP_API_URL || "http://localhost:8000/api";

export const apiConfig = {
  baseURL: API_BASE_URL,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
  timeout: 10000,
  withCredentials: true, // Enable cookies for cross-origin requests
};

// CSRF Token configuration for Laravel
export const getCSRFToken = () => {
  return document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute("content");
};

// API endpoints
export const endpoints = {
  // Auth endpoints
  csrf: "/csrf-token",
  login: "/auth/login",
  register: "/auth/register",
  logout: "/auth/logout",
  user: "/user",

  // Product endpoints (from Laravel Goods model)
  products: "/products",
  product: (id) => `/products/${id}`,
  featuredProducts: "/products/featured",
  categories: "/categories",
  brands: "/brands",

  // Contact endpoints
  contact: "/contact",

  // Hero Section endpoints
  heroSections: "/hero-sections",
  heroSection: (id) => `/hero-sections/${id}`,
  activeHeroSection: "/hero-sections/active",

  // Brand endpoints
  brand: (id) => `/brands/${id}`,
  activeBrands: "/brands/active",
};

export default apiConfig;
