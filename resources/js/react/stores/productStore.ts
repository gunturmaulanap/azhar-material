import { create } from "zustand";
import { devtools } from "zustand/middleware";
import { productService, brandService } from "../services/api";

/** ==== Types ==== */
type Category = { id: number; name: string };
type Brand = { id: number; name: string; logo?: string | null };
type Product = {
  id: number;
  name: string;
  description?: string;
  price: number;
  stock: number;
  category?: Category;
  brand?: Brand;
  image_url?: string | null;
};

type Store = {
  // state
  products: Product[];
  categories: Category[];
  brands: Brand[];
  isLoading: boolean;
  error: string | null;

  totalProducts: number;
  currentPage: number;
  perPage: number;
  totalPages: number;
  hasNextPage: boolean;
  hasPrevPage: boolean;

  searchTerm: string;
  selectedCategory: string; // "all" or id string
  selectedBrand: string; // "all" or id string
  sortBy: "name" | "price-low" | "price-high";

  // actions
  setSearchTerm: (v: string) => void;
  setSelectedCategory: (v: string) => void;
  setSelectedBrand: (v: string) => void;
  setSortBy: (v: "name" | "price-low" | "price-high") => void;
  setPerPage: (n: number) => void;
  setCurrentPage: (n: number) => void;

  isFiltering: () => boolean;

  clearCache: () => void;
  clearError: () => void;

  fetchAllData: (force?: boolean) => Promise<void>;
  fetchProducts: (page?: number, force?: boolean) => Promise<void>;
};

/** ==== Helpers ==== */
const pickImage = (p: any): string | null =>
  p?.image_url ?? p?.image ?? p?.image_path ?? p?.thumbnail ?? p?.photo ?? null;

const mapProduct = (p: any): Product => ({
  id: Number(p.id),
  name: String(p.name ?? ""),
  description: p.description ?? "",
  price: Number(p.price ?? 0),
  stock: Number(p.stock ?? 0),
  category: p.category
    ? {
        id: Number(p.category.id ?? 0),
        name: String(p.category.name ?? "Uncategorized"),
      }
    : undefined,
  brand: p.brand
    ? {
        id: Number(p.brand.id ?? 0),
        name: String(p.brand.name ?? "Unknown Brand"),
        logo: p.brand.logo ?? null,
      }
    : undefined,
  image_url: typeof pickImage(p) === "string" ? String(pickImage(p)) : null,
});

// Ambil angka aman
const num = (v: any, fallback: number) => {
  const n = Number(v);
  return Number.isFinite(n) ? n : fallback;
};

// Ekstrak array item + meta dari berbagai bentuk respons
function extractItemsAndMeta(
  body: any,
  pageFallback: number,
  perPageFallback: number
): {
  items: any[];
  total: number;
  perPage: number;
  current: number;
  last: number;
} {
  // 1) Array langsung
  if (Array.isArray(body)) {
    const arr = body;
    return {
      items: arr,
      total: arr.length,
      perPage: arr.length || perPageFallback,
      current: 1,
      last: 1,
    };
  }

  // 2) { data: [] , meta?: {...} }
  if (body?.data && Array.isArray(body.data)) {
    const arr = body.data;
    const meta = body.meta ?? {};
    return {
      items: arr,
      total: num(meta.total ?? body.total, arr.length),
      perPage: num(meta.per_page ?? body.per_page, perPageFallback),
      current: num(meta.current_page ?? body.current_page, pageFallback),
      last: num(meta.last_page ?? body.last_page, 1),
    };
  }

  // 3) { data: { data: [] , meta: {...} } } (Laravel Resource + Pagination)
  if (body?.data?.data && Array.isArray(body.data.data)) {
    const arr = body.data.data;
    const meta = body.data.meta ??
      body.meta ?? {
        total: body.data.total,
        per_page: body.data.per_page,
        current_page: body.data.current_page,
        last_page: body.data.last_page,
      };
    return {
      items: arr,
      total: num(meta?.total, arr.length),
      perPage: num(meta?.per_page, perPageFallback),
      current: num(meta?.current_page, pageFallback),
      last: num(meta?.last_page, 1),
    };
  }

  // 4) { items: [] , ... }
  if (Array.isArray(body?.items)) {
    const arr = body.items;
    const meta = body.meta ?? {};
    return {
      items: arr,
      total: num(meta.total ?? body.total, arr.length),
      perPage: num(meta.per_page ?? body.per_page, perPageFallback),
      current: num(meta.current_page ?? body.current_page, pageFallback),
      last: num(meta.last_page ?? body.last_page, 1),
    };
  }

  // 5) { products: [] , ... }
  if (Array.isArray(body?.products)) {
    const arr = body.products;
    const meta = body.meta ?? {};
    return {
      items: arr,
      total: num(meta.total ?? body.total, arr.length),
      perPage: num(meta.per_page ?? body.per_page, perPageFallback),
      current: num(meta.current_page ?? body.current_page, pageFallback),
      last: num(meta.last_page ?? body.last_page, 1),
    };
  }

  // 6) Fallback kosong
  return {
    items: [],
    total: 0,
    perPage: perPageFallback,
    current: pageFallback,
    last: 1,
  };
}

/** ==== Store ==== */
export const useProductStore = create<Store>()(
  devtools((set, get) => ({
    products: [],
    categories: [],
    brands: [],
    isLoading: false,
    error: null,

    totalProducts: 0,
    currentPage: 1,
    perPage: 8,
    totalPages: 1,
    hasNextPage: false,
    hasPrevPage: false,

    searchTerm: "",
    selectedCategory: "all",
    selectedBrand: "all",
    sortBy: "name",

    setSearchTerm: (v) => set({ searchTerm: v }),
    setSelectedCategory: (v) => set({ selectedCategory: v, currentPage: 1 }),
    setSelectedBrand: (v) => set({ selectedBrand: v, currentPage: 1 }),
    setSortBy: (v) => set({ sortBy: v, currentPage: 1 }),
    setPerPage: (n) => set({ perPage: n, currentPage: 1 }),
    setCurrentPage: (n) => set({ currentPage: Math.max(1, n) }),

    isFiltering: () => {
      const s = get();
      return (
        s.searchTerm.trim() !== "" ||
        s.selectedCategory !== "all" ||
        s.selectedBrand !== "all" ||
        s.sortBy !== "name"
      );
    },

    clearCache: () =>
      set({
        products: [],
        categories: [],
        brands: [],
        totalProducts: 0,
        totalPages: 1,
        currentPage: 1,
      }),

    clearError: () => set({ error: null }),

    fetchAllData: async (force = false) => {
      await Promise.all([
        get().fetchProducts(force ? 1 : get().currentPage, force),
        (async () => {
          // kategori
          try {
            const res = await productService.getCategories?.();
            const arr = Array.isArray(res?.data?.data)
              ? res.data.data
              : Array.isArray(res?.data)
              ? res.data
              : [];
            set({
              categories: arr.map((c: any) => ({
                id: Number(c.id),
                name: String(c.name),
              })),
            });
          } catch {
            // ignore
          }
        })(),
        (async () => {
          // brand aktif
          try {
            const res = await brandService.getActive?.();
            const arr = Array.isArray(res?.data?.data)
              ? res.data.data
              : Array.isArray(res?.data)
              ? res.data
              : [];
            set({
              brands: arr.map((b: any) => ({
                id: Number(b.id),
                name: String(b.name),
                logo: b.logo ?? null,
              })),
            });
          } catch {
            // ignore
          }
        })(),
      ]);
    },

    fetchProducts: async (page = 1, force = false) => {
      const s = get();
      set({ isLoading: true });

      try {
        const params: any = {
          page,
          per_page: s.perPage,
          search: s.searchTerm || undefined,
          category_id:
            s.selectedCategory !== "all" ? s.selectedCategory : undefined,
          brand_id: s.selectedBrand !== "all" ? s.selectedBrand : undefined,
          sort:
            s.sortBy === "name"
              ? "name"
              : s.sortBy === "price-low"
              ? "price_asc"
              : "price_desc",
          ...(force ? { _fresh: Date.now() } : {}),
        };

        const res = await productService.getAll(params);
        const body = res?.data;

        // Ekstraksi fleksibel
        const { items, total, perPage, current, last } = extractItemsAndMeta(
          body,
          page,
          s.perPage
        );

        if (!Array.isArray(items)) {
          throw new Error("Format response produk tidak dikenal.");
        }

        const mapped = items.map(mapProduct);

        set({
          products: mapped,
          totalProducts: total,
          perPage,
          currentPage: current,
          totalPages: Math.max(1, last),
          hasNextPage: current < Math.max(1, last),
          hasPrevPage: current > 1,
          isLoading: false,
        });
      } catch (e: any) {
        console.error(e);
        set({
          error:
            e?.response?.data?.message ?? e?.message ?? "Gagal memuat produk.",
          isLoading: false,
        });
      }
    },
  }))
);
