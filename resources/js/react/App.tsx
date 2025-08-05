import React from "react";
import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import { useAuth, AuthProvider } from "./hooks/useAuth"; // relatif path
import LoadingSpinner from "./components/LoadingSpinner";
import Layout from "./components/Layout"; // Pastikan file ini ada
import Home from "./pages/Home";
import Products from "./pages/Products";
import Brands from "./pages/Brands";
import Services from "./pages/Services";
import Contact from "./pages/Contact";
import Team from "./pages/Team";
import Login from "./pages/Login";
// import AdminDashboard from "./pages/AdminDashboard"; // Keep commented as per original
// import ContentAdminDashboard from "./pages/ContentAdminDashboard"; // Keep as per original

// Komponen route yang memproteksi akses berdasarkan autentikasi
// Tujuan: Hanya izinkan akses jika user terautentikasi.
// Semua user yang terautentikasi akan tetap di landing page React,
// kecuali jika Laravel mengarahkan mereka ke dashboard Livewire.
const ProtectedRoute = ({ children }: { children: React.ReactNode }) => {
  const { isAuthenticated, loading } = useAuth();

  if (loading) {
    return (
      <div className="min-h-screen bg-gray-50">
        <LoadingSpinner
          size="lg"
          text="Memuat aplikasi..."
          className="min-h-screen"
        />
      </div>
    );
  }

  if (!isAuthenticated) {
    return <Navigate to="/login" replace />;
  }

  // Jika terautentikasi, izinkan akses ke children.
  // Redirect ke dashboard spesifik (Livewire) akan ditangani oleh Laravel setelah SSO.
  // Di sini, kita hanya memastikan mereka login.
  return <>{children}</>;
};

// Komponen route untuk publik yang mengarahkan user yang sudah login
// Tujuan: Jika user sudah login, jangan biarkan mereka mengakses halaman login lagi.
const PublicRoute = ({ children }: { children: React.ReactNode }) => {
  const { isAuthenticated, loading } = useAuth();

  if (loading) {
    return (
      <div className="min-h-screen bg-gray-50">
        <LoadingSpinner
          size="lg"
          text="Memuat aplikasi..."
          className="min-h-screen"
        />
      </div>
    );
  }

  if (isAuthenticated) {
    // Jika user sudah login, arahkan mereka ke halaman utama React
    return <Navigate to="/" replace />;
  }

  return <>{children}</>;
};

function App() {
  return (
    <AuthProvider>
      <BrowserRouter>
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
                  <Route path="/brands" element={<Brands />} />
                  <Route path="/services" element={<Services />} />
                  <Route path="/contact" element={<Contact />} />
                  <Route path="/team" element={<Team />} />
                  <Route path="/login" element={<Login />} />
                  {/* Tambahkan rute publik lainnya di sini */}
                </Routes>
              </Layout>
            }
          />
        </Routes>
      </BrowserRouter>
    </AuthProvider>
  );
}

export default App;
