import React from 'react';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { useAuth } from './hooks/useAuth';
import Layout from './components/Layout';
import LoadingSpinner from './components/LoadingSpinner';
import Home from './pages/Home';
import Products from './pages/Products';
import Brands from './pages/Brands';
import Services from './pages/Services';
import Contact from './pages/Contact';
import Team from './pages/Team';
import Login from './pages/Login';
import AdminDashboard from './pages/AdminDashboard';
import ContentAdminDashboard from './pages/ContentAdminDashboard';
import './App.css';

// Protected Route Component
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

  if (allowedRoles.length > 0 && !allowedRoles.includes(user?.role)) {
    // Redirect based on user role
    switch (user?.role) {
      case 'admin':
      case 'superadmin':
        return <Navigate to="/admin/dashboard" replace />;
      case 'content-admin':
        return <Navigate to="/admin/content" replace />;
      default:
        return <Navigate to="/" replace />;
    }
  }

  return children;
};

// Public Route Component (redirects if already authenticated)
const PublicRoute = ({ children }: { children: React.ReactNode }) => {
  const { isAuthenticated, user } = useAuth();

  if (isAuthenticated && user) {
    // Redirect based on user role
    switch (user.role) {
      case 'admin':
      case 'superadmin':
        return <Navigate to="/admin/dashboard" replace />;
      case 'content-admin':
        return <Navigate to="/admin/content" replace />;
      default:
        return <Navigate to="/" replace />;
    }
  }

  return children;
};

function AppContent() {
  return (
    <BrowserRouter>
      <Routes>
        {/* Login Route */}
        <Route 
          path="/login" 
          element={
            <PublicRoute>
              <Login />
            </PublicRoute>
          } 
        />

        {/* Admin Dashboard Routes */}
        <Route 
          path="/admin/dashboard" 
          element={
            <ProtectedRoute allowedRoles={['admin', 'superadmin']}>
              <AdminDashboard />
            </ProtectedRoute>
          } 
        />

        {/* Content Admin Dashboard Route */}
        <Route 
          path="/admin/content" 
          element={
            <ProtectedRoute allowedRoles={['content-admin']}>
              <ContentAdminDashboard />
            </ProtectedRoute>
          } 
        />

        {/* Public Company Profile Routes */}
        <Route path="/*" element={
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
        } />
      </Routes>
    </BrowserRouter>
  );
}

function App() {
  return <AppContent />;
}

export default App;
