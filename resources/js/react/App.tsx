import React from "react";
import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import { useAuth, AuthProvider } from "./hooks/useAuth";
import LoadingSpinner from "./components/LoadingSpinner";
import Layout from "./components/Layout";
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

// ==== Bootstrap auth dari server (diisi di react.blade.php) ====
declare global {
  interface Window {
    __BOOT__?: { isAuth: boolean; user?: any } | undefined;
  }
}
const BOOT = typeof window !== "undefined" ? window.__BOOT__ : undefined;
// ===============================================================

/** Route guard untuk halaman privat (jika nanti ada). */
const ProtectedRoute = ({ children }: { children: React.ReactNode }) => {
  const { isAuthenticated, loading } = useAuth();
  if (loading) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <LoadingSpinner size="lg" text="Memuat aplikasi..." className="p-8" />
      </div>
    );
  }
  return isAuthenticated ? <>{children}</> : <Navigate to="/login" replace />;
};

/** Route publik: kalau user sudah login, jangan ke /login lagi. */
const PublicRoute = ({ children }: { children: React.ReactNode }) => {
  const { isAuthenticated, loading } = useAuth();
  if (loading) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <LoadingSpinner size="lg" text="Memuat aplikasi..." className="p-8" />
      </div>
    );
  }
  return isAuthenticated ? <Navigate to="/" replace /> : <>{children}</>;
};

function App() {
  return (
    <ErrorBoundary>
      {/* BOOT disalurkan ke AuthProvider agar no-flicker setelah refresh */}
      <AuthProvider boot={BOOT}>
        <BrowserRouter>
          <ScrollToTop smooth delay={0} />
          <Notifications />

          {/* Semua halaman publik dibungkus Layout supaya header/footer konsisten */}
          <Routes>
            <Route
              path="/*"
              element={
                <Layout>
                  <Routes>
                    <Route path="/" element={<Home />} />
                    <Route path="/products" element={<Products />} />
                    <Route path="/projects" element={<Projects />} />
                    <Route path="/services" element={<Services />} />
                    <Route path="/contact" element={<Contact />} />
                    <Route path="/team" element={<Team />} />
                    <Route
                      path="/login"
                      element={
                        <PublicRoute>
                          <Login />
                        </PublicRoute>
                      }
                    />
                    {/* fallback unknown public routes → home */}
                    <Route path="*" element={<Navigate to="/" replace />} />
                  </Routes>
                </Layout>
              }
            />

            {/* Contoh halaman privat React (kalau nanti ada):
            <Route
              path="/admin/dashboard"
              element={
                <ProtectedRoute>
                  <AdminDashboard />
                </ProtectedRoute>
              }
            />
            */}
          </Routes>
        </BrowserRouter>
      </AuthProvider>
    </ErrorBoundary>
  );
}

export default App;
