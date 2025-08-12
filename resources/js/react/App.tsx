import React from "react";
import { BrowserRouter, Routes, Route, Navigate, useLocation } from "react-router-dom";
import { useAuth, AuthProvider } from "./hooks/useAuth"; // relatif path
import LoadingSpinner from "./components/LoadingSpinner";
import Layout from "./components/Layout"; // Pastikan file ini ada
import Notifications from "./components/Notifications";
import ScrollToTop from "./components/ScrollToTop";
import ErrorBoundary from "./components/ErrorBoundary";
import Home from "./pages/Home";
import Products from "./pages/Products";
import Projects from "./pages/Projects";
import Brands from "./pages/Brands";
import Services from "./pages/Services";
import Contact from "./pages/Contact";
import Team from "./pages/Team";
import Login from "./pages/Login";
// import AdminDashboard from "./pages/AdminDashboard"; // Keep commented as per original
// import ContentAdminDashboard from "./pages/ContentAdminDashboard"; // Keep as per original

// Komponen route yang memproteksi akses berdasarkan autentikasi
const ProtectedRoute = ({ children }: { children: React.ReactNode }) => {
  const { isAuthenticated, ready } = useAuth();
  const location = useLocation();

  if (!ready) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <LoadingSpinner
          size="lg"
          text="Memuat aplikasi..."
          className="p-8"
        />
      </div>
    );
  }

  if (!isAuthenticated) {
    return <Navigate to="/login" replace state={{ from: location.pathname }} />;
  }

  return <>{children}</>;
};

// Komponen route untuk publik yang mengarahkan user yang sudah login
const PublicRoute = ({ children }: { children: React.ReactNode }) => {
  const { isAuthenticated, ready } = useAuth();

  if (!ready) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <LoadingSpinner
          size="lg"
          text="Memuat aplikasi..."
          className="p-8"
        />
      </div>
    );
  }

  if (isAuthenticated) {
    return <Navigate to="/" replace />;
  }

  return <>{children}</>;
};

function App() {
  return (
    <ErrorBoundary>
      <AuthProvider>
        <BrowserRouter>
          {/* Automatically scroll to top on route changes */}
          <ScrollToTop smooth={true} delay={0} />
          <Notifications />
          <Routes>
          {/* Rute untuk halaman yang memerlukan autentikasi (jika ada halaman React admin) */}
          {/* Contoh: Jika ada dashboard admin React terpisah */}
          {/* <Route
            path="/admin/dashboard"
            element={
              <ProtectedRoute>
                <AdminDashboard />
              </ProtectedRoute>
            }
          /> */}
          {/* Contoh: Content Admin Dashboard React */}
          {/* <Route
            path="/content-admin-dashboard" // Ganti dengan rute React yang sesuai jika ada
            element={
              <ProtectedRoute>
                <ContentAdminDashboard />
              </ProtectedRoute>
            }
          /> */}

          {/* Rute Publik yang dibungkus oleh Layout */}
          {/* Ini akan menangani semua rute yang tidak cocok di atas */}
          <Route
            path="/*" // Catch-all route for public pages
            element={
              <Layout>
                <Routes>
                  <Route path="/" element={<Home />} />
                  <Route path="/products" element={<Products />} />
                  <Route path="/projects" element={<Projects />} />
                  <Route path="/services" element={<Services />} />
                  <Route path="/contact" element={<Contact />} />
                  <Route path="/team" element={<Team />} />
                  <Route path="/login" element={<PublicRoute><Login /></PublicRoute>} />
                  {/* Tambahkan rute publik lainnya di sini */}
                </Routes>
              </Layout>
            }
          />
          </Routes>
        </BrowserRouter>
      </AuthProvider>
    </ErrorBoundary>
  );
}

export default App;
