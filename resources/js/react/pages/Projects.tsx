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
  image?: string;
  display_image_url?: string;
  description: string;
}

const API_BASE =
  import.meta.env.VITE_API_BASE_URL?.replace(/\/$/, "") ||
  window.location.origin.replace(/\/$/, "");

const Projects: React.FC = () => {
  const [projects, setProjects] = useState<Project[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [selectedCategory, setSelectedCategory] = useState("all");
  const [isFiltersOpen, setIsFiltersOpen] = useState(false);

  const categories = [
    { id: "all", name: "Semua Kategori" },
    { id: "infrastruktur", name: "Infrastruktur" },
    { id: "bangunan", name: "Bangunan" },
    { id: "sanitasi", name: "Sanitasi" },
  ];

  useEffect(() => {
    const controller = new AbortController();
    const timer = setTimeout(() => controller.abort(), 10000);

    (async () => {
      setLoading(true);
      setError(null);

      const params = new URLSearchParams({ status: "published" });
      if (selectedCategory !== "all") {
        params.set("category", encodeURIComponent(selectedCategory));
      }
      const apiUrl = `${API_BASE}/api/projects?${params.toString()}`;

      try {
        const res = await fetch(apiUrl, {
          signal: controller.signal,
          credentials: "include",
          cache: "no-store",
        });
        if (!res.ok)
          throw new Error(`Gagal mengambil data proyek (${res.status})`);
        const data = await res.json();
        setProjects(Array.isArray(data.data) ? data.data : []);
      } catch (e: any) {
        setError(
          e?.name === "AbortError"
            ? "Permintaan timeout, coba lagi."
            : e?.message || "Terjadi kesalahan."
        );
      } finally {
        clearTimeout(timer);
        setLoading(false);
      }
    })();

    return () => {
      clearTimeout(timer);
      controller.abort();
    };
  }, [selectedCategory]);

  const containerVariants = {
    hidden: { opacity: 0 },
    visible: { opacity: 1, transition: { staggerChildren: 0.1 } },
  };
  const itemVariants = {
    hidden: { opacity: 0, y: 20 },
    visible: { opacity: 1, y: 0, transition: { duration: 0.5 } },
  };

  const getImage = (p: Project) =>
    p.display_image_url || p.image || "/images/fallback.jpg";

  return (
    <div className="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
      {/* Hero */}
      <motion.div
        className="relative bg-gradient-to-r from-primary/90 to-primary py-24 overflow-hidden"
        initial={{ opacity: 0 }}
        animate={{ opacity: 1 }}
        transition={{ duration: 0.8 }}
      >
        <div className="absolute inset-0 bg-black/10" />
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
              selesaikan…
            </p>
          </motion.div>
        </div>
      </motion.div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        {/* Filter */}
        <motion.div
          className="mb-12"
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.6, delay: 0.4 }}
        >
          <div className="bg-white rounded-2xl shadow-soft p-6 border border-gray-200">
            <button
              onClick={() => setIsFiltersOpen((v) => !v)}
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
                  {categories.map((c) => (
                    <button
                      key={c.id}
                      onClick={() => setSelectedCategory(c.id)}
                      className={`px-6 py-2 rounded-full font-medium transition-all duration-200 ${
                        selectedCategory === c.id
                          ? "bg-primary text-white shadow-medium"
                          : "bg-gray-100 text-gray-700 hover:bg-gray-200"
                      }`}
                    >
                      {c.name}
                    </button>
                  ))}
                </div>
              </motion.div>
            )}
          </div>
        </motion.div>

        {/* Status */}
        {loading && (
          <div className="text-center text-gray-500 text-lg my-12">
            Mengambil data proyek…
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

        {/* Grid */}
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
                <div className="relative h-80 overflow-hidden">
                  <img
                    src={getImage(project)}
                    alt={project.title}
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                    onError={(e) =>
                      (e.currentTarget.src = "/images/fallback.jpg")
                    }
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                  <div className="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300">
                    <ArrowRight className="w-6 h-6 text-white" />
                  </div>
                </div>
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

        {/* …(bagian bawah tetap) */}
      </div>
    </div>
  );
};

export default Projects;
