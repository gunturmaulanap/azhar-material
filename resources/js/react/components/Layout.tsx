import React, { useState, useEffect } from "react";
import { Link, useLocation, useNavigate } from "react-router-dom";
import { Menu, X, Facebook, Instagram, Mail, User, LogOut } from "lucide-react";
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
  const auth = useAuth();
  const user = auth?.user;
  const isAuthenticated = auth?.isAuthenticated;
  const logout = auth?.logout;

  useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 50);
    };
    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  const handleLogout = async () => {
    await logout();
    navigate("/");
  };

  const navigation = [
    { name: "Home", href: "/" },
    { name: "Products", href: "/products" },
    { name: "Brands", href: "/brands" },
    { name: "Services", href: "/services" },
    { name: "Contact", href: "/contact" },
    { name: "Team", href: "/team" },
    { name: "Dashboard", href: "/" },
  ];

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
            {/* Logo */}
            <Link to="/" className="flex items-center">
              <span className="text-2xl font-bold text-primary">
                Azhar Material
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
                  <span className="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
                </Link>
              ))}
            </nav>

            {/* Auth Section */}
            <div className="hidden md:flex items-center space-x-4">
              {isAuthenticated ? (
                <div className="relative">
                  <Button
                    variant="ghost"
                    className="flex items-center space-x-2"
                    onClick={() => setShowUserMenu(!showUserMenu)}
                  >
                    <User className="h-4 w-4" />
                    <span>{user?.name || "User"}</span>
                  </Button>
                  {showUserMenu && (
                    <div className="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                      <button
                        onClick={handleLogout}
                        className="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                      >
                        <LogOut className="mr-2 h-4 w-4" />
                        <span>Logout</span>
                      </button>
                    </div>
                  )}
                </div>
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

            {/* Mobile Menu Button */}
            <button
              className="md:hidden p-2"
              onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
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
                    onClick={() => setIsMobileMenuOpen(false)}
                  >
                    {item.name}
                  </Link>
                ))}

                {/* Mobile Auth */}
                <div className="pt-4 border-t border-gray-200">
                  {isAuthenticated ? (
                    <div className="px-3 py-2">
                      <p className="text-sm text-gray-600 mb-2">
                        Welcome, {user && user.name ? user.name : "User"}
                      </p>
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
                      onClick={() => setIsMobileMenuOpen(false)}
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
