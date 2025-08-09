import React, { useEffect, useRef, useState } from "react";
import { Search, Filter, ChevronLeft, ChevronRight } from "lucide-react";
import { motion, AnimatePresence } from "framer-motion";
import { Button } from "../components/ui/button";
import { Card, CardContent } from "../components/ui/card";
import { Input } from "../components/ui/input";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "../components/ui/select";
import { useProductStore } from "../stores/productStore";
import { useAppStore } from "../stores/appStore";
import { useDebounce } from "../hooks/useDebounce";
import useScrollRestoration from "../hooks/useScrollRestoration";

// Small helper for progressive image with blur-up + skeleton
const ProgressiveImage: React.FC<{ src: string; alt: string; className?: string }> = ({ src, alt, className }) => {
  const [loaded, setLoaded] = useState(false);
  const [errored, setErrored] = useState(false);
  return (
    <div className={`relative ${className ?? ""}`}>
      {!loaded && !errored && (
        <div
          className="absolute inset-0 bg-gray-200 animate-pulse transition-opacity duration-500"
          aria-hidden="true"
        />
      )}
      {!errored ? (
        <img
          src={src}
          alt={alt}
          loading="lazy"
          decoding="async"
          onLoad={() => setLoaded(true)}
          onError={() => { setErrored(true); setLoaded(true); }}
          className={`w-full h-full object-cover transition-all duration-500 ${loaded ? "opacity-100 blur-0" : "opacity-0 blur-md"}`}
        />
      ) : (
        <div className="w-full h-full bg-gray-200 flex items-center justify-center">
          <span className="text-gray-500 text-sm">No Image</span>
        </div>
      )}
    </div>
  );
};

const Products: React.FC = () => {
  // Initialize scroll restoration
  useScrollRestoration({
    restoreOnRefresh: true,
    scrollToTopOnRouteChange: true,
    smooth: true,
    delay: 0,
  });

  // Track if component is mounted to prevent multiple fetches
  const isInitialMount = useRef(true);
  const hasInitialized = useRef(false);

  // Animation variants
  const containerVariants = {
    hidden: { opacity: 0 },
    visible: {
      opacity: 1,
      transition: {
        duration: 0.6,
        staggerChildren: 0.1,
      },
    },
  };

  const itemVariants = {
    hidden: { opacity: 0, y: 20 },
    visible: {
      opacity: 1,
      y: 0,
      transition: {
        duration: 0.5,
        ease: "easeOut",
      },
    },
  };

  const cardVariants = {
    hidden: { opacity: 0, scale: 0.95, y: 20 },
    visible: {
      opacity: 1,
      scale: 1,
      y: 0,
      transition: {
        duration: 0.4,
        ease: "easeOut",
      },
    },
    hover: {
      y: -8,
      scale: 1.02,
      transition: {
        duration: 0.2,
        ease: "easeInOut",
      },
    },
  };

  const filterVariants = {
    hidden: { opacity: 0, y: -10 },
    visible: {
      opacity: 1,
      y: 0,
      transition: {
        duration: 0.4,
        delay: 0.2,
      },
    },
  };

  // Zustand stores
  const {
    products,
    categories,
    brands,
    isLoading,
    error,
    totalProducts,
    currentPage,
    perPage,
    totalPages,
    hasNextPage,
    hasPrevPage,
    searchTerm,
    selectedCategory,
    selectedBrand,
    sortBy,
    isFiltering,
    getFilteredProductCount,
    setSearchTerm,
    setSelectedCategory,
    setSelectedBrand,
    setSortBy,
    setPerPage,
    setCurrentPage,
    fetchAllData,
    fetchProducts,
    fetchCategories,
    fetchBrands,
    clearError,
    clearCache,
  } = useProductStore();

  const { setPageMeta, addNotification } = useAppStore();

  // Reduced debounce for more responsive search
  const debouncedSearchTerm = useDebounce(searchTerm, 300);

  // Set page metadata only once
  useEffect(() => {
    setPageMeta(
      "Products - Azhar Material",
      "Browse our complete range of construction materials"
    );
  }, [setPageMeta]);

  // Initialize data on first mount only
  useEffect(() => {
    if (isInitialMount.current && !hasInitialized.current) {
      isInitialMount.current = false;
      hasInitialized.current = true;

      // Only clear cache if no data exists
      if (
        products.length === 0 &&
        categories.length === 0 &&
        brands.length === 0
      ) {
        clearCache();
      }

      // Fetch data without aggressive refresh
      fetchAllData(false); // Don't force refresh unless necessary
    }
  }, []); // Empty dependency array for mount-only execution

  // Handle debounced search changes
  useEffect(() => {
    if (!hasInitialized.current) return;

    // Always force refresh for search to get updated results
    fetchProducts(1, true); // Force refresh for search to bypass cache
  }, [debouncedSearchTerm]);

  // Handle errors with notifications
  useEffect(() => {
    if (error) {
      addNotification({
        type: "error",
        message: error,
        duration: 5000,
      });
      clearError();
    }
  }, [error, addNotification, clearError]);

  // Handle search term changes
  const handleSearchChange = (value: string) => {
    setSearchTerm(value);
  };

  // Handle page changes
  const handlePageChange = (page: number) => {
    setCurrentPage(page);
    // Scroll to top when page changes
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  // Handle per page changes
  const handlePerPageChange = (value: string) => {
    const newPerPage = parseInt(value);
    setPerPage(newPerPage);
  };

  const formatPrice = (price: number) => {
    return new Intl.NumberFormat("id-ID", {
      style: "currency",
      currency: "IDR",
      minimumFractionDigits: 0,
    }).format(price);
  };

  if (isLoading) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto mb-4"></div>
          <p className="text-gray-600">Loading products...</p>
        </div>
      </div>
    );
  }

  return (
    <motion.div
      className="min-h-screen bg-gray-50"
      initial="hidden"
      animate="visible"
      variants={containerVariants}
    >
      {/* Breadcrumb */}
      <motion.div className="bg-white py-4 border-b" variants={itemVariants}>
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <nav className="text-sm">
            <span className="text-gray-500">Home</span>
            <span className="mx-2 text-gray-400">/</span>
            <span className="text-primary font-medium">Products</span>
          </nav>
        </div>
      </motion.div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {/* Header */}
        <motion.div className="mb-8" variants={itemVariants}>
          <h1 className="text-4xl font-bold text-neutral-800 mb-4">
            Product Catalog
          </h1>
          <p className="text-xl text-gray-600">
            Browse our complete range of construction materials
          </p>
        </motion.div>

        {/* Filters */}
        <motion.div
          className="bg-white p-6 rounded-2xl shadow-lg mb-8"
          variants={filterVariants}
        >
          <div className="grid grid-cols-1 md:grid-cols-5 gap-4">
            {/* Search */}
            <div className="relative">
              <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
              <Input
                placeholder="Search products..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                className="pl-10 border-gray-200 focus:border-primary rounded-lg"
              />
            </div>

            {/* Category Filter */}
            <Select
              value={selectedCategory}
              onValueChange={setSelectedCategory}
            >
              <SelectTrigger className="border-gray-200 focus:border-primary rounded-lg">
                <SelectValue placeholder="Category" />
              </SelectTrigger>
              <SelectContent className="bg-white">
                <SelectItem value="all">All Categories</SelectItem>
                {categories.map((category) => (
                  <SelectItem key={category.id} value={category.id.toString()}>
                    {category.name}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>

            {/* Brand Filter */}
            <Select value={selectedBrand} onValueChange={setSelectedBrand}>
              <SelectTrigger className="border-gray-200 focus:border-primary rounded-lg">
                <SelectValue placeholder="Brand" />
              </SelectTrigger>
              <SelectContent className="bg-white">
                <SelectItem value="all">All Brands</SelectItem>
                {brands.map((brand) => (
                  <SelectItem key={brand.id} value={brand.id.toString()}>
                    {brand.name}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>

            {/* Sort */}
            <Select value={sortBy} onValueChange={setSortBy}>
              <SelectTrigger className="border-gray-200 focus:border-primary rounded-lg">
                <SelectValue placeholder="Sort by" />
              </SelectTrigger>
              <SelectContent className="bg-white">
                <SelectItem value="name">Sort by Name</SelectItem>
                <SelectItem value="price-low">Price: Low to High</SelectItem>
                <SelectItem value="price-high">Price: High to Low</SelectItem>
              </SelectContent>
            </Select>

            {/* Products Per Page */}
            <Select
              value={perPage.toString()}
              onValueChange={handlePerPageChange}
            >
              <SelectTrigger className="border-gray-200 focus:border-primary rounded-lg">
                <SelectValue />
              </SelectTrigger>
              <SelectContent className="bg-white">
                <SelectItem value="8">8 products</SelectItem>
                <SelectItem value="16">16 products</SelectItem>
                <SelectItem value="24">24 products</SelectItem>
                <SelectItem value="32">32 products</SelectItem>
              </SelectContent>
            </Select>
          </div>

          {/* Results Count */}
          <div className="mt-4 pt-4 border-t border-gray-200">
            <p className="text-gray-600">
              Showing {products.length} of {totalProducts} products
              {isFiltering() && (
                <span className="text-primary font-medium ml-1">
                  (filtered)
                </span>
              )}
              {totalPages > 1 && (
                <span className="ml-2 text-gray-500">
                  • Page {currentPage} of {totalPages}
                </span>
              )}
            </p>
          </div>
        </motion.div>

        {/* Product Grid */}
        <motion.div
          className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
          variants={containerVariants}
        >
          <AnimatePresence>
            {products.map((product, index) => (
              <motion.div
                key={product.id}
                variants={cardVariants}
                initial="hidden"
                animate="visible"
                exit="hidden"
                whileHover="hover"
                transition={{ delay: index * 0.05 }}
                layout
              >
                <Card className="group cursor-pointer border-0 shadow-lg hover:shadow-xl rounded-2xl overflow-hidden h-full">
                  <div className="relative overflow-hidden">
                    {product.image_url ? (
                      <ProgressiveImage
                        src={product.image_url}
                        alt={product.name}
                        className="w-full h-48"
                      />
                    ) : (
                      <div className="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <span className="text-gray-500">No Image</span>
                      </div>
                    )}

                    <div className="absolute top-4 right-4">
                      <span
                        className={`px-3 py-1 text-xs font-semibold rounded-full ${
                          product.stock > 0
                            ? "bg-green-100 text-green-800"
                            : "bg-orange-100 text-orange-800"
                        }`}
                      >
                        {product.stock > 0 ? "In Stock" : "Out of Stock"}
                      </span>
                    </div>
                  </div>

                  <CardContent className="p-6">
                    <h3 className="text-lg font-semibold text-neutral-800 mb-2 line-clamp-2">
                      {product.name}
                    </h3>
                    {product.description && (
                      <p className="text-gray-600 text-sm mb-2 line-clamp-2">
                        {product.description}
                      </p>
                    )}
                    <p className="text-primary font-medium mb-1">
                      {product.brand?.name || "No Brand"}
                    </p>
                    <p className="text-gray-600 text-sm mb-3">
                      {product.category?.name || "No Category"}
                    </p>
                    <div className="flex items-center justify-between">
                      <span className="text-2xl font-bold text-neutral-800">
                        {formatPrice(product.price)}
                      </span>
                    </div>
                  </CardContent>
                </Card>
              </motion.div>
            ))}
          </AnimatePresence>
        </motion.div>

        {/* Pagination Controls - Show when there are multiple pages */}
        {totalPages > 1 && (
          <motion.div
            className="flex justify-center items-center space-x-4 mt-12 mb-8"
            variants={itemVariants}
            initial="hidden"
            animate="visible"
          >
            <Button
              variant="outline"
              onClick={() => handlePageChange(currentPage - 1)}
              disabled={!hasPrevPage || isLoading}
              className="flex items-center space-x-2 px-4 py-2 border-2 border-primary text-primary hover:bg-primary hover:text-white disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300"
            >
              <ChevronLeft className="h-4 w-4" />
              <span>Previous</span>
            </Button>

            <div className="flex items-center space-x-2">
              <span className="text-sm text-gray-600">
                Page {currentPage} of {totalPages}
              </span>
            </div>

            <Button
              variant="outline"
              onClick={() => handlePageChange(currentPage + 1)}
              disabled={!hasNextPage || isLoading}
              className="flex items-center space-x-2 px-4 py-2 border-2 border-primary text-primary hover:bg-primary hover:text-white disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300"
            >
              <span>Next</span>
              <ChevronRight className="h-4 w-4" />
            </Button>
          </motion.div>
        )}

        {/* No Results */}
        {products.length === 0 && !isLoading && (
          <motion.div
            className="text-center py-16"
            variants={itemVariants}
            initial="hidden"
            animate="visible"
          >
            <motion.div
              className="text-gray-400 mb-4"
              animate={{ rotate: [0, 10, -10, 0] }}
              transition={{ duration: 2, repeat: Infinity, repeatDelay: 3 }}
            >
              <Filter className="h-16 w-16 mx-auto" />
            </motion.div>
            <h3 className="text-xl font-semibold text-gray-700 mb-2">
              No products found
            </h3>
            <p className="text-gray-500">
              Try adjusting your search or filter criteria
            </p>
          </motion.div>
        )}
      </div>
    </motion.div>
  );
};

export default Products;
