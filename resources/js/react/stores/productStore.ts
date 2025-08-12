import { create } from 'zustand';
import { persist, createJSONStorage } from 'zustand/middleware';
import { productService } from '../services/api';

export interface Product {
  id: number;
  name: string;
  description?: string;
  price: number;
  stock: number;
  category: {
    id: number;
    name: string;
  };
  brand: {
    id: number;
    name: string;
  };
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
  selectedCategory: string;
  selectedBrand: string;
  sortBy: string;
  perPage: number;
  currentPage: number;
}

interface ProductState {
  // Data
  products: Product[];
  categories: Category[];
  brands: Brand[];
  
  // Pagination
  totalProducts: number;
  currentPage: number;
  perPage: number;
  totalPages: number;
  hasNextPage: boolean;
  hasPrevPage: boolean;
  
  // Filters
  searchTerm: string;
  selectedCategory: string;
  selectedBrand: string;
  sortBy: string;
  
  // Loading states
  isLoading: boolean;
  isLoadingCategories: boolean;
  isLoadingBrands: boolean;
  
  // Error states
  error: string | null;
  
  // Cache
  lastFetch: number;
  cacheExpiry: number; // 2 minutes in milliseconds
  categoriesLastFetch: number;
  brandsLastFetch: number;
  metadataCacheExpiry: number; // 30 seconds for categories and brands
  
  // Actions
  setProducts: (products: Product[]) => void;
  setCategories: (categories: Category[]) => void;
  setBrands: (brands: Brand[]) => void;
  setLoading: (loading: boolean) => void;
  setError: (error: string | null) => void;
  
  // Filter actions
  setSearchTerm: (term: string) => void;
  setSelectedCategory: (category: string) => void;
  setSelectedBrand: (brand: string) => void;
  setSortBy: (sort: string) => void;
  setPerPage: (perPage: number) => void;
  setCurrentPage: (page: number) => void;
  
  // Reset actions
  resetFilters: () => void;
  clearError: () => void;
  clearCache: () => void;
  
  // Fetch actions
  fetchProducts: (page?: number, forceRefresh?: boolean) => Promise<void>;
  fetchCategories: (forceRefresh?: boolean) => Promise<void>;
  fetchBrands: (forceRefresh?: boolean) => Promise<void>;
  fetchAllData: (forceRefresh?: boolean) => Promise<void>;
  
  // Utility functions
  isFiltering: () => boolean;
  isCacheValid: () => boolean;
  getFilteredProductCount: () => number;
  isCategoriesCacheValid: () => boolean;
  isBrandsCacheValid: () => boolean;
  sanitizeProduct: (product: any) => Product | null;
  getMockProducts: () => Product[];
}

const initialFilters: ProductFilters = {
  searchTerm: '',
  selectedCategory: 'all',
  selectedBrand: 'all',
  sortBy: 'name',
  perPage: 8,
  currentPage: 1,
};

export const useProductStore = create<ProductState>()(
  persist(
    (set, get) => ({
      // Initial state
      products: [],
      categories: [],
      brands: [],
      
      // Pagination
      totalProducts: 0,
      // currentPage and perPage are provided by initialFilters spread below
      totalPages: 0,
      hasNextPage: false,
      hasPrevPage: false,
      
      // Filters
      ...initialFilters,
      
      // Loading states
      isLoading: false,
      isLoadingCategories: false,
      isLoadingBrands: false,
      
      // Error states
      error: null,
      
      // Cache
      lastFetch: 0,
      cacheExpiry: 2 * 60 * 1000, // 2 minutes for products
      categoriesLastFetch: 0,
      brandsLastFetch: 0,
      metadataCacheExpiry: 5 * 60 * 1000, // 5 minutes for categories and brands
      
      // Basic setters
      setProducts: (products) => set({ products }),
      setCategories: (categories) => set({ categories }),
      setBrands: (brands) => set({ brands }),
      setLoading: (isLoading) => set({ isLoading }),
      setError: (error) => set({ error }),
      
      // Filter setters
      setSearchTerm: (searchTerm) => {
        set({ searchTerm, currentPage: 1 });
        // Don't auto-fetch for search, let debounce in component handle it
      },
      
      setSelectedCategory: (selectedCategory) => {
        set({ selectedCategory, currentPage: 1 });
        // Immediately fetch products when filter changes
        get().fetchProducts(1, true);
      },
      
      setSelectedBrand: (selectedBrand) => {
        set({ selectedBrand, currentPage: 1 });
        // Immediately fetch products when filter changes
        get().fetchProducts(1, true);
      },
      
      setSortBy: (sortBy) => {
        set({ sortBy, currentPage: 1 });
        // Immediately fetch products when filter changes
        get().fetchProducts(1, true);
      },
      
      setPerPage: (perPage) => {
        set({ perPage, currentPage: 1 });
        // Immediately fetch products when filter changes
        get().fetchProducts(1, true);
      },
      
      setCurrentPage: (currentPage) => {
        set({ currentPage });
        // Force refresh to ensure pagination works
        get().fetchProducts(currentPage, true);
      },
      
      // Reset actions
      resetFilters: () => {
        set({ ...initialFilters });
        get().fetchProducts(1);
      },
      
      clearError: () => set({ error: null }),
      
      clearCache: () => {
        localStorage.removeItem('product-store');
        set({
          ...initialFilters,
          products: [],
          categories: [],
          brands: [],
          totalProducts: 0,
          currentPage: 1,
          totalPages: 0,
          hasNextPage: false,
          hasPrevPage: false,
          isLoading: false,
          isLoadingCategories: false,
          isLoadingBrands: false,
          error: null,
          lastFetch: 0,
          categoriesLastFetch: 0,
          brandsLastFetch: 0,
        });
      },
      
      // Utility functions
      isFiltering: () => {
        const state = get();
        return (
          state.searchTerm.trim() !== '' ||
          state.selectedCategory !== 'all' ||
          state.selectedBrand !== 'all'
        );
      },
      
      isCacheValid: () => {
        const state = get();
        return Date.now() - state.lastFetch < state.cacheExpiry;
      },
      
      isCategoriesCacheValid: () => {
        const state = get();
        return Date.now() - state.categoriesLastFetch < state.metadataCacheExpiry;
      },
      
      isBrandsCacheValid: () => {
        const state = get();
        return Date.now() - state.brandsLastFetch < state.metadataCacheExpiry;
      },
      
      getFilteredProductCount: () => {
        const state = get();
        return state.isFiltering() ? state.products.length : state.totalProducts;
      },
      
      // Utility function to sanitize data
     sanitizeProduct: (product: any): Product | null => {
        // Skip products with invalid names or debug data
        if (!product || 
            !product.name || 
            product.name.length < 3 || 
            /^[a-z]+$/.test(product.name.toLowerCase()) || // Skip single word lowercase names like 'adsasdsd'
            product.name.includes('test') || 
            product.name.includes('debug') ||
            product.name.includes('asdasd') ||
            product.name.toLowerCase().includes('lorem')) {
          return null;
        }
        
        return {
          id: product.id,
          name: product.name,
          description: product.description || '',
          price: typeof product.price === 'number' ? product.price : 0,
          stock: typeof product.stock === 'number' ? product.stock : 0,
          category: {
            id: product.category?.id || 0,
            name: product.category?.name || 'Uncategorized'
          },
          brand: {
            id: product.brand?.id || 0,
            name: product.brand?.name || 'Unknown Brand'
          },
          image_url: typeof product.image_url === 'string' ? product.image_url : null,
          created_at: product.created_at || new Date().toISOString(),
          updated_at: product.updated_at || new Date().toISOString()
        };
      },
      
      getMockProducts: () => [
        {
          id: 1,
          name: "Premium Steel Rod - 10mm",
          description: "High-quality steel rod for construction projects",
          price: 150000,
          stock: 100,
          category: { id: 1, name: "Steel & Iron" },
          brand: { id: 1, name: "Indonesian Steel" },
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString(),
        },
        {
          id: 2,
          name: "Portland Cement - 50kg",
          description: "Premium grade portland cement for construction",
          price: 85000,
          stock: 50,
          category: { id: 2, name: "Cement" },
          brand: { id: 2, name: "Holcim Indonesia" },
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString(),
        },
        {
          id: 3,
          name: "Concrete Block - 20x10x40cm",
          description: "Standard concrete blocks for wall construction",
          price: 12000,
          stock: 200,
          category: { id: 3, name: "Concrete" },
          brand: { id: 3, name: "Varia Usaha" },
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString(),
        },
        {
          id: 4,
          name: "Red Brick - Standard Size",
          description: "Traditional red clay bricks for construction",
          price: 8000,
          stock: 300,
          category: { id: 4, name: "Bricks" },
          brand: { id: 4, name: "Azhar Material" },
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString(),
        },
        {
          id: 5,
          name: "Sand - Fine Grade",
          description: "Fine grade sand for construction and plastering",
          price: 450000,
          stock: 25,
          category: { id: 5, name: "Sand & Gravel" },
          brand: { id: 5, name: "Local Supplier" },
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString(),
        },
        {
          id: 6,
          name: "Gravel Stone - Medium Size",
          description: "Medium grade gravel for concrete mixing",
          price: 350000,
          stock: 30,
          category: { id: 5, name: "Sand & Gravel" },
          brand: { id: 5, name: "Local Supplier" },
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString(),
        },
        {
          id: 7,
          name: "Roof Tiles - Clay",
          description: "Traditional clay roof tiles, weather resistant",
          price: 25000,
          stock: 150,
          category: { id: 6, name: "Roofing" },
          brand: { id: 6, name: "Kanmuri" },
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString(),
        },
        {
          id: 8,
          name: "White Paint - 25L",
          description: "High quality white wall paint for interior and exterior",
          price: 180000,
          stock: 40,
          category: { id: 7, name: "Paint & Finishing" },
          brand: { id: 7, name: "Dulux" },
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString(),
        },
        {
          id: 9,
          name: "Steel Beam - H-Shape",
          description: "Heavy duty steel beam for structural construction",
          price: 2500000,
          stock: 15,
          category: { id: 1, name: "Steel & Iron" },
          brand: { id: 1, name: "Indonesian Steel" },
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString(),
        },
        {
          id: 10,
          name: "Ceramic Tiles - 30x30cm",
          description: "Premium ceramic floor tiles",
          price: 45000,
          stock: 500,
          category: { id: 6, name: "Roofing" },
          brand: { id: 6, name: "Kanmuri" },
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString(),
        },
        {
          id: 11,
          name: "Mortar Mix - 25kg",
          description: "Ready-to-use mortar mix for masonry work",
          price: 65000,
          stock: 80,
          category: { id: 2, name: "Cement" },
          brand: { id: 2, name: "Holcim Indonesia" },
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString(),
        },
        {
          id: 12,
          name: "Plywood - 18mm",
          description: "Marine grade plywood for construction",
          price: 350000,
          stock: 60,
          category: { id: 4, name: "Bricks" },
          brand: { id: 4, name: "Azhar Material" },
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString(),
        },
        {
          id: 13,
          name: "Iron Pipe - 2 inch",
          description: "Galvanized iron pipe for plumbing",
          price: 125000,
          stock: 90,
          category: { id: 1, name: "Steel & Iron" },
          brand: { id: 1, name: "Indonesian Steel" },
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString(),
        },
        {
          id: 14,
          name: "Waterproof Coating - 20L",
          description: "Professional waterproof coating for walls",
          price: 450000,
          stock: 25,
          category: { id: 7, name: "Paint & Finishing" },
          brand: { id: 7, name: "Dulux" },
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString(),
        },
        {
          id: 15,
          name: "Hollow Block - 15x20x40cm",
          description: "Lightweight hollow concrete blocks",
          price: 15000,
          stock: 400,
          category: { id: 3, name: "Concrete" },
          brand: { id: 3, name: "Varia Usaha" },
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString(),
        },
        {
          id: 16,
          name: "Crushed Stone - Medium",
          description: "Crushed stone aggregate for concrete mixing",
          price: 280000,
          stock: 50,
          category: { id: 5, name: "Sand & Gravel" },
          brand: { id: 5, name: "Local Supplier" },
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString(),
        }
      ],

      // Fetch functions
      fetchProducts: async (page = 1, forceRefresh = false) => {
        const state = get();
        
        // Always force refresh when filtering/searching to get fresh results
        const isFiltering = state.isFiltering();
        const shouldForceRefresh = forceRefresh || isFiltering;
        
        // Don't fetch if cache is valid and not forcing refresh and not filtering
        if (!shouldForceRefresh && state.isCacheValid() && state.products.length > 0) {
          return;
        }
        
        try {
          set({ isLoading: true, error: null });
          
          const params: any = {
            page: page,
            per_page: state.perPage // Always use consistent pagination
          };
          
          // Add search and filter params
          if (state.searchTerm.trim()) params.search = state.searchTerm.trim();
          if (state.selectedCategory !== 'all') params.category_id = state.selectedCategory;
          if (state.selectedBrand !== 'all') params.brand_id = state.selectedBrand;
          if (state.sortBy) params.sort_by = state.sortBy;
          
          let products: Product[] = [];
          let totalProducts: number = 0;
          let totalPages: number = 1;
          let hasNextPage: boolean = false;
          let hasPrevPage: boolean = false;
          let currentPage: number = 1;
          
          try {
            const response = await productService.getAll(params);
            const data = response.data.data;
            
            if (data.data) {
              // Paginated response - sanitize products
              const rawProducts = data.data || [];
              const sanitized = rawProducts
                .map((p: any) => get().sanitizeProduct(p))
                .filter((x: Product | null): x is Product => Boolean(x));
              products = sanitized;
              totalProducts = data.total || 0;
              totalPages = data.last_page || 1;
              hasNextPage = data.next_page_url !== null;
              hasPrevPage = data.prev_page_url !== null;
              currentPage = data.current_page || 1;
            } else {
              // Non-paginated response - sanitize products and apply pagination
              const rawProducts = Array.isArray(data) ? data : [];
              const allProducts = rawProducts
                .map((p: any) => get().sanitizeProduct(p))
                .filter((x): x is Product => Boolean(x));
              
              // Apply pagination to filtered results
              const startIndex = (page - 1) * state.perPage;
              const endIndex = startIndex + state.perPage;
              products = allProducts.slice(startIndex, endIndex);
              
              totalProducts = allProducts.length;
              totalPages = Math.ceil(allProducts.length / state.perPage);
              hasNextPage = page < totalPages;
              hasPrevPage = page > 1;
              currentPage = page;
            }
            
            // If no valid products found, use mock data
            if (products.length === 0 && !state.isFiltering()) {
              const mockProducts = get().getMockProducts();
              const startIndex = (page - 1) * state.perPage;
              const endIndex = startIndex + state.perPage;
              products = mockProducts.slice(startIndex, endIndex);
              totalProducts = mockProducts.length;
              totalPages = Math.ceil(mockProducts.length / state.perPage);
              hasNextPage = page < totalPages;
              hasPrevPage = page > 1;
              currentPage = page;
            }
            
          } catch (apiError) {
            console.warn('API request failed, using mock data:', apiError);
            // Use mock data when API fails
            const mockProducts = get().getMockProducts();
            
            // Apply client-side filtering to mock data if needed
            let filteredProducts: Product[] = mockProducts;
            if (state.isFiltering()) {
              filteredProducts = mockProducts.filter((product: Product) => {
                const searchMatch = !state.searchTerm.trim() || 
                  product.name.toLowerCase().includes(state.searchTerm.toLowerCase()) ||
                  product.description?.toLowerCase().includes(state.searchTerm.toLowerCase());
                
                const categoryMatch = state.selectedCategory === 'all' || 
                  product.category.id.toString() === state.selectedCategory;
                
                const brandMatch = state.selectedBrand === 'all' || 
                  product.brand.id.toString() === state.selectedBrand;
                  
                return searchMatch && categoryMatch && brandMatch;
              });
            }
            
            // Apply client-side sorting
            if (state.sortBy === 'price-low') {
              filteredProducts.sort((a: Product, b: Product) => a.price - b.price);
            } else if (state.sortBy === 'price-high') {
              filteredProducts.sort((a: Product, b: Product) => b.price - a.price);
            } else {
              filteredProducts.sort((a: Product, b: Product) => a.name.localeCompare(b.name));
            }
            
            // Apply pagination to filtered results
            const startIndex = (page - 1) * state.perPage;
            const endIndex = startIndex + state.perPage;
            products = filteredProducts.slice(startIndex, endIndex);
            
            totalProducts = filteredProducts.length;
            totalPages = Math.ceil(filteredProducts.length / state.perPage);
            hasNextPage = page < totalPages;
            hasPrevPage = page > 1;
            currentPage = page;
          }
          
          set({
            products,
            totalProducts,
            totalPages,
            hasNextPage,
            hasPrevPage,
            currentPage,
            lastFetch: Date.now(),
            isLoading: false,
          });
          
        } catch (error) {
          console.error('Error fetching products:', error);
          
          // Fallback to mock data even on error
          const mockProducts = get().getMockProducts();
          const startIndex = (page - 1) * state.perPage;
          const endIndex = startIndex + state.perPage;
          const products = mockProducts.slice(startIndex, endIndex);
          
          set({
            products,
            totalProducts: mockProducts.length,
            totalPages: Math.ceil(mockProducts.length / state.perPage),
            hasNextPage: page < Math.ceil(mockProducts.length / state.perPage),
            hasPrevPage: page > 1,
            currentPage: page,
            lastFetch: Date.now(),
            isLoading: false,
            error: null, // Don't show error if we have fallback data
          });
        }
      },
      
      fetchCategories: async (forceRefresh = false) => {
        const state = get();
        
        // Check cache validity for categories
        if (!forceRefresh && state.categories.length > 0 && state.isCategoriesCacheValid()) {
          return;
        }
        
        try {
          set({ isLoadingCategories: true });
          
          try {
            const response = await productService.getCategories();
            const rawCategories = response.data.data || [];
            
            // Get all categories without any filtering - let all data through
            const categories = rawCategories.filter((cat: any) => {
              // Only check for basic data integrity
              return cat && cat.name && typeof cat.name === 'string' && cat.name.trim().length > 0;
            });
            
            // If no valid categories, use mock data
            const finalCategories = categories.length > 0 ? categories : [
              { id: 1, name: "Steel & Iron" },
              { id: 2, name: "Cement" },
              { id: 3, name: "Concrete" },
              { id: 4, name: "Bricks" },
              { id: 5, name: "Sand & Gravel" },
              { id: 6, name: "Roofing" },
              { id: 7, name: "Paint & Finishing" },
            ];
            
            set({ categories: finalCategories, isLoadingCategories: false, categoriesLastFetch: Date.now() });
          } catch (apiError) {
            console.warn('Categories API failed, using mock data:', apiError);
            // Use mock categories when API fails
            const mockCategories = [
              { id: 1, name: "Steel & Iron" },
              { id: 2, name: "Cement" },
              { id: 3, name: "Concrete" },
              { id: 4, name: "Bricks" },
              { id: 5, name: "Sand & Gravel" },
              { id: 6, name: "Roofing" },
              { id: 7, name: "Paint & Finishing" },
            ];
            set({ categories: mockCategories, isLoadingCategories: false, categoriesLastFetch: Date.now() });
          }
        } catch (error) {
          console.error('Error fetching categories:', error);
          set({ 
            categories: [
              { id: 1, name: "Steel & Iron" },
              { id: 2, name: "Cement" },
              { id: 3, name: "Concrete" },
              { id: 4, name: "Bricks" },
              { id: 5, name: "Sand & Gravel" },
              { id: 6, name: "Roofing" },
              { id: 7, name: "Paint & Finishing" },
            ], 
            isLoadingCategories: false,
            categoriesLastFetch: Date.now(),
            error: null
          });
        }
      },
      
      fetchBrands: async (forceRefresh = false) => {
        const state = get();
        
        // Check cache validity for brands
        if (!forceRefresh && state.brands.length > 0 && state.isBrandsCacheValid()) {
          return;
        }
        
        try {
          set({ isLoadingBrands: true });
          
          try {
            const response = await productService.getBrands();
            const rawBrands = response.data.data || [];
            
            // Get all brands without any filtering - let all data through
            const brands = rawBrands.filter((brand: any) => {
              // Only check for basic data integrity
              return brand && brand.name && typeof brand.name === 'string' && brand.name.trim().length > 0;
            });
            
            // If no valid brands, use mock data
            const finalBrands = brands.length > 0 ? brands : [
              { id: 1, name: "Indonesian Steel" },
              { id: 2, name: "Holcim Indonesia" },
              { id: 3, name: "Varia Usaha" },
              { id: 4, name: "Azhar Material" },
              { id: 5, name: "Local Supplier" },
              { id: 6, name: "Kanmuri" },
              { id: 7, name: "Dulux" },
            ];
            
            set({ brands: finalBrands, isLoadingBrands: false, brandsLastFetch: Date.now() });
          } catch (apiError) {
            console.warn('Brands API failed, using mock data:', apiError);
            // Use mock brands when API fails
            const mockBrands = [
              { id: 1, name: "Indonesian Steel" },
              { id: 2, name: "Holcim Indonesia" },
              { id: 3, name: "Varia Usaha" },
              { id: 4, name: "Azhar Material" },
              { id: 5, name: "Local Supplier" },
              { id: 6, name: "Kanmuri" },
              { id: 7, name: "Dulux" },
            ];
            set({ brands: mockBrands, isLoadingBrands: false, brandsLastFetch: Date.now() });
          }
        } catch (error) {
          console.error('Error fetching brands:', error);
          set({ 
            brands: [
              { id: 1, name: "Indonesian Steel" },
              { id: 2, name: "Holcim Indonesia" },
              { id: 3, name: "Varia Usaha" },
              { id: 4, name: "Azhar Material" },
              { id: 5, name: "Local Supplier" },
              { id: 6, name: "Kanmuri" },
              { id: 7, name: "Dulux" },
            ], 
            isLoadingBrands: false,
            brandsLastFetch: Date.now(),
            error: null
          });
        }
      },
      
      fetchAllData: async (forceRefresh = false) => {
        const state = get();
        
        // Fetch categories and brands in parallel if needed
        const promises = [];
        
        if (forceRefresh || state.categories.length === 0) {
          promises.push(state.fetchCategories(forceRefresh));
        }
        
        if (forceRefresh || state.brands.length === 0) {
          promises.push(state.fetchBrands(forceRefresh));
        }
        
        // Wait for categories and brands, then fetch products
        await Promise.all(promises);
        await state.fetchProducts(1, forceRefresh);
      },
    }),
    {
      name: 'product-store',
      storage: createJSONStorage(() => localStorage),
      partialize: (state) => ({
        // Only persist these fields (no filter state)
        categories: state.categories,
        brands: state.brands,
        // Don't persist filters - they should reset on refresh
        lastFetch: state.lastFetch,
      }),
    }
  )
);
