import React, { useState, useEffect } from "react";
import { ChevronDown, MapPin, Calendar, ArrowRight } from "lucide-react";
import { motion } from "framer-motion";

interface Project {
  id: number;
  title: string;
  location: string;
  date: string;
  weight: string;
  category: string;
  image: string;
  display_image_url: string; // Add this accessor property
  description: string;
}

const Projects: React.FC = () => {
  const [projects, setProjects] = useState<Project[]>([]);
  const [loading, setLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);
  const [selectedCategory, setSelectedCategory] = useState<string>("all");
  const [isFiltersOpen, setIsFiltersOpen] = useState<boolean>(false);

  const categories = [
    { id: "all", name: "Semua Kategori" },
    { id: "infrastruktur", name: "Infrastruktur" },
    { id: "bangunan", name: "Bangunan" },
    { id: "sanitasi", name: "Sanitasi" },
  ];

  useEffect(() => {
    const fetchProjects = async () => {
      setLoading(true);
      setError(null);
      let apiUrl = "http://localhost:8000/api/projects?status=published";
      if (selectedCategory !== "all") {
        apiUrl += `&category=${selectedCategory}`;
      }
      try {
        const response = await fetch(apiUrl);
        if (!response.ok) {
          throw new Error("Gagal mengambil data proyek");
        }
        const result = await response.json();
        setProjects(result.data);
      } catch (err: any) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };
    fetchProjects();
  }, [selectedCategory]);

  const containerVariants = {
    hidden: { opacity: 0 },
    visible: {
      opacity: 1,
      transition: {
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
      },
    },
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
      {/* Hero Section */}
      <motion.div
        className="relative bg-gradient-to-r from-primary/90 to-primary py-24 overflow-hidden"
        initial={{ opacity: 0 }}
        animate={{ opacity: 1 }}
        transition={{ duration: 0.8 }}
      >
        <div className="absolute inset-0 bg-black/10"></div>
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8, delay: 0.2 }}
            className="text-center text-white"
          >
            <h1 className="text-5xl font-bold mb-6">Proyek Kami</h1>
            <p className="text-xl text-white/90 max-w-3xl mx-auto leading-relaxed">
              Melihat berbagai proyek pembangunan desa yang telah kami
              selesaikan dengan dedikasi tinggi dan material berkualitas untuk
              kemajuan masyarakat pedesaan
            </p>
          </motion.div>
        </div>
      </motion.div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        {/* Filter Section */}
        <motion.div
          className="mb-12"
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.6, delay: 0.4 }}
        >
          <div className="bg-white rounded-2xl shadow-soft p-6 border border-gray-200">
            <button
              onClick={() => setIsFiltersOpen(!isFiltersOpen)}
              className="flex items-center space-x-2 text-neutral-700 font-medium hover:text-primary transition-colors"
            >
              <ChevronDown
                className={`w-5 h-5 transition-transform ${
                  isFiltersOpen ? "rotate-180" : ""
                }`}
              />
              <span>Filters</span>
            </button>

            {isFiltersOpen && (
              <motion.div
                className="mt-6 pt-6 border-t border-gray-200"
                initial={{ opacity: 0, height: 0 }}
                animate={{ opacity: 1, height: "auto" }}
                transition={{ duration: 0.3 }}
              >
                <div className="flex flex-wrap gap-3">
                  {categories.map((category) => (
                    <button
                      key={category.id}
                      onClick={() => setSelectedCategory(category.id)}
                      className={`px-6 py-2 rounded-full font-medium transition-all duration-200 ${
                        selectedCategory === category.id
                          ? "bg-primary text-white shadow-medium"
                          : "bg-gray-100 text-gray-700 hover:bg-gray-200"
                      }`}
                    >
                      {category.name}
                    </button>
                  ))}
                </div>
              </motion.div>
            )}
          </div>
        </motion.div>

        {/* Status Handling */}
        {loading && (
          <div className="text-center text-gray-500 text-lg my-12">
            Mengambil data proyek...
          </div>
        )}

        {error && (
          <div className="text-center text-red-500 text-lg my-12">
            Terjadi kesalahan: {error}
          </div>
        )}

        {!loading && !error && projects.length === 0 && (
          <div className="text-center text-gray-500 text-lg my-12">
            Tidak ada proyek dalam kategori ini.
          </div>
        )}

        {/* Projects Grid */}
        {!loading && !error && projects.length > 0 && (
          <motion.div
            variants={containerVariants}
            initial="hidden"
            animate="visible"
            className="grid grid-cols-1 md:grid-cols-2 gap-8"
          >
            {projects.map((project) => (
              <motion.div
                key={project.id}
                variants={itemVariants}
                className="bg-white rounded-3xl overflow-hidden shadow-soft hover:shadow-medium transition-all duration-300 group hover:-translate-y-2"
              >
                {/* Project Image */}
                <div className="relative h-80 overflow-hidden">
                  <img
                    src={project.display_image_url} // CHANGE IS HERE
                    alt={project.title}
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                  <div className="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300">
                    <ArrowRight className="w-6 h-6 text-white" />
                  </div>
                </div>

                {/* Project Content */}
                <div className="p-8">
                  <div className="flex justify-between items-start mb-4">
                    <h3 className="text-xl font-bold text-neutral-800 group-hover:text-primary transition-colors line-clamp-2">
                      {project.title}
                    </h3>
                    <div className="flex items-center text-primary font-semibold text-sm bg-primary/10 px-3 py-1 rounded-full">
                      <span>{project.weight}</span>
                    </div>
                  </div>

                  <div className="flex items-center text-gray-600 mb-2">
                    <MapPin className="w-4 h-4 mr-2 flex-shrink-0" />
                    <span className="text-sm">{project.location}</span>
                  </div>

                  <div className="flex items-center text-gray-600 mb-4">
                    <Calendar className="w-4 h-4 mr-2 flex-shrink-0" />
                    <span className="text-sm">{project.date}</span>
                  </div>

                  <p className="text-gray-700 text-sm leading-relaxed line-clamp-3">
                    {project.description}
                  </p>

                  <div className="mt-6">
                    <span
                      className={`inline-block px-3 py-1 rounded-full text-xs font-medium ${
                        project.category === "infrastruktur"
                          ? "bg-blue-100 text-blue-800"
                          : project.category === "bangunan"
                          ? "bg-green-100 text-green-800"
                          : "bg-orange-100 text-orange-800"
                      }`}
                    >
                      {project.category === "infrastruktur"
                        ? "Infrastruktur"
                        : project.category === "bangunan"
                        ? "Bangunan"
                        : "Sanitasi"}
                    </span>
                  </div>
                </div>
              </motion.div>
            ))}
          </motion.div>
        )}

        {/* Stats Section */}
        <motion.div
          className="mt-20 bg-gradient-to-r from-primary to-primary/90 rounded-3xl p-12 text-white"
          initial={{ opacity: 0, y: 30 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.8, delay: 0.6 }}
        >
          <div className="text-center mb-12">
            <h2 className="text-3xl font-bold mb-4">Pencapaian Kami</h2>
            <p className="text-white/90 max-w-2xl mx-auto">
              Kontribusi nyata untuk pembangunan infrastruktur desa dan
              kesejahteraan masyarakat
            </p>
          </div>

          <div className="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div className="text-center">
              <div className="text-4xl font-bold mb-2">50+</div>
              <div className="text-white/80">Proyek Selesai</div>
            </div>
            <div className="text-center">
              <div className="text-4xl font-bold mb-2">25</div>
              <div className="text-white/80">Desa Terlayani</div>
            </div>
            <div className="text-center">
              <div className="text-4xl font-bold mb-2">500+</div>
              <div className="text-white/80">Ton Material</div>
            </div>
            <div className="text-center">
              <div className="text-4xl font-bold mb-2">100%</div>
              <div className="text-white/80">Kepuasan</div>
            </div>
          </div>
        </motion.div>

        {/* CTA Section */}
        <motion.div
          className="mt-16 text-center"
          initial={{ opacity: 0, y: 30 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.8, delay: 0.8 }}
        >
          <h3 className="text-3xl font-bold text-neutral-800 mb-4">
            Punya Proyek Desa?
          </h3>
          <p className="text-gray-600 mb-8 max-w-2xl mx-auto">
            Konsultasikan kebutuhan material konstruksi proyek desa Anda dengan
            tim ahli kami. Dapatkan penawaran terbaik dan layanan profesional.
          </p>
          <button className="btn-primary-enhanced px-8 py-4 text-lg font-semibold rounded-2xl hover-lift">
            Konsultasi Gratis
          </button>
        </motion.div>
      </div>
    </div>
  );
};

export default Projects;
