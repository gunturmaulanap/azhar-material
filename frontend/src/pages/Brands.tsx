import React from 'react';
import { mockBrands } from '../utils/mockData';

const Brands: React.FC = () => {
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
            We collaborate with industry-leading manufacturers to provide you with the highest quality construction materials and building solutions.
          </p>
        </div>

        {/* Brands Grid */}
        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8">
          {mockBrands.map((brand) => (
            <div
              key={brand.id}
              className="group flex flex-col items-center justify-center p-8 bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2"
            >
              <div className="w-full h-20 flex items-center justify-center mb-4 grayscale group-hover:grayscale-0 transition-all duration-300">
                <img
                  src={brand.logo}
                  alt={brand.name}
                  className="max-w-full max-h-full object-contain"
                />
              </div>
              <h3 className="text-lg font-semibold text-neutral-800 text-center">
                {brand.name}
              </h3>
            </div>
          ))}
        </div>

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
                <h3 className="text-lg font-semibold text-neutral-800 mb-2">Trusted Quality</h3>
                <p className="text-gray-600">All brands undergo strict quality control and certification processes.</p>
              </div>
              <div className="text-center">
                <div className="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                  <span className="text-2xl font-bold text-primary">⚡</span>
                </div>
                <h3 className="text-lg font-semibold text-neutral-800 mb-2">Innovation</h3>
                <p className="text-gray-600">Leading manufacturers with cutting-edge technology and materials.</p>
              </div>
              <div className="text-center">
                <div className="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                  <span className="text-2xl font-bold text-primary">🏆</span>
                </div>
                <h3 className="text-lg font-semibold text-neutral-800 mb-2">Industry Leaders</h3>
                <p className="text-gray-600">Recognized brands with proven track records in construction industry.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Brands;