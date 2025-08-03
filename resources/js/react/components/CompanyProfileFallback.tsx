import React from "react";
import { Building2, Phone, Mail, MapPin } from "lucide-react";

const CompanyProfileFallback: React.FC = () => {
  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
      {/* Hero Section */}
      <section className="relative h-screen flex items-center justify-center overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-600 opacity-90"></div>

        <div className="relative z-10 text-center text-white px-4 max-w-4xl mx-auto">
          <div className="flex justify-center mb-6">
            <Building2 className="h-16 w-16" />
          </div>
          <h1 className="text-4xl md:text-6xl font-bold mb-6 animate-fade-in">
            Azhar Material
          </h1>
          <p className="text-xl md:text-2xl mb-4 opacity-90">
            Your Trusted Construction Partner
          </p>
          <p className="text-lg mb-8 opacity-80 max-w-2xl mx-auto">
            Menyediakan material konstruksi berkualitas tinggi untuk berbagai
            kebutuhan proyek Anda
          </p>
          <button className="bg-white text-blue-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition-colors">
            Lihat Produk Kami
          </button>
        </div>
      </section>

      {/* Company Info Section */}
      <section className="py-16 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
              Tentang Azhar Material
            </h2>
            <p className="text-lg text-gray-600 max-w-3xl mx-auto">
              Kami adalah perusahaan terpercaya yang telah berpengalaman dalam
              menyediakan material konstruksi berkualitas tinggi untuk berbagai
              kebutuhan proyek Anda.
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {/* Contact Info Cards */}
            <div className="text-center p-6 bg-gray-50 rounded-lg">
              <Phone className="h-8 w-8 text-blue-600 mx-auto mb-4" />
              <h3 className="text-lg font-semibold text-gray-900 mb-2">
                Telepon
              </h3>
              <p className="text-gray-600">+62 123 456 7890</p>
            </div>

            <div className="text-center p-6 bg-gray-50 rounded-lg">
              <Mail className="h-8 w-8 text-blue-600 mx-auto mb-4" />
              <h3 className="text-lg font-semibold text-gray-900 mb-2">
                Email
              </h3>
              <p className="text-gray-600">info@azharmaterial.com</p>
            </div>

            <div className="text-center p-6 bg-gray-50 rounded-lg">
              <MapPin className="h-8 w-8 text-blue-600 mx-auto mb-4" />
              <h3 className="text-lg font-semibold text-gray-900 mb-2">
                Lokasi
              </h3>
              <p className="text-gray-600">Jakarta, Indonesia</p>
            </div>
          </div>
        </div>
      </section>

      {/* Products Preview */}
      <section className="py-16 bg-gray-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
              Produk Unggulan
            </h2>
            <p className="text-lg text-gray-600">
              Material konstruksi berkualitas tinggi untuk kebutuhan proyek Anda
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {/* Sample Products */}
            {[
              {
                name: "Besi Beton",
                description: "Besi beton berkualitas tinggi",
              },
              { name: "Semen", description: "Semen premium untuk konstruksi" },
              { name: "Agregat", description: "Pasir dan kerikil pilihan" },
            ].map((product, index) => (
              <div
                key={index}
                className="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow"
              >
                <div className="h-40 bg-gradient-to-br from-gray-200 to-gray-300 rounded-lg mb-4"></div>
                <h3 className="text-lg font-semibold text-gray-900 mb-2">
                  {product.name}
                </h3>
                <p className="text-gray-600">{product.description}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Loading indicator */}
      <div className="fixed bottom-4 right-4 bg-white p-3 rounded-full shadow-lg">
        <div className="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
      </div>
    </div>
  );
};

export default CompanyProfileFallback;
