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
import { motion } from "framer-motion";

const LocationSection: React.FC = () => {
  const [isMapHovered, setIsMapHovered] = useState(false);
  const [isVisible, setIsVisible] = useState(false);
  const mapRef = useRef<HTMLIFrameElement>(null);
  const sectionRef = useRef<HTMLElement>(null);

  const storeInfo = {
    name: "TB. Azhar",
    tagline: "Toko Besi Terpercaya di Cilacap",
    description:
      "TB. Azhar adalah toko besi di pedesaan yang selalu ingin meningkatkan layanan melalui teknologi seperti sekarang ini. Kami berkomitmen memberikan pelayanan terbaik dengan produk berkualitas tinggi untuk kebutuhan konstruksi dan bangunan Anda. Dengan pengalaman bertahun-tahun, kami memahami kebutuhan masyarakat akan material bangunan yang berkualitas dengan harga yang terjangkau.",
    address: {
      street: "TB. Azhar, Bojongsari, Ciporos",
      area: "Kec. Karangpucung, Kabupaten Cilacap",
      region: "Jawa Tengah 53255",
      full: "TB. Azhar, Bojongsari, Ciporos, Kec. Karangpucung, Kabupaten Cilacap, Jawa Tengah 53255",
      city: "Cilacap",
      province: "Jawa Tengah",
      country: "Indonesia",
    },
    contact: {
      phone: "(0282) 123-4567",
      mobile: "081392854911",
      email: "azharmaterial@gmail.com",
      whatsapp: "081392854911",
    },
    coordinates: {
      lat: -7.600069,
      lng: 108.893111,
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
    // Navigate to the correct TB. Azhar address: TB. Azhar, Bojongsari, Ciporos, Kec. Karangpucung, Kabupaten Cilacap, Jawa Tengah 53255
    const addressQuery = encodeURIComponent(storeInfo.address.full);
    const coordinateQuery = `${storeInfo.coordinates.lat},${storeInfo.coordinates.lng}`;
    
    // Use address first, fallback to coordinates if needed
    const googleMapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${addressQuery}`;
    const appleMapsUrl = `http://maps.apple.com/?daddr=${addressQuery}`;

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

  const containerVariants = {
    hidden: { opacity: 0 },
    visible: {
      opacity: 1,
      transition: {
        staggerChildren: 0.1,
        delayChildren: 0.2,
      },
    },
  };

  const itemVariants = {
    hidden: { opacity: 0, y: 30 },
    visible: {
      opacity: 1,
      y: 0,
      transition: {
        duration: 0.6,
        ease: [0.4, 0, 0.2, 1], // Use easing array instead of string
      },
    },
  };

  const mapVariants = {
    hidden: { opacity: 0, scale: 0.9 },
    visible: {
      opacity: 1,
      scale: 1,
      transition: {
        duration: 0.8,
        ease: [0.4, 0, 0.2, 1], // Use easing array instead of string
      },
    },
  };

  return (
    <motion.section
      ref={sectionRef}
      className="relative py-20 overflow-hidden"
      initial="hidden"
      animate={isVisible ? "visible" : "hidden"}
      variants={containerVariants}
    >
      {/* Background Elements */}
      <div className="absolute inset-0 bg-gradient-to-br from-gray-50 via-white to-gray-100"></div>
      <div className="absolute top-20 right-10 w-72 h-72 bg-primary/5 rounded-full blur-3xl"></div>
      <div className="absolute bottom-20 left-10 w-96 h-96 bg-blue-500/5 rounded-full blur-3xl"></div>

      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Enhanced Header */}
        <motion.div className="text-center mb-16" variants={itemVariants}>
          <motion.div
            className="inline-flex items-center px-4 py-2 bg-primary/10 rounded-full text-primary font-medium text-sm mb-6"
            whileHover={{ scale: 1.05 }}
          >
            <Store className="h-4 w-4 mr-2" />
            {storeInfo.tagline}
          </motion.div>
          <motion.h2
            className="text-5xl font-bold text-gray-900 mb-6 bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 bg-clip-text"
            variants={itemVariants}
          >
            Lokasi Toko Kami
          </motion.h2>
          <motion.p
            className="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed"
            variants={itemVariants}
          >
            Kunjungi toko kami untuk melihat langsung kualitas produk dan
            mendapatkan konsultasi gratis dari tim ahli kami
          </motion.p>
        </motion.div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
          {/* Enhanced Store Information */}
          <motion.div className="space-y-8" variants={containerVariants}>
            <motion.div variants={itemVariants}>
              <motion.h3 className="text-3xl font-bold bg-gradient-to-r from-primary to-blue-600 bg-clip-text text-transparent mb-6">
                {storeInfo.name}
              </motion.h3>
              <motion.p
                className="text-gray-600 leading-relaxed text-lg"
                whileHover={{ scale: 1.01 }}
                transition={{ type: "spring", stiffness: 300 }}
              >
                {storeInfo.description}
              </motion.p>
            </motion.div>

            {/* Enhanced Contact Cards */}
            <motion.div
              className="grid grid-cols-1 gap-6"
              variants={containerVariants}
            >
              {/* Address Card */}
              <motion.div
                className="group bg-white/80 backdrop-blur-sm p-8 rounded-3xl shadow-xl border border-white/20 hover:shadow-2xl transition-all duration-500 hover:bg-white"
                variants={itemVariants}
                whileHover={{
                  y: -8,
                  transition: { type: "spring", stiffness: 300 },
                }}
              >
                <div className="flex items-start space-x-6">
                  <motion.div
                    className="bg-gradient-to-br from-primary/20 to-blue-500/20 p-4 rounded-2xl group-hover:scale-110 transition-transform duration-300"
                    whileHover={{ rotate: 360 }}
                    transition={{ duration: 0.6 }}
                  >
                    <MapPin className="h-8 w-8 text-primary" />
                  </motion.div>
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
              </motion.div>

              {/* Contact Info Card */}
              <motion.div
                className="group bg-white/80 backdrop-blur-sm p-8 rounded-3xl shadow-xl border border-white/20 hover:shadow-2xl transition-all duration-500 hover:bg-white"
                variants={itemVariants}
                whileHover={{
                  y: -8,
                  transition: { type: "spring", stiffness: 300 },
                }}
              >
                <h4 className="font-bold text-gray-900 mb-6 flex items-center text-lg">
                  <motion.div
                    className="mr-3 p-2 bg-gradient-to-br from-green-400/20 to-blue-500/20 rounded-xl"
                    whileHover={{ rotate: 360 }}
                    transition={{ duration: 0.6 }}
                  >
                    <Phone className="h-6 w-6 text-primary" />
                  </motion.div>
                  HUBUNGI KAMI
                </h4>

                <div className="space-y-4">
                  <motion.div
                    className="flex items-center space-x-4 p-3 rounded-2xl hover:bg-gray-50 transition-colors"
                    whileHover={{ x: 5 }}
                  >
                    <Phone className="h-5 w-5 text-gray-400" />
                    <a
                      href={`tel:${storeInfo.contact.mobile}`}
                      className="text-gray-700 hover:text-primary transition-colors font-medium"
                    >
                      {storeInfo.contact.mobile}
                    </a>
                  </motion.div>

                  <motion.div
                    className="flex items-center space-x-4 p-3 rounded-2xl hover:bg-gray-50 transition-colors"
                    whileHover={{ x: 5 }}
                  >
                    <Mail className="h-5 w-5 text-gray-400" />
                    <a
                      href={`mailto:${storeInfo.contact.email}`}
                      className="text-gray-700 hover:text-primary transition-colors font-medium"
                    >
                      {storeInfo.contact.email}
                    </a>
                  </motion.div>

                  <motion.div
                    className="flex items-center space-x-4 p-3 rounded-2xl hover:bg-gray-50 transition-colors"
                    whileHover={{ x: 5 }}
                  >
                    <Clock className="h-5 w-5 text-gray-400" />
                    <div className="text-gray-700">
                      <div className="font-medium">
                        Senin-Jumat: {storeInfo.operatingHours.weekdays}
                      </div>
                      <div className="text-sm text-gray-500">
                        Sabtu-Minggu: {storeInfo.operatingHours.weekend}
                      </div>
                    </div>
                  </motion.div>
                </div>
              </motion.div>
            </motion.div>

            {/* Action Buttons */}
            <motion.div
              className="flex flex-col sm:flex-row gap-4"
              variants={itemVariants}
            >
              <motion.div
                whileHover={{ scale: 1.05 }}
                whileTap={{ scale: 0.95 }}
                className="flex-1"
              >
                <Button
                  onClick={handleGetDirections}
                  className="w-full bg-gradient-to-r from-primary to-blue-600 hover:from-primary/90 hover:to-blue-600/90 text-white px-8 py-4 rounded-2xl text-lg font-semibold flex items-center justify-center space-x-3 shadow-xl hover:shadow-2xl transition-all duration-300"
                >
                  <Navigation className="h-6 w-6" />
                  <span>Dapatkan Petunjuk Arah</span>
                  <ExternalLink className="h-5 w-5" />
                </Button>
              </motion.div>
              <motion.div
                whileHover={{ scale: 1.05 }}
                whileTap={{ scale: 0.95 }}
                className="flex-1"
              >
                <Button
                  onClick={handleWhatsApp}
                  className="w-full bg-green-500 hover:bg-green-600 text-white px-8 py-4 rounded-2xl text-lg font-semibold flex items-center justify-center space-x-3 shadow-xl hover:shadow-2xl transition-all duration-300"
                >
                  <Phone className="h-6 w-6" />
                  <span>Chat WhatsApp</span>
                </Button>
              </motion.div>
            </motion.div>
          </motion.div>

          {/* Enhanced Map Section */}
          <motion.div className="relative" variants={mapVariants}>
            <motion.div
              className="relative overflow-hidden rounded-3xl shadow-2xl group"
              onMouseEnter={() => handleMapInteraction("hover", true)}
              onMouseLeave={() => handleMapInteraction("hover", false)}
              whileHover={{
                scale: 1.02,
                transition: { duration: 0.3 },
              }}
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
                title="Lokasi TB. Azhar"
              />

              {/* Enhanced Overlay Effects */}
              <motion.div
                className="absolute inset-0 bg-gradient-to-t from-black/10 via-transparent to-transparent pointer-events-none"
                animate={{ opacity: isMapHovered ? 1 : 0 }}
                transition={{ duration: 0.3 }}
              />

              {/* Enhanced Map Info Overlay */}
              <motion.div
                className="absolute top-6 left-6 bg-white/95 backdrop-blur-md p-4 rounded-2xl shadow-xl border border-white/20"
                initial={{ opacity: 0, y: -20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: 0.5 }}
              >
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
              </motion.div>

              {/* Zoom Indicator */}
              <motion.div
                className="absolute bottom-6 right-6 bg-black/50 text-white px-3 py-2 rounded-lg text-sm backdrop-blur-sm"
                animate={{
                  opacity: isMapHovered ? 1 : 0,
                  scale: isMapHovered ? 1 : 0.8,
                }}
                transition={{ duration: 0.2 }}
              >
                Click to zoom
              </motion.div>
            </motion.div>

            {/* Enhanced Store Image */}
            <motion.div className="mt-8" variants={itemVariants}>
              <motion.div
                className="group bg-white/80 backdrop-blur-sm p-6 rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500"
                whileHover={{ y: -5 }}
              >
                <div className="aspect-video bg-gradient-to-br from-primary/30 via-blue-500/20 to-purple-500/30 rounded-2xl flex items-center justify-center relative overflow-hidden group-hover:scale-105 transition-transform duration-500">
                  <div className="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                  <motion.div
                    className="relative z-10 text-center text-white"
                    initial={{ opacity: 0, scale: 0.8 }}
                    animate={{ opacity: 1, scale: 1 }}
                    transition={{ delay: 0.8, duration: 0.5 }}
                  >
                    <motion.div
                      className="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur-sm"
                      whileHover={{
                        scale: 1.2,
                        rotate: 360,
                        background: "rgba(255,255,255,0.3)",
                      }}
                      transition={{ duration: 0.6 }}
                    >
                      <Store className="h-10 w-10" />
                    </motion.div>
                    <h4 className="font-bold text-xl mb-2">TB. Azhar</h4>
                    <p className="text-sm opacity-90">
                      Karangpucung, Cilacap • Jawa Tengah
                    </p>
                    <div className="mt-3 flex items-center justify-center space-x-2 text-xs">
                      <div className="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                      <span>Buka Sekarang</span>
                    </div>
                  </motion.div>
                </div>
              </motion.div>
            </motion.div>
          </motion.div>
        </div>

        {/* Enhanced Features Section */}
        <motion.div className="mt-20" variants={itemVariants}>
          <motion.div
            className="bg-white/60 backdrop-blur-sm rounded-3xl shadow-2xl p-10 border border-white/20"
            whileInView={{ opacity: 1, y: 0 }}
            initial={{ opacity: 0, y: 50 }}
            transition={{ duration: 0.8 }}
            viewport={{ once: true }}
          >
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
                  <motion.div
                    key={index}
                    className="group text-center p-6 rounded-2xl hover:bg-white/80 transition-all duration-300 hover:shadow-lg"
                    initial={{ opacity: 0, y: 30 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    transition={{ delay: index * 0.1, duration: 0.6 }}
                    viewport={{ once: true }}
                    whileHover={{ y: -10 }}
                  >
                    <motion.div
                      className={`w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br ${feature.gradient} flex items-center justify-center group-hover:scale-110 transition-transform duration-300`}
                      whileHover={{ rotate: 360 }}
                      transition={{ duration: 0.6 }}
                    >
                      <IconComponent className="h-8 w-8 text-white" />
                    </motion.div>
                    <h4 className="font-bold text-gray-900 mb-3 text-lg">
                      {feature.title}
                    </h4>
                    <p className="text-gray-600 text-sm leading-relaxed">
                      {feature.description}
                    </p>
                  </motion.div>
                );
              })}
            </div>
          </motion.div>
        </motion.div>
      </div>
    </motion.section>
  );
};

export default LocationSection;
