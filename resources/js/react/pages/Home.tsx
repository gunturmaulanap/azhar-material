import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import useScrollRestoration from "../hooks/useScrollRestoration";
import { toPublicUrl } from "../utils/storageUrl";

import {
  ArrowRight,
  AlertCircle,
  Truck,
  Users,
  Building,
  Package,
  Clock,
  Shield,
  MapPin,
  Phone,
  MessageCircle,
  Mail,
} from "lucide-react";
import { motion } from "framer-motion";
import { Button } from "../components/ui/button";
import { Card, CardContent } from "../components/ui/card";
import {
  Carousel,
  CarouselContent,
  CarouselItem,
  CarouselNext,
  CarouselPrevious,
} from "../components/ui/carousel";
import Autoplay from "embla-carousel-autoplay";
import LocationSection from "../components/home/LocationSection";
import {
  productService,
  heroSectionService,
  brandService,
} from "../services/api";

/* ========= Komponen Gambar Progresif (local) ========= */
const ProgressiveImage: React.FC<{
  src?: string;
  alt: string;
  className?: string;
}> = ({ src, alt, className }) => {
  const [loaded, setLoaded] = useState(false);
  const [errored, setErrored] = useState(false);

  return (
    <div className={`relative ${className ?? ""}`}>
      {!loaded && !errored && (
        <div className="absolute inset-0 bg-gray-200 animate-pulse transition-opacity duration-500" />
      )}

      {!errored && src ? (
        <img
          src={src}
          alt={alt}
          loading="lazy"
          decoding="async"
          onLoad={() => setLoaded(true)}
          onError={() => {
            setErrored(true);
            setLoaded(true);
          }}
          className={`w-full h-full object-cover transition-all duration-500 ${
            loaded ? "opacity-100 blur-0" : "opacity-0 blur-md"
          }`}
        />
      ) : (
        <div className="w-full h-full bg-gray-200 flex items-center justify-center">
          <span className="text-gray-500 text-sm">No Image</span>
        </div>
      )}
    </div>
  );
};
/* ===================================================== */

interface Product {
  id: number;
  name: string;
  description?: string;
  price: number;
  stock: number;
  category?: { id: number; name: string };
  brand?: { id: number; name: string };
  image_url?: string | null;
}

interface HeroSection {
  id: number;
  title: string;
  subtitle: string;
  description: string;
  button_text: string;
  button_url: string;
  background_image?: string | null;
  background_video?: string | null;
  background_type?: "image" | "video";
  background_image_url?: string | null;
  background_video_url?: string | null;
  is_active: boolean;
}

interface Brand {
  id: number;
  name: string;
  description?: string;
  logo?: string | null;
  website_url?: string;
  is_active: boolean;
}

const Home: React.FC = () => {
  // ✅ Samakan title halaman Home
  useEffect(() => {
    document.title = "Home – Azhar Material";
  }, []);

  const [featuredProducts, setFeaturedProducts] = useState<Product[]>([]);
  const [heroSection, setHeroSection] = useState<HeroSection | null>(null);
  const [brands, setBrands] = useState<Brand[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [videoLoaded, setVideoLoaded] = useState(false);
  const [isMobile, setIsMobile] = useState(false);

  // Scroll restore
  useScrollRestoration({
    restoreOnRefresh: true,
    scrollToTopOnRouteChange: true,
    smooth: true,
    delay: 100,
  });

  const autoplayPlugin = React.useRef(
    Autoplay({ delay: 2000, stopOnInteraction: true })
  );

  // Animations
  const containerVariants: any = {
    hidden: { opacity: 0 },
    visible: {
      opacity: 1,
      transition: {
        duration: 0.6,
        ease: "easeOut",
        staggerChildren: 0.08,
        delayChildren: 0.1,
      },
    },
  };

  const itemVariants: any = {
    hidden: { opacity: 0, y: 30 },
    visible: {
      opacity: 1,
      y: 0,
      transition: { duration: 0.6, ease: "easeOut" },
    },
  };

  const cardVariants: any = {
    hidden: { opacity: 0, scale: 0.9 },
    visible: {
      opacity: 1,
      scale: 1,
      transition: { duration: 0.5, ease: "easeOut" },
    },
    hover: {
      scale: 1.05,
      y: -10,
      transition: { duration: 0.3, ease: "easeOut" },
    },
  };

  const fadeInUpVariants: any = {
    hidden: { opacity: 0, y: 40 },
    visible: {
      opacity: 1,
      y: 0,
      transition: { duration: 0.6, ease: [0.25, 0.1, 0.25, 1] },
    },
  };

  useEffect(() => {
    const checkIsMobile = () => setIsMobile(window.innerWidth < 768);
    checkIsMobile();
    window.addEventListener("resize", checkIsMobile);

    if (window.innerWidth < 768) setVideoLoaded(true);
    fetchData();

    return () => window.removeEventListener("resize", checkIsMobile);
  }, []);

  const fetchData = async () => {
    try {
      setLoading(true);
      setError(null);

      const [productsRes, heroRes, brandsRes] = await Promise.allSettled([
        productService.getFeatured(),
        heroSectionService.getActive(),
        brandService.getActive(),
      ]);

      if (
        productsRes.status === "fulfilled" &&
        Array.isArray(productsRes.value?.data?.data)
      ) {
        setFeaturedProducts(productsRes.value.data.data);
      } else {
        setFeaturedProducts([]);
      }

      if (heroRes.status === "fulfilled" && heroRes.value?.data?.data) {
        setHeroSection(heroRes.value.data.data);
      } else {
        setHeroSection(null);
      }

      if (
        brandsRes.status === "fulfilled" &&
        Array.isArray(brandsRes.value?.data?.data)
      ) {
        setBrands(brandsRes.value.data.data);
      } else {
        setBrands([]);
      }
    } catch (e) {
      console.error(e);
      setError("Gagal memuat data. Silakan coba lagi.");
    } finally {
      setLoading(false);
    }
  };

  const scrollToProducts = () => {
    document.getElementById("product-preview")?.scrollIntoView({
      behavior: "smooth",
    });
  };

  if (loading) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto mb-4" />
          <p className="text-gray-600">Loading...</p>
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <motion.div
        className="min-h-screen flex items-center justify-center bg-gray-50"
        initial={{ opacity: 0 }}
        animate={{ opacity: 1 }}
        transition={{ duration: 0.5 }}
      >
        <div className="max-w-md w-full mx-auto p-6">
          <div className="bg-white rounded-lg shadow-lg p-8 text-center">
            <div className="flex justify-center mb-4">
              <AlertCircle className="h-12 w-12 text-red-500" />
            </div>
            <h1 className="text-xl font-semibold text-gray-900 mb-2">
              Terjadi Kesalahan
            </h1>
            <p className="text-gray-600 mb-6">{error}</p>
            <Button onClick={fetchData} className="w-full">
              Coba Lagi
            </Button>
          </div>
        </div>
      </motion.div>
    );
  }

  return (
    <motion.div
      className="relative"
      initial={{ opacity: 0 }}
      animate={{ opacity: 1 }}
      transition={{ duration: 0.5, ease: "easeOut" }}
    >
      {/* Hero Section */}
      <motion.section
        className="relative h-screen flex items-center justify-center overflow-hidden"
        initial="hidden"
        animate="visible"
        variants={fadeInUpVariants}
      >
        {/* Background Media */}
        <div className="absolute inset-0 w-full h-full">
          {heroSection?.background_type === "video" &&
          (heroSection?.background_video ||
            heroSection?.background_video_url) ? (
            <div className="relative w-full h-full">
              <motion.video
                autoPlay
                muted
                loop
                playsInline
                preload={isMobile ? "metadata" : "auto"}
                onLoadedData={() => setVideoLoaded(true)}
                onCanPlay={() => setVideoLoaded(true)}
                className="w-full h-full object-cover"
                style={{ filter: "brightness(0.7) contrast(1.1)" }}
                initial={{ opacity: 0 }}
                animate={{ opacity: 1 }}
                transition={{ duration: 1, ease: "easeOut" }}
              >
                <source
                  src={
                    toPublicUrl(heroSection.background_video_url) ??
                    toPublicUrl(heroSection.background_video)
                  }
                  type="video/mp4"
                />
              </motion.video>

              <motion.div
                className="absolute inset-0 bg-gradient-to-br from-black/40 via-black/30 to-black/50"
                initial={{ opacity: 0 }}
                animate={{ opacity: 1 }}
                transition={{ duration: 0.8, delay: 0.3, ease: "easeOut" }}
              />
            </div>
          ) : heroSection?.background_type === "image" &&
            (heroSection?.background_image ||
              heroSection?.background_image_url) ? (
            <motion.img
              src={
                toPublicUrl(heroSection.background_image_url) ??
                toPublicUrl(heroSection.background_image)
              }
              alt="Hero Background"
              className="w-full h-full object-cover"
              style={{ filter: "brightness(0.7) contrast(1.1)" }}
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              transition={{ duration: 0.8, ease: "easeOut" }}
            />
          ) : (
            // Fallback: video default
            <div className="relative w-full h-full">
              <motion.video
                autoPlay
                muted
                loop
                playsInline
                preload={isMobile ? "metadata" : "auto"}
                onLoadedData={() => setVideoLoaded(true)}
                onCanPlay={() => setVideoLoaded(true)}
                className="w-full h-full object-cover"
                style={{ filter: "brightness(0.7) contrast(1.1)" }}
                initial={{ opacity: 0 }}
                animate={{ opacity: 1 }}
                transition={{ duration: 1, ease: "easeOut" }}
              >
                <source src="/videos/azhar.mp4" type="video/mp4" />
              </motion.video>

              <motion.div
                className="absolute inset-0 bg-gradient-to-br from-black/40 via-black/30 to-black/50"
                initial={{ opacity: 0 }}
                animate={{ opacity: 1 }}
                transition={{ duration: 0.8, delay: 0.3, ease: "easeOut" }}
              />
            </div>
          )}
        </div>

        {/* Hero Content */}
        <motion.div
          className="relative z-10 text-center text-white px-4 sm:px-6 lg:px-8 max-w-4xl"
          variants={containerVariants}
          initial="hidden"
          animate="visible"
        >
          <motion.h1
            className="text-5xl md:text-7xl font-bold mb-6 leading-tight drop-shadow-2xl"
            variants={itemVariants}
          >
            {heroSection?.title || "Build with Quality."}
            <br />
            <span className="text-accent drop-shadow-xl">
              {heroSection?.subtitle || "Build with Azhar."}
            </span>
          </motion.h1>
          <motion.p
            className="text-xl md:text-2xl mb-8 text-gray-100 font-light drop-shadow-lg"
            variants={itemVariants}
          >
            {heroSection?.description ||
              "Supplying trusted construction materials to fuel your vision."}
          </motion.p>
          <motion.div variants={itemVariants}>
            <Button
              onClick={scrollToProducts}
              size="lg"
              className="bg-primary hover:bg-primary/90 text-white px-8 py-4 text-lg font-semibold rounded-full transition-all duration-500 transform hover:scale-105 shadow-2xl hover:shadow-3xl backdrop-blur-sm border border-white/20"
            >
              {heroSection?.button_text || "View Products"}
              <ArrowRight className="ml-2 h-5 w-5" />
            </Button>
          </motion.div>
        </motion.div>

        {/* Scroll Indicator */}
        <motion.div
          className="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce"
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 1, duration: 0.5 }}
        >
          <div className="w-6 h-10 border-2 border-white rounded-full flex justify-center">
            <div className="w-1 h-3 bg-white rounded-full mt-2 animate-pulse" />
          </div>
        </motion.div>
      </motion.section>

      {/* Product Preview */}
      <motion.section
        id="product-preview"
        className="py-20 bg-white"
        initial="hidden"
        whileInView="visible"
        viewport={{ once: true, amount: 0.3 }}
        variants={containerVariants}
      >
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <motion.div className="text-center mb-16" variants={itemVariants}>
            <motion.h2
              className="text-4xl md:text-5xl font-bold text-neutral-800 mb-4"
              variants={itemVariants}
            >
              Featured Products
            </motion.h2>
            <motion.p
              className="text-xl text-gray-600 max-w-2xl mx-auto"
              variants={itemVariants}
            >
              Discover our premium selection of construction materials from
              trusted brands
            </motion.p>
          </motion.div>

          {featuredProducts.length === 0 ? (
            <div className="text-center py-16">
              <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto mb-4" />
              <p className="text-gray-600">No featured products available</p>
            </div>
          ) : (
            <>
              <motion.div
                className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12"
                variants={containerVariants}
              >
                {featuredProducts.map((product, index) => (
                  <motion.div
                    key={product.id}
                    variants={cardVariants}
                    whileHover="hover"
                    custom={index}
                    className="cursor-pointer"
                  >
                    <Card className="group border-0 shadow-lg hover:shadow-xl transition-all duration-300 rounded-2xl overflow-hidden h-full">
                      <div className="relative overflow-hidden">
                        {product.image_url ? (
                          <ProgressiveImage
                            src={toPublicUrl(product.image_url)}
                            alt={product.name}
                            className="w-full h-48"
                          />
                        ) : (
                          <div className="w-full h-48 bg-gradient-to-br from-primary/10 to-accent/20 flex items-center justify-center">
                            <span className="text-primary font-semibold">
                              Material Image
                            </span>
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
                        <h3 className="text-xl font-semibold text-neutral-800 mb-2">
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
                        <p className="text-gray-600 text-sm">
                          {product.category?.name || "No Category"}
                        </p>
                      </CardContent>
                    </Card>
                  </motion.div>
                ))}
              </motion.div>

              <motion.div className="text-center" variants={itemVariants}>
                <Link to="/products">
                  <motion.div
                    whileHover={{ scale: 1.05 }}
                    whileTap={{ scale: 0.95 }}
                  >
                    <Button
                      size="lg"
                      variant="outline"
                      className="border-2 border-primary text-primary hover:bg-primary hover:text-white px-8 py-3 rounded-full font-semibold transition-all duration-300 shadow-lg hover:shadow-xl"
                    >
                      View All Products
                      <ArrowRight className="ml-2 h-5 w-5" />
                    </Button>
                  </motion.div>
                </Link>
              </motion.div>
            </>
          )}
        </div>
      </motion.section>

      {/* Brands */}
      <section className="py-20 bg-gradient-to-br from-accent/5 to-primary/5">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl md:text-5xl font-bold text-neutral-800 mb-4">
              Trusted Brands
            </h2>
            <p className="text-xl text-gray-600">
              We partner with industry-leading manufacturers
            </p>
          </div>

          {brands.length === 0 ? (
            <div className="text-center py-16">
              <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto mb-4" />
              <p className="text-gray-600">No brands available</p>
            </div>
          ) : (
            <div className="relative">
              <Carousel
                className="w-full max-w-6xl mx-auto"
                onMouseEnter={() => autoplayPlugin.current.stop()}
                onMouseLeave={() => autoplayPlugin.current.reset()}
              >
                <CarouselContent className="-ml-1">
                  {brands.map((brand) => (
                    <CarouselItem
                      key={brand.id}
                      className="pl-1 basis-1/2 md:basis-1/3 lg:basis-1/4 xl:basis-1/5"
                    >
                      <div className="p-1">
                        <Card className="group border-0 shadow-md hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 rounded-2xl overflow-hidden bg-white/80 backdrop-blur-sm">
                          <CardContent className="flex flex-col items-center justify-center p-6 h-32">
                            {brand.logo ? (
                              <img
                                src={toPublicUrl(brand.logo)}
                                alt={brand.name}
                                className="max-h-12 w-auto grayscale group-hover:grayscale-0 opacity-70 group-hover:opacity-100 transition-all duration-300 mb-2"
                                loading="lazy"
                              />
                            ) : (
                              <div className="w-12 h-12 bg-gradient-to-br from-primary/20 to-accent/20 rounded-xl flex items-center justify-center mb-2 group-hover:from-primary/30 group-hover:to-accent/30 transition-all duration-300">
                                <span className="text-primary font-bold text-sm truncate px-1">
                                  {brand.name
                                    .split(" ")
                                    .map((w) => w[0])
                                    .join("")
                                    .slice(0, 2)}
                                </span>
                              </div>
                            )}
                            <h3 className="text-sm font-semibold text-neutral-800 text-center group-hover:text-primary transition-colors duration-300 truncate w-full">
                              {brand.name}
                            </h3>
                          </CardContent>
                        </Card>
                      </div>
                    </CarouselItem>
                  ))}
                </CarouselContent>
                <CarouselPrevious className="-left-4 bg-white/90 hover:bg-white shadow-lg" />
                <CarouselNext className="-right-4 bg-white/90 hover:bg-white shadow-lg" />
              </Carousel>
            </div>
          )}
        </div>
      </section>

      {/* Services + CTA + Location */}
      <section className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl md:text-5xl font-bold text-neutral-800 mb-4">
              Our Services
            </h2>
            <p className="text-xl text-gray-600 max-w-3xl mx-auto">
              Comprehensive construction material solutions designed to support
              your building projects
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <div className="group text-center">
              <div className="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-primary group-hover:scale-110 transition-all duration-300">
                <Truck className="h-8 w-8 text-primary group-hover:text-white transition-colors duration-300" />
              </div>
              <h3 className="text-lg font-semibold text-neutral-800 mb-3">
                Material Supply
              </h3>
              <p className="text-gray-600 text-sm leading-relaxed">
                Complete range of construction materials delivered to your
                project site
              </p>
            </div>

            <div className="group text-center">
              <div className="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-primary group-hover:scale-110 transition-all duration-300">
                <Users className="h-8 w-8 text-primary group-hover:text-white transition-colors duration-300" />
              </div>
              <h3 className="text-lg font-semibold text-neutral-800 mb-3">
                Expert Consultation
              </h3>
              <p className="text-gray-600 text-sm leading-relaxed">
                Professional advice on material selection and quantity planning
              </p>
            </div>

            <div className="group text-center">
              <div className="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-green-600 group-hover:scale-110 transition-all duration-300">
                <Building className="h-8 w-8 text-green-600 group-hover:text-white transition-colors duration-300" />
              </div>
              <h3 className="text-lg font-semibold text-neutral-800 mb-3">
                Project Support
              </h3>
              <p className="text-gray-600 text-sm leading-relaxed">
                Ongoing support throughout your construction project lifecycle
              </p>
            </div>

            <div className="group text-center">
              <div className="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-600 group-hover:scale-110 transition-all duration-300">
                <Package className="h-8 w-8 text-blue-600 group-hover:text-white transition-colors duration-300" />
              </div>
              <h3 className="text-lg font-semibold text-neutral-800 mb-3">
                Quality Assurance
              </h3>
              <p className="text-gray-600 text-sm leading-relaxed">
                All materials come with quality guarantee and certification
              </p>
            </div>
          </div>

          <div className="text-center">
            <Link to="/services">
              <Button
                size="lg"
                className="bg-primary hover:bg-primary/90 text-white px-8 py-3 rounded-full font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl"
              >
                Learn More About Our Services
                <ArrowRight className="ml-2 h-5 w-5" />
              </Button>
            </Link>
          </div>
        </div>
      </section>

      <LocationSection />

      {/* CTA */}
      <section className="py-20 bg-gradient-to-br from-primary to-primary/80">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <h2 className="text-4xl md:text-5xl font-bold text-white mb-4">
              Ready to Start Your Project?
            </h2>
            <p className="text-xl text-accent max-w-3xl mx-auto">
              Get in touch with our team for personalized consultation and
              competitive pricing
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div className="text-center text-white">
              <div className="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <Clock className="h-8 w-8 text-white" />
              </div>
              <h3 className="text-lg font-semibold mb-2">Fast Response</h3>
              <p className="text-accent">Quick quotes and immediate support</p>
            </div>

            <div className="text-center text-white">
              <div className="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <Shield className="h-8 w-8 text-white" />
              </div>
              <h3 className="text-lg font-semibold mb-2">Trusted Service</h3>
              <p className="text-accent">Experience serving professionals</p>
            </div>

            <div className="text-center text-white">
              <div className="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <MapPin className="h-8 w-8 text-white" />
              </div>
              <h3 className="text-lg font-semibold mb-2">Wide Coverage</h3>
              <p className="text-accent">Delivery across Jabodetabek</p>
            </div>
          </div>

          <div className="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <Button
              onClick={() =>
                window.open("https://wa.me/6281392854911", "_blank")
              }
              className="bg-white text-primary hover:bg-accent hover:text-white rounded-full px-8 py-3 font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg"
            >
              <MessageCircle className="h-5 w-5 mr-2" />
              Chat via WhatsApp
            </Button>
            <Button
              onClick={() => window.open("tel:081392854911", "_blank")}
              variant="outline"
              className="border-2 border-white text-white hover:bg-white hover:text-primary rounded-full px-8 py-3 font-semibold transition-all duration-300 transform hover:scale-105"
            >
              <Phone className="h-5 w-5 mr-2" />
              Call Now
            </Button>
            <Link to="/contact">
              <Button
                variant="outline"
                className="border-2 border-white text-white hover:bg-white hover:text-primary rounded-full px-8 py-3 font-semibold transition-all duration-300 transform hover:scale-105"
              >
                <Mail className="h-5 w-5 mr-2" />
                Contact Us
              </Button>
            </Link>
          </div>
        </div>
      </section>
    </motion.div>
  );
};

export default Home;
