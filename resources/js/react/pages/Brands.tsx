import React, { useState, useEffect } from "react";
import LoadingSpinner from "../components/LoadingSpinner";
import { brandService } from "../services/api";

interface Brand {
  id: number;
  name: string;
  description?: string;
  logo?: string;
  website_url?: string;
  is_active: boolean;
  created_at: string;
  updated_at: string;
}

const Brands: React.FC = () => {
  const [brands, setBrands] = useState<Brand[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    fetchBrands();
  }, []);

  const fetchBrands = async () => {
    try {
      setLoading(true);
      setError(null);

      const response = await brandService.getActive();
      const brandsData = response.data.data;

      setBrands(Array.isArray(brandsData) ? brandsData : []);
    } catch (error) {
      console.error("Error fetching brands:", error);
      setError("Failed to load brands. Please try again.");
      setBrands([]);
    } finally {
      setLoading(false);
    }
  };

  // if (loading) {
  //   return (
  //     <div className="min-h-screen bg-gray-50">
  //       <LoadingSpinner
  //         size="lg"
  //         text="Loading brands..."
  //         className="min-h-screen"
  //       />
  //     </div>
  //   );
  // }

  if (error) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <div className="text-center">
          <h1 className="text-2xl font-bold text-gray-900 mb-4">
            Error Loading Brands
          </h1>
          <p className="text-gray-600 mb-6">{error}</p>
          <button
            onClick={fetchBrands}
            className="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors"
          >
            Try Again
          </button>
        </div>
      </div>
    );
  }
  return (
    <div className="min-h-screen bg-gray-50">
      {/* Breadcrumb */}
      <div className="bg-white py-4 border-b">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <nav className="text-sm">
            <span className="text-gray-500">Home</span>
            <span className="mx-2 text-gray-400">/</span>
            <span className="text-primary font-medium">Brands</span>
          </nav>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        {/* Header */}
        <div className="text-center mb-16">
          <h1 className="text-4xl md:text-5xl font-bold text-neutral-800 mb-6">
            Our Brand Partners
          </h1>
          <p className="text-xl text-gray-600 max-w-3xl mx-auto">
            We collaborate with industry-leading manufacturers to provide you
            with the highest quality construction materials and building
            solutions.
          </p>
        </div>

        {/* Brands Grid */}
        {brands.length > 0 ? (
          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8">
            {brands.map((brand) => (
              <div
                key={brand.id}
                className="group flex flex-col items-center justify-center p-8 bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2"
              >
                <div className="w-full h-20 flex items-center justify-center mb-4 grayscale group-hover:grayscale-0 transition-all duration-300">
                  {brand.logo ? (
                    <img
                      src={brand.logo}
                      alt={brand.name}
                      className="max-w-full max-h-full object-contain"
                      onError={(e) => {
                        const target = e.target as HTMLImageElement;
                        target.style.display = "none";
                        const parent = target.parentElement;
                        if (parent) {
                          parent.innerHTML = `<div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center"><span class="text-gray-500 text-xs text-center">${brand.name}</span></div>`;
                        }
                      }}
                    />
                  ) : (
                    <div className="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                      <span className="text-gray-500 text-xs text-center">
                        {brand.name}
                      </span>
                    </div>
                  )}
                </div>
                <h3 className="text-lg font-semibold text-neutral-800 text-center">
                  {brand.name}
                </h3>
                {brand.description && (
                  <p className="text-sm text-gray-600 text-center mt-2 line-clamp-2">
                    {brand.description}
                  </p>
                )}
              </div>
            ))}
          </div>
        ) : (
          <div className="text-center py-16">
            <div className="text-gray-400 mb-4">
              <svg
                className="h-16 w-16 mx-auto"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth={1}
                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                />
              </svg>
            </div>
            <h3 className="text-xl font-semibold text-gray-700 mb-2">
              No brands available
            </h3>
            <p className="text-gray-500">
              We're working on adding brand partnerships. Check back soon!
            </p>
          </div>
        )}

        {/* Additional Content */}
        <div className="mt-20 bg-white rounded-2xl p-8 md:p-12 shadow-lg">
          <div className="text-center">
            <h2 className="text-3xl font-bold text-neutral-800 mb-6">
              Why Choose Our Brand Partners?
            </h2>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
              <div className="text-center">
                <div className="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                  <span className="text-2xl font-bold text-primary">✓</span>
                </div>
                <h3 className="text-lg font-semibold text-neutral-800 mb-2">
                  Trusted Quality
                </h3>
                <p className="text-gray-600">
                  All brands undergo strict quality control and certification
                  processes.
                </p>
              </div>
              <div className="text-center">
                <div className="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                  <span className="text-2xl font-bold text-primary">⚡</span>
                </div>
                <h3 className="text-lg font-semibold text-neutral-800 mb-2">
                  Innovation
                </h3>
                <p className="text-gray-600">
                  Leading manufacturers with cutting-edge technology and
                  materials.
                </p>
              </div>
              <div className="text-center">
                <div className="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                  <span className="text-2xl font-bold text-primary">🏆</span>
                </div>
                <h3 className="text-lg font-semibold text-neutral-800 mb-2">
                  Industry Leaders
                </h3>
                <p className="text-gray-600">
                  Recognized brands with proven track records in construction
                  industry.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Brands;
