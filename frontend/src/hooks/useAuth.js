import { useState, useEffect, createContext, useContext } from "react";
import { authService } from "../services/api";

const AuthContext = createContext();

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error("useAuth must be used within an AuthProvider");
  }
  return context;
};

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const [isAuthenticated, setIsAuthenticated] = useState(false);

  useEffect(() => {
    checkAuth();
  }, []);

  const checkAuth = async () => {
    try {
      const token = localStorage.getItem("auth_token");
      if (token) {
        const response = await authService.getUser();
        setUser(response.data.data);
        setIsAuthenticated(true);
      }
    } catch (error) {
      localStorage.removeItem("auth_token");
    } finally {
      setLoading(false);
    }
  };

  const login = async (credentials) => {
    try {
      console.log("Attempting login with credentials:", credentials); // Debug log
      
      // Get CSRF token first
      try {
        await authService.getCsrf();
      } catch (csrfError) {
        console.log("CSRF token already available or not needed");
      }
      
      const response = await authService.login(credentials);
      console.log("API response:", response.data); // Debug log

      const { user, token, redirectUrl } = response.data.data;

      if (token) {
        localStorage.setItem("auth_token", token);
        console.log("Token stored in localStorage"); // Debug log
      }
      setUser(user);
      setIsAuthenticated(true);
      console.log("User authenticated:", user); // Debug log

      return { 
        success: true, 
        redirectUrl,
        data: response.data // Include full response data for fallback
      };
    } catch (error) {
      console.error("Login error in useAuth:", error); // Debug log
      return {
        success: false,
        error: error.response?.data?.error || error.response?.data?.message || "Login failed",
      };
    }
  };

  const register = async (userData) => {
    try {
      const response = await authService.register(userData);
      const { user, token } = response.data.data;

      localStorage.setItem("auth_token", token);
      setUser(user);
      setIsAuthenticated(true);

      return { success: true };
    } catch (error) {
      return {
        success: false,
        error: error.response?.data?.message || "Registration failed",
      };
    }
  };

  const logout = async () => {
    try {
      await authService.logout();
    } catch (error) {
      console.error("Logout error:", error);
    } finally {
      localStorage.removeItem("auth_token");
      setUser(null);
      setIsAuthenticated(false);
    }
  };

  const value = {
    user,
    loading,
    isAuthenticated,
    login,
    register,
    logout,
    checkAuth,
  };

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};
