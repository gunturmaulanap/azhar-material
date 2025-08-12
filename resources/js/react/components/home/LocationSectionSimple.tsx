import React, { useState, useRef, useEffect } from "react";
import {
  MapPin,
  Phone,
  Mail,
  Navigation,
  ExternalLink,
  Clock,
  Car,
  Store,
  Zap,
} from "lucide-react";
import { Button } from "../ui/button";

const LocationSectionSimple: React.FC = () => {
  const [isMapHovered, setIsMapHovered] = useState(false);
  const [isVisible, setIsVisible] = useState(false);
  const mapRef = useRef<HTMLIFrameElement>(null);
  const sectionRef = useRef<HTMLElement>(null);

  const storeInfo = {
    name: "Azhar Material",
    tagline: "Toko Besi Terpercaya di Cilacap",
    description:
      "Azhar Material adalah toko besi di pedesaan yang selalu ingin meningkatkan layanan melalui teknologi seperti sekarang ini. Kami berkomitmen memberikan pelayanan terbaik dengan produk berkualitas tinggi untuk kebutuhan konstruksi dan bangunan Anda. Dengan pengalaman bertahun-tahun, kami memahami kebutuhan masyarakat akan material bangunan yang berkualitas dengan harga yang terjangkau.",
    address: {
      full: "JV2V+3JQ, Bojongsari, Ciporos, Kec. Karangpucung, Kabupaten Cilacap, Jawa Tengah 53255",
      city: "Cilacap",
      province: "Jawa Tengah",
      country: "Indonesia",
    },
    contact: {
      phone: "+62 813-9285-4911",
      email: "info@azharmaterial.com",
      whatsapp: "+62 813-9285-4911",
    },
    coordinates: {
      lat: -7.5986,
      lng: 108.8939,
    },
    operatingHours: {
      weekdays: "07:00 - 17:00",
      weekend: "07:00 - 15:00",
    },
  };

  const features = [
    {
      icon: MapPin,
      title: "Lokasi Strategis",
      description:
        "Terletak di pusat Karangpucung, mudah dijangkau dari berbagai daerah sekitar dengan akses jalan utama.",
      gradient: "from-blue-500 to-cyan-500",
    },
    {
      icon: Clock,
      title: "Jam Operasional Fleksibel",
      description:
        "Buka setiap hari dengan jam operasional yang fleksibel untuk memenuhi kebutuhan pelanggan.",
      gradient: "from-green-500 to-emerald-500",
    },
    {
      icon: Car,
      title: "Parkir Luas",
      description:
        "Area parkir yang luas dan aman untuk kendaraan besar maupun kecil, termasuk truk pengangkut material.",
      gradient: "from-purple-500 to-indigo-500",
    },
    {
      icon: Zap,
      title: "Layanan Cepat",
      description:
        "Tim berpengalaman siap melayani dengan cepat dan profesional untuk semua kebutuhan material Anda.",
      gradient: "from-orange-500 to-red-500",
    },
  ];

  // Intersection Observer for scroll animations
  useEffect(() => {
    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          setIsVisible(true);
        }
      },
      { threshold: 0.1 }
    );

    if (sectionRef.current) {
      observer.observe(sectionRef.current);
    }

    return () => observer.disconnect();
  }, []);

  const handleGetDirections = () => {
    const googleMapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${storeInfo.coordinates.lat},${storeInfo.coordinates.lng}`;
    const appleMapsUrl = `http://maps.apple.com/?daddr=${storeInfo.coordinates.lat},${storeInfo.coordinates.lng}`;

    const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    const isApple = /iPhone|iPad|iPod|Mac/i.test(navigator.userAgent);

    if (isMobile && isApple) {
      window.open(appleMapsUrl, "_blank");
    } else {
      window.open(googleMapsUrl, "_blank");
    }
  };

  const handleWhatsApp = () => {
    const message = encodeURIComponent(
      `Halo, saya ingin mengetahui lebih lanjut tentang produk di ${storeInfo.name}`
    );
    window.open(
      `https://wa.me/${storeInfo.contact.whatsapp.replace(
        /[^0-9]/g,
        ""
      )}?text=${message}`,
      "_blank"
    );
  };

  const handleMapInteraction = (type: "hover" | "click", isActive: boolean) => {
    if (type === "hover") {
      setIsMapHovered(isActive);
      if (mapRef.current) {
        mapRef.current.style.transform = isActive ? "scale(1.03)" : "scale(1)";
        mapRef.current.style.filter = isActive
          ? "brightness(1.05) contrast(1.1)"
          : "brightness(1) contrast(1)";
        mapRef.current.style.transition =
          "all 0.4s cubic-bezier(0.4, 0, 0.2, 1)";
      }
    }
  };

  return (
    <section
      ref={sectionRef}
      className="relative py-20 overflow-hidden"
      style={{
        animation: isVisible ? "fadeInUp 0.8s ease-out forwards" : "none",
        opacity: isVisible ? 1 : 0,
        transform: isVisible ? "translateY(0)" : "translateY(30px)",
      }}
    >
      {/* CSS Keyframes */}
      <style>{`
        @keyframes fadeInUp {
          from {
            opacity: 0;
            transform: translateY(30px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }

        @keyframes pulse {
          0%, 100% {
            opacity: 1;
          }
          50% {
            opacity: 0.5;
          }
        }

        @keyframes bounce {
          0%, 100% {
            transform: translateY(-25%);
            animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
          }
          50% {
            transform: translateY(0);
            animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
          }
        }

        .animate-pulse {
          animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        .animate-bounce-custom {
          animation: bounce 1s infinite;
        }

        .card-hover {
          transition: all 0.3s ease;
        }

        .card-hover:hover {
          transform: translateY(-8px);
          box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .icon-rotate:hover {
          animation: rotate 0.6s ease-in-out;
        }

        @keyframes rotate {
          from {
            transform: rotate(0deg);
          }
          to {
            transform: rotate(360deg);
          }
        }

        .feature-card {
          transition: all 0.3s ease;
          animation-delay: var(--delay);
        }

        .feature-card:hover {
          transform: translateY(-10px);
        }

        .stagger-animation {
          animation: fadeInUp 0.6s ease-out forwards;
          animation-delay: var(--delay);
          opacity: 0;
        }

        ${isVisible ? ".stagger-animation { opacity: 1; }" : ""}
      `}</style>

      {/* Background Elements */}
      <div className="absolute inset-0 bg-gradient-to-br from-gray-50 via-white to-gray-100"></div>
      <div className="absolute top-20 right-10 w-72 h-72 bg-primary/5 rounded-full blur-3xl"></div>
      <div className="absolute bottom-20 left-10 w-96 h-96 bg-blue-500/5 rounded-full blur-3xl"></div>

      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Enhanced Header */}
        <div className="text-center mb-16">
          <div className="inline-flex items-center px-4 py-2 bg-primary/10 rounded-full text-primary font-medium text-sm mb-6 hover:scale-105 transition-transform duration-300">
            <Store className="h-4 w-4 mr-2" />
            {storeInfo.tagline}
          </div>
          <h2 className="text-5xl font-bold text-gray-900 mb-6 bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 bg-clip-text">
            Lokasi Toko Kami
          </h2>
          <p className="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
            Kunjungi toko kami untuk melihat langsung kualitas produk dan
            mendapatkan konsultasi gratis dari tim ahli kami
          </p>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
          {/* Enhanced Store Information */}
          <div className="space-y-8">
            <div
              className="stagger-animation"
              style={{ "--delay": "0.2s" } as React.CSSProperties}
            >
              <h3 className="text-3xl font-bold bg-gradient-to-r from-primary to-blue-600 bg-clip-text text-transparent mb-6">
                {storeInfo.name}
              </h3>
              <p className="text-gray-600 leading-relaxed text-lg hover:scale-105 transition-transform duration-300">
                {storeInfo.description}
              </p>
            </div>

            {/* Enhanced Contact Cards */}
            <div className="grid grid-cols-1 gap-6">
              {/* Address Card */}
              <div
                className="card-hover group bg-white/80 backdrop-blur-sm p-8 rounded-3xl shadow-xl border border-white/20 stagger-animation"
                style={{ "--delay": "0.4s" } as React.CSSProperties}
              >
                <div className="flex items-start space-x-6">
                  <div className="bg-gradient-to-br from-primary/20 to-blue-500/20 p-4 rounded-2xl group-hover:scale-110 transition-transform duration-300 icon-rotate">
                    <MapPin className="h-8 w-8 text-primary" />
                  </div>
                  <div className="flex-1">
                    <h4 className="font-bold text-gray-900 mb-3 text-lg">
                      ALAMAT LENGKAP
                    </h4>
                    <p className="text-gray-600 leading-relaxed mb-4">
                      {storeInfo.address.full}
                    </p>
                    <div className="flex flex-wrap gap-2">
                      <span className="px-3 py-1 bg-primary/10 text-primary font-medium rounded-full text-sm">
                        {storeInfo.address.city}
                      </span>
                      <span className="px-3 py-1 bg-blue-500/10 text-blue-600 font-medium rounded-full text-sm">
                        {storeInfo.address.province}
                      </span>
                      <span className="px-3 py-1 bg-gray-100 text-gray-600 font-medium rounded-full text-sm">
                        {storeInfo.address.country}
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              {/* Contact Info Card */}
              <div
                className="card-hover group bg-white/80 backdrop-blur-sm p-8 rounded-3xl shadow-xl border border-white/20 stagger-animation"
                style={{ "--delay": "0.6s" } as React.CSSProperties}
              >
                <h4 className="font-bold text-gray-900 mb-6 flex items-center text-lg">
                  <div className="mr-3 p-2 bg-gradient-to-br from-green-400/20 to-blue-500/20 rounded-xl icon-rotate">
                    <Phone className="h-6 w-6 text-primary" />
                  </div>
                  HUBUNGI KAMI
                </h4>

                <div className="space-y-4">
                  <div className="flex items-center space-x-4 p-3 rounded-2xl hover:bg-gray-50 transition-colors hover:translate-x-1">
                    <Phone className="h-5 w-5 text-gray-400" />
                    <a
                      href={`tel:${storeInfo.contact.phone}`}
                      className="text-gray-700 hover:text-primary transition-colors font-medium"
                    >
                      {storeInfo.contact.phone}
                    </a>
                  </div>

                  <div className="flex items-center space-x-4 p-3 rounded-2xl hover:bg-gray-50 transition-colors hover:translate-x-1">
                    <Mail className="h-5 w-5 text-gray-400" />
                    <a
                      href={`mailto:${storeInfo.contact.email}`}
                      className="text-gray-700 hover:text-primary transition-colors font-medium"
                    >
                      {storeInfo.contact.email}
                    </a>
                  </div>

                  <div className="flex items-center space-x-4 p-3 rounded-2xl hover:bg-gray-50 transition-colors hover:translate-x-1">
                    <Clock className="h-5 w-5 text-gray-400" />
                    <div className="text-gray-700">
                      <div className="font-medium">
                        Senin-Jumat: {storeInfo.operatingHours.weekdays}
                      </div>
                      <div className="text-sm text-gray-500">
                        Sabtu-Minggu: {storeInfo.operatingHours.weekend}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {/* Action Buttons */}
            <div
              className="flex flex-col sm:flex-row gap-4 stagger-animation"
              style={{ "--delay": "0.8s" } as React.CSSProperties}
            >
              <div className="flex-1">
                <Button
                  onClick={handleGetDirections}
                  className="w-full bg-gradient-to-r from-primary to-blue-600 hover:from-primary/90 hover:to-blue-600/90 text-white px-8 py-4 rounded-2xl text-lg font-semibold flex items-center justify-center space-x-3 shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105"
                >
                  <Navigation className="h-6 w-6" />
                  <span>Dapatkan Petunjuk Arah</span>
                  <ExternalLink className="h-5 w-5" />
                </Button>
              </div>
              <div className="flex-1">
                <Button
                  onClick={handleWhatsApp}
                  className="w-full bg-green-500 hover:bg-green-600 text-white px-8 py-4 rounded-2xl text-lg font-semibold flex items-center justify-center space-x-3 shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105"
                >
                  <Phone className="h-6 w-6" />
                  <span>Chat WhatsApp</span>
                </Button>
              </div>
            </div>
          </div>

          {/* Enhanced Map Section */}
          <div
            className="relative stagger-animation"
            style={{ "--delay": "1.0s" } as React.CSSProperties}
          >
            <div
              className="relative overflow-hidden rounded-3xl shadow-2xl group hover:scale-105 transition-transform duration-300"
              onMouseEnter={() => handleMapInteraction("hover", true)}
              onMouseLeave={() => handleMapInteraction("hover", false)}
            >
              <iframe
                ref={mapRef}
                src={`https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3954.123456789!2d${storeInfo.coordinates.lng}!3d${storeInfo.coordinates.lat}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwMzUnNTUuMSJTIDEwOMKwNTMnMzguMCJF!5e0!3m2!1sen!2sid!4v1234567890123!5m2!1sen!2sid`}
                width="100%"
                height="600"
                style={{ border: 0 }}
                allowFullScreen
                loading="lazy"
                referrerPolicy="no-referrer-when-downgrade"
                className="w-full h-[600px] transition-all duration-500"
                title="Lokasi Azhar Material"
              />

              {/* Enhanced Overlay Effects */}
              <div
                className="absolute inset-0 bg-gradient-to-t from-black/10 via-transparent to-transparent pointer-events-none transition-opacity duration-300"
                style={{ opacity: isMapHovered ? 1 : 0 }}
              />

              {/* Enhanced Map Info Overlay */}
              <div className="absolute top-6 left-6 bg-white/95 backdrop-blur-md p-4 rounded-2xl shadow-xl border border-white/20">
                <div className="flex items-center space-x-3 mb-2">
                  <div className="w-3 h-3 bg-primary rounded-full animate-pulse"></div>
                  <span className="text-lg font-bold text-gray-900">
                    {storeInfo.name}
                  </span>
                </div>
                <p className="text-sm text-gray-600 flex items-center">
                  <MapPin className="h-4 w-4 mr-1" />
                  {storeInfo.address.city}, {storeInfo.address.province}
                </p>
              </div>

              {/* Zoom Indicator */}
              <div
                className="absolute bottom-6 right-6 bg-black/50 text-white px-3 py-2 rounded-lg text-sm backdrop-blur-sm transition-all duration-200"
                style={{
                  opacity: isMapHovered ? 1 : 0,
                  transform: isMapHovered ? "scale(1)" : "scale(0.8)",
                }}
              >
                Click to zoom
              </div>
            </div>

            {/* Enhanced Store Image */}
            <div className="mt-8">
              <div className="card-hover group bg-white/80 backdrop-blur-sm p-6 rounded-3xl shadow-xl">
                <div className="aspect-video bg-gradient-to-br from-primary/30 via-blue-500/20 to-purple-500/30 rounded-2xl flex items-center justify-center relative overflow-hidden group-hover:scale-105 transition-transform duration-500">
                  <div className="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                  <div className="relative z-10 text-center text-white">
                    <div className="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur-sm hover:scale-120 hover:rotate-360 hover:bg-white/30 transition-all duration-600">
                      <Store className="h-10 w-10" />
                    </div>
                    <h4 className="font-bold text-xl mb-2">Azhar Material</h4>
                    <p className="text-sm opacity-90">
                      Karangpucung, Cilacap • Jawa Tengah
                    </p>
                    <div className="mt-3 flex items-center justify-center space-x-2 text-xs">
                      <div className="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                      <span>Buka Sekarang</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Enhanced Features Section */}
        <div className="mt-20">
          <div className="bg-white/60 backdrop-blur-sm rounded-3xl shadow-2xl p-10 border border-white/20">
            <div className="text-center mb-12">
              <h3 className="text-3xl font-bold text-gray-900 mb-4">
                Mengapa Memilih Lokasi Kami?
              </h3>
              <p className="text-lg text-gray-600 max-w-2xl mx-auto">
                Lokasi strategis dengan berbagai keunggulan untuk kemudahan
                berbelanja
              </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
              {features.map((feature, index) => {
                const IconComponent = feature.icon;
                return (
                  <div
                    key={index}
                    className="feature-card group text-center p-6 rounded-2xl hover:bg-white/80 transition-all duration-300 hover:shadow-lg"
                    style={
                      {
                        "--delay": `${index * 0.1 + 1.2}s`,
                      } as React.CSSProperties
                    }
                  >
                    <div
                      className={`w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br ${feature.gradient} flex items-center justify-center group-hover:scale-110 transition-transform duration-300 icon-rotate`}
                    >
                      <IconComponent className="h-8 w-8 text-white" />
                    </div>
                    <h4 className="font-bold text-gray-900 mb-3 text-lg">
                      {feature.title}
                    </h4>
                    <p className="text-gray-600 text-sm leading-relaxed">
                      {feature.description}
                    </p>
                  </div>
                );
              })}
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};

export default LocationSectionSimple;
