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
import AdminDashboard from "./pages/AdminDashboard";
import ContentAdminDashboard from "./pages/ContentAdminDashboard";
import "./App.css";

// Komponen route yang memproteksi akses berdasarkan role
const ProtectedRoute = ({
  children,
  allowedRoles = [],
}: {
  children: React.ReactNode;
  allowedRoles: string[];
}) => {
  const { user, isAuthenticated, loading } = useAuth();

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

  if (allowedRoles.length > 0 && !allowedRoles.includes(user?.role || "")) {
    switch (user?.role) {
      case "admin":
      case "superadmin":
        return <Navigate to="/admin/dashboard" replace />;
      case "content-admin":
        return <Navigate to="/admin/content" replace />;
      default:
        return <Navigate to="/" replace />;
    }
  }

  return <>{children}</>;
};

// Komponen route untuk publik yang mengarahkan user yang sudah login
const PublicRoute = ({ children }: { children: React.ReactNode }) => {
  const { isAuthenticated, user } = useAuth();

  if (isAuthenticated && user) {
    switch (user.role) {
      case "admin":
      case "superadmin":
        return <Navigate to="/admin/dashboard" replace />;
      case "content-admin":
        return <Navigate to="/admin/content" replace />;
      default:
        return <Navigate to="/" replace />;
    }
  }

  return <>{children}</>;
};

function App() {
  return (
    <AuthProvider>
      <BrowserRouter>
        <Routes>
          {/* Login */}
          <Route
            path="/login"
            element={
              <PublicRoute>
                <Login />
              </PublicRoute>
            }
          />

          {/* Admin */}
          <Route
            path="/admin/dashboard"
            element={
              <ProtectedRoute allowedRoles={["admin", "superadmin"]}>
                <AdminDashboard />
              </ProtectedRoute>
            }
          />

          {/* Content Admin */}
          <Route
            path="/admin/content"
            element={
              <ProtectedRoute allowedRoles={["content-admin"]}>
                <ContentAdminDashboard />
              </ProtectedRoute>
            }
          />

          {/* Halaman Publik */}
          <Route
            path="/*"
            element={
              <Layout>
                <Routes>
                  <Route path="/" element={<Home />} />
                  <Route path="/products" element={<Products />} />
                  <Route path="/brands" element={<Brands />} />
                  <Route path="/services" element={<Services />} />
                  <Route path="/contact" element={<Contact />} />
                  <Route path="/team" element={<Team />} />
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
