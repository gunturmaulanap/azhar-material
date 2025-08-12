// resources/js/react/config/api.ts
// API Configuration for Laravel Integration

const API_BASE_URL = window.location.origin + "/api";

export const apiConfig = {
  baseURL: API_BASE_URL,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
    "X-Requested-With": "XMLHttpRequest",
  },
  timeout: 10000,
  withCredentials: true,
};

export const publicApiConfig = {
  ...apiConfig,
};

export const getCSRFToken = () =>
  document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") ||
  undefined;

export const apiConfigWithCSRF = () => ({
  ...apiConfig,
  headers: {
    ...apiConfig.headers,
    "X-CSRF-TOKEN": getCSRFToken(),
  },
});

// Endpoints (baseURL sudah /api)
export const endpoints = {
  csrfCookie: "/sanctum/csrf-cookie",
  login: "/auth/login",
  register: "/auth/register",
  logout: "/auth/logout",
  user: "/user",
  verifyToken: "/auth/verify",

  // konten publik (opsional)
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
};

export default apiConfig;
