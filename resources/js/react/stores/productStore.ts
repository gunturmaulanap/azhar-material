import { create } from "zustand";
import { persist, createJSONStorage } from "zustand/middleware";
import { productService } from "../services/api";

export interface Product {
  id: number;
  name: string;
  description?: string;
  price: number;
  stock: number;
  category: { id: number; name: string };
  brand: { id: number; name: string };
  image_url?: string | null;
  created_at: string;
  updated_at: string;
}

export interface Category {
  id: number;
  name: string;
}
export interface Brand {
  id: number;
  name: string;
}

interface ProductFilters {
  searchTerm: string;
  selectedCategory: string; // 'all' | id
  selectedBrand: string; // 'all' | id
  sortBy: string; // 'name' | 'price-low' | 'price-high' | ...
  perPage: number;
  currentPage: number;
}

interface ProductState {
  // data
  products: Product[];
  categories: Category[];
  brands: Brand[];

  // pagination
  totalProducts: number;
  currentPage: number;
  perPage: number;
  totalPages: number;
  hasNextPage: boolean;
  hasPrevPage: boolean;

  // filters
  searchTerm: string;
  selectedCategory: string;
  selectedBrand: string;
  sortBy: string;

  // loading & error
  isLoading: boolean;
  isLoadingCategories: boolean;
  isLoadingBrands: boolean;
  error: string | null;

  // meta cache utk kategori/brand
  categoriesLastFetch: number;
  brandsLastFetch: number;
  metadataCacheExpiry: number;

  // internal
  _abort?: AbortController;

  // actions
  setSearchTerm: (term: string) => void;
  setSelectedCategory: (category: string) => void;
  setSelectedBrand: (brand: string) => void;
  setSortBy: (sort: string) => void;
  setPerPage: (perPage: number) => void;
  setCurrentPage: (page: number) => void;
  resetFilters: () => void;
  clearError: () => void;
  clearCache: () => void;

  // utils
  isFiltering: () => boolean; // <-- balik lagi

  // fetchers
  fetchProducts: (page?: number, forceRefresh?: boolean) => Promise<void>;
  fetchCategories: (forceRefresh?: boolean) => Promise<void>;
  fetchBrands: (forceRefresh?: boolean) => Promise<void>;
  fetchAllData: (forceRefresh?: boolean) => Promise<void>;
}

const initialFilters: ProductFilters = {
  searchTerm: "",
  selectedCategory: "all",
  selectedBrand: "all",
  sortBy: "name",
  perPage: 8,
  currentPage: 1,
};

export const useProductStore = create<ProductState>()(
  persist(
    (set, get) => ({
      // data
      products: [],
      categories: [],
      brands: [],

      // pagination
      totalProducts: 0,
      totalPages: 0,
      hasNextPage: false,
      hasPrevPage: false,

      // filters
      ...initialFilters,

      // loading & error
      isLoading: false,
      isLoadingCategories: false,
      isLoadingBrands: false,
      error: null,

      // meta cache
      categoriesLastFetch: 0,
      brandsLastFetch: 0,
      metadataCacheExpiry: 5 * 60 * 1000,

      // setters
      setSearchTerm: (searchTerm) => {
        set({ searchTerm, currentPage: 1 });
        // debounce di komponen
      },
      setSelectedCategory: (selectedCategory) => {
        set({ selectedCategory, currentPage: 1 });
        void get().fetchProducts(1, true);
      },
      setSelectedBrand: (selectedBrand) => {
        set({ selectedBrand, currentPage: 1 });
        void get().fetchProducts(1, true);
      },
      setSortBy: (sortBy) => {
        set({ sortBy, currentPage: 1 });
        void get().fetchProducts(1, true);
      },
      setPerPage: (perPage) => {
        set({ perPage, currentPage: 1 });
        void get().fetchProducts(1, true);
      },
      setCurrentPage: (currentPage) => {
        set({ currentPage });
        void get().fetchProducts(currentPage, false);
      },

      resetFilters: () => {
        set({ ...initialFilters });
        void get().fetchProducts(1, true);
      },

      clearError: () => set({ error: null }),

      clearCache: () => {
        localStorage.removeItem("product-store");
        set({
          categories: [],
          brands: [],
          categoriesLastFetch: 0,
          brandsLastFetch: 0,
        });
      },

      // utils
      isFiltering: () => {
        const s = get();
        return (
          s.searchTerm.trim() !== "" ||
          s.selectedCategory !== "all" ||
          s.selectedBrand !== "all"
        );
      },

      // fetchers
      fetchProducts: async (
        page = get().currentPage || 1,
        _forceRefresh = false
      ) => {
        const state = get();

        // batalkan request sebelumnya (hindari race)
        try {
          state._abort?.abort();
        } catch {}
        const abort = new AbortController();
        set({ _abort: abort });

        const params: Record<string, any> = {
          page,
          per_page: state.perPage,
        };
        if (state.searchTerm.trim()) params.search = state.searchTerm.trim();
        if (state.selectedCategory !== "all")
          params.category_id = state.selectedCategory;
        if (state.selectedBrand !== "all")
          params.brand_id = state.selectedBrand;
        if (state.sortBy) params.sort_by = state.sortBy;

        set({ isLoading: true, error: null });
        try {
          const res = await productService.getAll(params, {
            signal: abort.signal,
          });
          const body = res?.data?.data ?? res?.data;

          let products: Product[] = [];
          let total = 0,
            lastPage = 1,
            current = page,
            next = false,
            prev = page > 1;

          if (body && typeof body === "object" && Array.isArray(body.data)) {
            // server paginated
            products = (body.data as any[]).map((p) => ({
              id: p.id,
              name: p.name,
              description: p.description ?? "",
              price: Number(p.price ?? 0),
              stock: Number(p.stock ?? 0),
              category: {
                id: p.category?.id ?? 0,
                name: p.category?.name ?? "Uncategorized",
              },
              brand: {
                id: p.brand?.id ?? 0,
                name: p.brand?.name ?? "Unknown Brand",
              },
              image_url: typeof p.image_url === "string" ? p.image_url : null,
              created_at: p.created_at ?? new Date().toISOString(),
              updated_at: p.updated_at ?? new Date().toISOString(),
            }));
            total = Number(body.total ?? products.length);
            lastPage = Number(body.last_page ?? 1);
            current = Number(body.current_page ?? page);
            next = !!body.next_page_url;
            prev = !!body.prev_page_url;
          } else if (Array.isArray(body)) {
            // server non‑paginated array
            products = (body as any[]).map((p) => ({
              id: p.id,
              name: p.name,
              description: p.description ?? "",
              price: Number(p.price ?? 0),
              stock: Number(p.stock ?? 0),
              category: {
                id: p.category?.id ?? 0,
                name: p.category?.name ?? "Uncategorized",
              },
              brand: {
                id: p.brand?.id ?? 0,
                name: p.brand?.name ?? "Unknown Brand",
              },
              image_url: typeof p.image_url === "string" ? p.image_url : null,
              created_at: p.created_at ?? new Date().toISOString(),
              updated_at: p.updated_at ?? new Date().toISOString(),
            }));
            total = products.length;
            lastPage = Math.max(1, Math.ceil(total / state.perPage));
            current = Math.min(page, lastPage);
            const start = (current - 1) * state.perPage;
            products = products.slice(start, start + state.perPage);
            next = current < lastPage;
            prev = current > 1;
          } else {
            throw new Error("Format response /products tidak dikenal");
          }

          set({
            products,
            totalProducts: total,
            totalPages: lastPage,
            currentPage: current,
            hasNextPage: next,
            hasPrevPage: prev,
            isLoading: false,
            _abort: undefined,
          });
        } catch (err: any) {
          if (err?.name === "CanceledError" || err?.name === "AbortError")
            return;
          console.error("fetchProducts error:", err);
          set({
            isLoading: false,
            error:
              err?.response?.data?.message ??
              err?.message ??
              "Gagal memuat produk",
            _abort: undefined,
          });
        }
      },

      fetchCategories: async (forceRefresh = false) => {
        const { categoriesLastFetch, metadataCacheExpiry, categories } = get();
        const fresh = Date.now() - categoriesLastFetch < metadataCacheExpiry;
        if (!forceRefresh && categories.length && fresh) return;

        set({ isLoadingCategories: true });
        try {
          const res = await productService.getCategories();
          const data = res?.data?.data ?? [];
          const list: Category[] = Array.isArray(data)
            ? data
                .filter((c: any) => c && c.name)
                .map((c: any) => ({ id: Number(c.id), name: String(c.name) }))
            : [];
          set({
            categories: list,
            categoriesLastFetch: Date.now(),
            isLoadingCategories: false,
          });
        } catch (err) {
          console.error("fetchCategories error:", err);
          set({ isLoadingCategories: false });
        }
      },

      fetchBrands: async (forceRefresh = false) => {
        const { brandsLastFetch, metadataCacheExpiry, brands } = get();
        const fresh = Date.now() - brandsLastFetch < metadataCacheExpiry;
        if (!forceRefresh && brands.length && fresh) return;

        set({ isLoadingBrands: true });
        try {
          const res = await productService.getBrands();
          const data = res?.data?.data ?? [];
          const list: Brand[] = Array.isArray(data)
            ? data
                .filter((b: any) => b && b.name)
                .map((b: any) => ({ id: Number(b.id), name: String(b.name) }))
            : [];
          set({
            brands: list,
            brandsLastFetch: Date.now(),
            isLoadingBrands: false,
          });
        } catch (err) {
          console.error("fetchBrands error:", err);
          set({ isLoadingBrands: false });
        }
      },

      fetchAllData: async (forceRefresh = false) => {
        await Promise.all([
          get().fetchCategories(forceRefresh),
          get().fetchBrands(forceRefresh),
        ]);
        await get().fetchProducts(1, true);
      },
    }),
    {
      name: "product-store",
      storage: createJSONStorage(() => localStorage),
      partialize: (s) => ({
        // persist meta yang aman (tidak menyimpan filter)
        categories: s.categories,
        brands: s.brands,
        categoriesLastFetch: s.categoriesLastFetch,
        brandsLastFetch: s.brandsLastFetch,
      }),
    }
  )
);
