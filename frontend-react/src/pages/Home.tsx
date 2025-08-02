import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { ArrowRight, Play } from 'lucide-react';
import { Button } from '../components/ui/button';
import { Card, CardContent } from '../components/ui/card';
import { productService, heroSectionService, brandService } from '../services/api';

interface Product {
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
  created_at: string;
  updated_at: string;
}

interface HeroSection {
  id: number;
  title: string;
  subtitle: string;
  description: string;
  button_text: string;
  button_url: string;
  background_image?: string;
  is_active: boolean;
  created_at: string;
  updated_at: string;
}

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

const Home: React.FC = () => {
  const [featuredProducts, setFeaturedProducts] = useState<Product[]>([]);
  const [heroSection, setHeroSection] = useState<HeroSection | null>(null);
  const [brands, setBrands] = useState<Brand[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchData();
  }, []);

  const fetchData = async () => {
    try {
      setLoading(true);
      const [productsRes, heroRes, brandsRes] = await Promise.all([
        productService.getFeatured(),
        heroSectionService.getActive(),
        brandService.getActive()
      ]);

      setFeaturedProducts(productsRes.data.data);
      setHeroSection(heroRes.data.data);
      setBrands(brandsRes.data.data);
    } catch (error) {
      console.error('Error fetching data:', error);
    } finally {
      setLoading(false);
    }
  };

  const scrollToProducts = () => {
    const element = document.getElementById('product-preview');
    if (element) {
      element.scrollIntoView({ behavior: 'smooth' });
    }
  };

  return (
    <div className="relative">
      {/* Hero Section */}
      <section className="relative h-screen flex items-center justify-center overflow-hidden">
        {/* Background Video/Image */}
        <div className="absolute inset-0 w-full h-full">
          {heroSection?.background_image ? (
            <img
              src={heroSection.background_image}
              alt="Hero Background"
              className="w-full h-full object-cover"
            />
          ) : (
            <video
              autoPlay
              loop
              muted
              playsInline
              className="w-full h-full object-cover"
            >
              <source src="https://cdn.pixabay.com/video/2021/08/04/84170-582657466_large.mp4" type="video/mp4" />
            </video>
          )}
          {/* Video Overlay */}
          <div className="absolute inset-0 bg-black bg-opacity-40"></div>
        </div>

        {/* Hero Content */}
        <div className="relative z-10 text-center text-white px-4 sm:px-6 lg:px-8 max-w-4xl">
          <h1 className="text-5xl md:text-7xl font-bold mb-6 leading-tight">
            {heroSection?.title || 'Build with Quality.'}
            <br />
            <span className="text-accent">{heroSection?.subtitle || 'Build with Azhar.'}</span>
          </h1>
          <p className="text-xl md:text-2xl mb-8 text-gray-200 font-light">
            {heroSection?.description || 'Supplying trusted construction materials to fuel your vision.'}
          </p>
          <Button
            onClick={scrollToProducts}
            size="lg"
            className="bg-primary hover:bg-primary/90 text-white px-8 py-4 text-lg font-semibold rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl"
          >
            {heroSection?.button_text || 'View Products'}
            <ArrowRight className="ml-2 h-5 w-5" />
          </Button>
        </div>

        {/* Scroll Indicator */}
        <div className="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
          <div className="w-6 h-10 border-2 border-white rounded-full flex justify-center">
            <div className="w-1 h-3 bg-white rounded-full mt-2 animate-pulse"></div>
          </div>
        </div>
      </section>

      {/* Product Preview Section */}
      <section id="product-preview" className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl md:text-5xl font-bold text-neutral-800 mb-4">
              Featured Products
            </h2>
            <p className="text-xl text-gray-600 max-w-2xl mx-auto">
              Discover our premium selection of construction materials from trusted brands
            </p>
          </div>

          {loading ? (
            <div className="text-center py-16">
              <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto mb-4"></div>
              <p className="text-gray-600">Loading featured products...</p>
            </div>
          ) : (
            <>
              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                {featuredProducts.map((product) => (
                  <Card key={product.id} className="group cursor-pointer border-0 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 rounded-2xl overflow-hidden">
                    <div className="relative overflow-hidden">
                      <div className="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <span className="text-gray-500">No Image</span>
                      </div>
                      <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    <CardContent className="p-6">
                      <h3 className="text-xl font-semibold text-neutral-800 mb-2">{product.name}</h3>
                      {product.description && (
                        <p className="text-gray-600 text-sm mb-2 line-clamp-2">
                          {product.description}
                        </p>
                      )}
                      <p className="text-primary font-medium mb-1">{product.brand?.name || 'No Brand'}</p>
                      <p className="text-gray-600 text-sm">{product.category?.name || 'No Category'}</p>
                    </CardContent>
                  </Card>
                ))}
              </div>

              <div className="text-center">
                <Link to="/products">
                  <Button
                    size="lg"
                    variant="outline"
                    className="border-2 border-primary text-primary hover:bg-primary hover:text-white px-8 py-3 rounded-full font-semibold transition-all duration-300"
                  >
                    View All Products
                    <ArrowRight className="ml-2 h-5 w-5" />
                  </Button>
                </Link>
              </div>
            </>
          )}
        </div>
      </section>

      {/* Brands Section */}
      <section className="py-20 bg-accent/10">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl md:text-5xl font-bold text-neutral-800 mb-4">
              Trusted Brands
            </h2>
            <p className="text-xl text-gray-600">
              We partner with industry-leading manufacturers
            </p>
          </div>

          {loading ? (
            <div className="text-center py-16">
              <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto mb-4"></div>
              <p className="text-gray-600">Loading brands...</p>
            </div>
          ) : (
            <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8 items-center">
              {brands.length > 0 ? (
                brands.map((brand) => (
                  <div
                    key={brand.id}
                    className="flex justify-center items-center p-4 rounded-lg transition-all duration-300 hover:scale-110 grayscale hover:grayscale-0"
                  >
                    {brand.logo ? (
                      <img
                        src={brand.logo}
                        alt={brand.name}
                        className="max-h-16 w-auto opacity-70 hover:opacity-100 transition-opacity duration-300"
                      />
                    ) : (
                      <div className="w-16 h-16 bg-gray-300 rounded-lg flex items-center justify-center">
                        <span className="text-gray-500 text-xs">{brand.name}</span>
                      </div>
                    )}
                  </div>
                ))
              ) : (
                // Placeholder for brands
                [1, 2, 3, 4, 5, 6].map((i) => (
                  <div
                    key={i}
                    className="flex justify-center items-center p-4 rounded-lg transition-all duration-300 hover:scale-110 grayscale hover:grayscale-0"
                  >
                    <div className="w-16 h-16 bg-gray-300 rounded-lg flex items-center justify-center">
                      <span className="text-gray-500 text-xs">Brand {i}</span>
                    </div>
                  </div>
                ))
              )}
            </div>
          )}
        </div>
      </section>
    </div>
  );
};

export default Home;