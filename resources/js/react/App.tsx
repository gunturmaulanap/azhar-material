// resources/js/react/App.tsx
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
import Services from "./pages/Services";
import Contact from "./pages/Contact";
import Team from "./pages/Team";
import Login from "./pages/Login";

declare global {
  interface Window {
    __BOOT__?: { isAuth: boolean; user?: any } | undefined;
  }
}
const BOOT = typeof window !== "undefined" ? window.__BOOT__ : undefined;

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
    <AuthProvider boot={BOOT}>
      <BrowserRouter>
        <ErrorBoundary>
          <ScrollToTop smooth delay={0} />
          <Notifications />

          <Routes>
            {/* Semua halaman publik menggunakan shell Layout (lihat <Outlet/> di Layout.tsx) */}
            <Route element={<Layout />}>
              <Route index element={<Home />} />
              <Route path="products" element={<Products />} />
              <Route path="projects" element={<Projects />} />
              <Route path="services" element={<Services />} />
              <Route path="contact" element={<Contact />} />
              <Route path="team" element={<Team />} />

              <Route
                path="login"
                element={
                  <PublicRoute>
                    <Login />
                  </PublicRoute>
                }
              />

              {/* fallback */}
              <Route path="*" element={<Navigate to="/" replace />} />
            </Route>

            {/* contoh privat (kalau nanti ada)
            <Route
              path="admin/dashboard"
              element={
                <ProtectedRoute><AdminDashboard/></ProtectedRoute>
              }
            />
            */}
          </Routes>
        </ErrorBoundary>
      </BrowserRouter>
    </AuthProvider>
  );
}
export default App;
