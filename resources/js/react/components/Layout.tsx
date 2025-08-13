import React, { useState, useEffect, useMemo } from "react";
import { Link, useLocation, useNavigate } from "react-router-dom";
import {
  Menu,
  X,
  Facebook,
  Instagram,
  Mail,
  User,
  LogOut,
  LayoutDashboard,
} from "lucide-react";
import { useAuth } from "../hooks/useAuth";
import { Button } from "./ui/button";

interface LayoutProps {
  children: React.ReactNode;
}

const Layout: React.FC<LayoutProps> = ({ children }) => {
  const [isScrolled, setIsScrolled] = useState(false);
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const [showUserMenu, setShowUserMenu] = useState(false);

  const location = useLocation();
  const navigate = useNavigate();

  const { user, isAuthenticated, logout, loading } = useAuth();

  // 👉 compute AFTER we have user from context
  const displayName = useMemo(
    () => user?.name || user?.username || "User",
    [user]
  );

  // Debounced scroll (ringan)
  useEffect(() => {
    let t: any;
    const onScroll = () => {
      clearTimeout(t);
      t = setTimeout(() => setIsScrolled(window.scrollY > 50), 50);
    };
    window.addEventListener("scroll", onScroll, { passive: true });
    onScroll();
    return () => {
      clearTimeout(t);
      window.removeEventListener("scroll", onScroll);
    };
  }, []);

  // Tutup mobile menu saat pindah halaman
  useEffect(() => {
    setIsMobileMenuOpen(false);
    setShowUserMenu(false);
  }, [location.pathname]);

  // Sinkronisasi logout antar-tab
  useEffect(() => {
    const onStorage = (e: StorageEvent) => {
      if (e.key === "logout-broadcast") {
        // Jangan panggil API lagi; cukup cleanup lokal
        logout(true);
        navigate("/", { replace: true });
      }
    };
    window.addEventListener("storage", onStorage);
    return () => window.removeEventListener("storage", onStorage);
  }, [logout, navigate]);

  const handleLogout = async () => {
    // Broadcast ke tab lain
    localStorage.setItem("logout-broadcast", String(Date.now()));
    try {
      await logout(); // API + cleanup
    } finally {
      // Redirect halus tanpa reload penuh, lalu bersihkan broadcast
      navigate("/", { replace: true });
      setTimeout(() => localStorage.removeItem("logout-broadcast"), 800);
    }
  };

  // Route dashboard per role (selaras backend)
  const getDashboardRoute = (role: string, userId?: number) => {
    const routes: Record<string, string> = {
      customer: userId ? `/customer/${userId}` : "/customer",
      super_admin: "/superadmin/dashboard",
      driver: "/driver/pengiriman-barang",
      admin: "/admin/transactions/create",
      "content-admin": "/content-admin/analytics",
      owner: "/owner/laporan-penjualan",
    };
    return routes[role] ?? "/";
  };

  const getDashboardLabel = (role: string) => {
    const labels: Record<string, string> = {
      customer: "My Transactions",
      super_admin: "Dashboard",
      driver: "Dashboard",
      admin: "Admin Panel",
      "content-admin": "Content Panel",
      owner: "Owner Panel",
    };
    return labels[role] ?? "Dashboard";
  };

  const navigation = useMemo(
    () => [
      { name: "Home", href: "/" },
      { name: "Projects", href: "/projects" },
      { name: "Products", href: "/products" },
      { name: "Services", href: "/services" },
      { name: "Contact", href: "/contact" },
      // { name: "Team", href: "/team" },
    ],
    []
  );
  // --- Title mapping per route ---
  const BASE_TITLE = "AZHAR";

  const TITLE_MAP: Record<string, string> = {
    "/": `Home – ${BASE_TITLE}`,
    "/projects": `Projects – ${BASE_TITLE}`,
    "/products": `Product Catalog – ${BASE_TITLE}`,
    "/services": `Services – ${BASE_TITLE}`,
    "/contact": `Contact – ${BASE_TITLE}`,
    "/login": `Login – ${BASE_TITLE}`,
  };

  const getTitleFromPath = (path: string) => {
    // exact match dulu
    if (TITLE_MAP[path]) return TITLE_MAP[path];

    // prefix match untuk grup route
    if (path.startsWith("/customer")) return `My Transactions – ${BASE_TITLE}`;
    if (path.startsWith("/admin")) return `Admin Panel – ${BASE_TITLE}`;
    if (path.startsWith("/superadmin")) return `Dashboard – ${BASE_TITLE}`;
    if (path.startsWith("/content-admin"))
      return `Content Panel – ${BASE_TITLE}`;
    if (path.startsWith("/owner")) return `Owner Panel – ${BASE_TITLE}`;
    if (path.startsWith("/driver")) return `Driver Dashboard – ${BASE_TITLE}`;

    // fallback
    return BASE_TITLE;
  };

  // set title tiap kali route berubah
  useEffect(() => {
    document.title = getTitleFromPath(location.pathname);
  }, [location.pathname]);

  // Komponen kecil: placeholder agar tidak kedip saat loading
  const AuthPlaceholder = () => (
    <div className="hidden md:flex items-center space-x-4">
      <div className="h-9 w-28 rounded-md bg-gray-200/70 animate-pulse" />
    </div>
  );
  type ImgProps = React.ImgHTMLAttributes<HTMLImageElement> & {
    fetchpriority?: "high" | "low" | "auto";
  };

  const Img: React.FC<ImgProps> = ({ fetchpriority, ...rest }) => (
    <img {...rest} {...({ fetchpriority } as any)} />
  );
  return (
    <div className="min-h-screen bg-neutral-50">
      {/* Header */}
      <header
        className={`fixed top-0 w-full z-50 transition-all duration-300 ${
          isScrolled ? "bg-white shadow-lg" : "bg-transparent"
        }`}
      >
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center justify-between h-16">
            <Link
              to="/"
              aria-label="Azhar Material"
              className="flex items-center gap-3"
            >
              <span className="inline-flex items-center justify-center px-1.5 py-1">
                <img
                  src="/img/header.png?v=2"
                  alt="Azhar Material"
                  className="h-7 w-auto sm:h-8 md:h-10 lg:h-11 object-contain"
                  loading="eager"
                  // @ts-expect-error: non-React attribute, but valid HTML
                  fetchpriority="high"
                />
              </span>
            </Link>
            {/* Desktop Navigation */}
            <nav className="hidden md:flex items-center space-x-8">
              {navigation.map((item) => (
                <Link
                  key={item.name}
                  to={item.href}
                  className={`relative px-3 py-2 text-sm font-medium transition-colors duration-200 group ${
                    location.pathname === item.href
                      ? "text-primary"
                      : isScrolled
                      ? "text-gray-700 hover:text-primary"
                      : "text-gray-400 hover:text-accent"
                  }`}
                >
                  {item.name}
                  <span className="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full" />
                </Link>
              ))}
            </nav>
            {/* Auth Section */}
            {loading ? (
              <AuthPlaceholder />
            ) : (
              <div className="hidden md:flex items-center space-x-4">
                {isAuthenticated ? (
                  <>
                    {/* Dashboard Button */}
                    <Button
                      variant="ghost"
                      className="flex items-center space-x-2 text-primary hover:bg-primary hover:text-white"
                      onClick={() => {
                        // Redirect full page ke route dashboard sesuai role
                        const url = getDashboardRoute(
                          user?.role || "customer",
                          user?.id
                        );
                        // GET sudah cukup; CSRF tidak diperlukan
                        window.location.href = url;
                      }}
                    >
                      <LayoutDashboard className="h-4 w-4" />
                      <span>{getDashboardLabel(user?.role || "customer")}</span>
                    </Button>

                    {/* User Menu */}
                    <div className="relative">
                      <Button
                        variant="ghost"
                        className="flex items-center space-x-2"
                        onClick={() => setShowUserMenu((v) => !v)}
                        aria-haspopup="menu"
                        aria-expanded={showUserMenu}
                      >
                        <User className="h-4 w-4" />
                        <span>{displayName}</span>{" "}
                      </Button>
                      {showUserMenu && (
                        <div
                          className="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                          role="menu"
                        >
                          <button
                            onClick={handleLogout}
                            className="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            role="menuitem"
                          >
                            <LogOut className="mr-2 h-4 w-4" />
                            <span>Logout</span>
                          </button>
                        </div>
                      )}
                    </div>
                  </>
                ) : (
                  <Link to="/login">
                    <Button
                      variant="outline"
                      className="border-primary text-primary hover:bg-primary hover:text-white"
                    >
                      Login
                    </Button>
                  </Link>
                )}
              </div>
            )}
            {/* Mobile Menu Button */}
            <button
              className="md:hidden p-2"
              onClick={() => setIsMobileMenuOpen((v) => !v)}
              aria-label="Toggle menu"
              aria-expanded={isMobileMenuOpen}
            >
              {isMobileMenuOpen ? (
                <X className="h-6 w-6 text-gray-700" />
              ) : (
                <Menu
                  className={`h-6 w-6 ${
                    isScrolled ? "text-gray-700" : "text-gray-600"
                  }`}
                />
              )}
            </button>
          </div>

          {/* Mobile Navigation */}
          {isMobileMenuOpen && (
            <div className="md:hidden bg-white border-t shadow-lg">
              <div className="px-2 pt-2 pb-3 space-y-1">
                {navigation.map((item) => (
                  <Link
                    key={item.name}
                    to={item.href}
                    className={`block px-3 py-2 text-base font-medium rounded-md transition-colors duration-200 ${
                      location.pathname === item.href
                        ? "text-primary bg-accent"
                        : "text-gray-700 hover:text-primary hover:bg-accent"
                    }`}
                  >
                    {item.name}
                  </Link>
                ))}

                {/* Mobile Auth */}
                <div className="pt-4 border-t border-gray-200">
                  {loading ? (
                    <div className="px-3 py-2">
                      <div className="h-9 w-28 rounded-md bg-gray-200/70 animate-pulse" />
                    </div>
                  ) : isAuthenticated ? (
                    <div className="px-3 py-2 space-y-2">
                      <p className="text-sm text-gray-600 mb-2">
                        Welcome, {displayName}{" "}
                      </p>

                      <Button
                        variant="ghost"
                        size="sm"
                        className="w-full flex items-center justify-start space-x-2 text-primary hover:bg-primary hover:text-white mb-2"
                        onClick={() => {
                          const url = getDashboardRoute(
                            user?.role || "customer",
                            user?.id
                          );
                          window.location.href = url;
                        }}
                      >
                        <LayoutDashboard className="h-4 w-4" />
                        <span>
                          {getDashboardLabel(user?.role || "customer")}
                        </span>
                      </Button>

                      <Button
                        variant="outline"
                        size="sm"
                        onClick={handleLogout}
                        className="w-full"
                      >
                        <LogOut className="mr-2 h-4 w-4" />
                        Logout
                      </Button>
                    </div>
                  ) : (
                    <Link
                      to="/login"
                      className="block px-3 py-2 text-base font-medium rounded-md text-primary hover:bg-accent"
                    >
                      Login
                    </Link>
                  )}
                </div>
              </div>
            </div>
          )}
        </div>
      </header>

      {/* Main Content */}
      <main className="pt-16">{children}</main>

      {/* Footer */}
      <footer className="bg-neutral-800 text-white py-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {/* Left Column - Logo & Tagline */}
            <div>
              <h3 className="text-2xl font-bold mb-4">Azhar Material</h3>
              <p className="text-gray-300 mb-6">
                Bahan Bangunan, Solusi Bangunan Anda.
              </p>
              <div className="flex space-x-4">
                <a
                  href="https://instagram.com/azharmaterial"
                  className="text-gray-300 hover:text-accent transition-colors duration-200"
                >
                  <Instagram className="h-5 w-5" />
                </a>
                <a
                  href="https://facebook.com/azharmaterial"
                  className="text-gray-300 hover:text-accent transition-colors duration-200"
                >
                  <Facebook className="h-5 w-5" />
                </a>
                <a
                  href="mailto:azharmaterial@gmail.com"
                  className="text-gray-300 hover:text-accent transition-colors duration-200"
                >
                  <Mail className="h-5 w-5" />
                </a>
              </div>
            </div>

            {/* Center Column - Links */}
            <div>
              <h4 className="text-lg font-semibold mb-4">Quick Links</h4>
              <ul className="space-y-2">
                <li>
                  <Link
                    to="/"
                    className="text-gray-300 hover:text-accent transition-colors duration-200"
                  >
                    About
                  </Link>
                </li>
                <li>
                  <Link
                    to="/products"
                    className="text-gray-300 hover:text-accent transition-colors duration-200"
                  >
                    Products
                  </Link>
                </li>
                <li>
                  <Link
                    to="/services"
                    className="text-gray-300 hover:text-accent transition-colors duration-200"
                  >
                    Customer Service
                  </Link>
                </li>
                <li>
                  <Link
                    to="/contact"
                    className="text-gray-300 hover:text-accent transition-colors duration-200"
                  >
                    Contact
                  </Link>
                </li>
              </ul>
            </div>

            {/* Right Column - Contact Info */}
            <div>
              <h4 className="text-lg font-semibold mb-4">Contact Info</h4>
              <div className="text-gray-300 space-y-2">
                <p>WhatsApp: 081392854911</p>
                <p>Email: azharmaterial@gmail.com</p>
                <p className="text-sm mt-4">
                  © 2024 Azhar Material. All rights reserved.
                </p>
              </div>
            </div>
          </div>
        </div>
      </footer>
    </div>
  );
};

export default Layout;
