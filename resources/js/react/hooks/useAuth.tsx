import React, {
  useState,
  useEffect,
  createContext,
  useContext,
  ReactNode,
} from "react";
import { authService } from "../services/api";
import Cookies from "js-cookie";
import toast from "react-hot-toast";

interface User {
  id: number;
  name: string;
  email: string;
  role: string;
  username?: string;
  // Tambahkan properti pengguna lain di sini jika diperlukan
}

interface LoginResponseData {
  success: boolean;
  message: string;
  data: {
    user: User;
    role: string;
    redirect_url: string; // Tambahkan properti ini
  };
  token: string;
  error?: string;
}

interface AuthContextType {
  user: User | null;
  isAuthenticated: boolean;
  loading: boolean;
  login: (credentials: {
    username: string;
    password: string;
    login_type: string;
    role?: string;
  }) => Promise<LoginResponseData>;
  logout: (skipApiCall?: boolean) => void;
  register: (data: any) => Promise<LoginResponseData>;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const useAuth = (): AuthContextType => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error("useAuth must be used within an AuthProvider");
  }
  return context;
};

export const AuthProvider = ({ children }: { children: ReactNode }) => {
  const [user, setUser] = useState<User | null>(null);
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [loading, setLoading] = useState(true);
  
  // Add a maximum loading time for mobile devices
  React.useEffect(() => {
    const maxLoadingTime = setTimeout(() => {
      if (loading) {
        console.warn("Authentication loading timeout - proceeding as guest");
        setLoading(false);
        setIsAuthenticated(false);
        setUser(null);
      }
    }, 10000); // 10 second maximum loading time
    
    return () => clearTimeout(maxLoadingTime);
  }, [loading]);

  // useEffect runs once on component mount to check for an existing token
  useEffect(() => {
    const initializeAuth = async () => {
      try {
        const token = Cookies.get("token");
        if (token) {
          // Set a shorter timeout for mobile compatibility
          const controller = new AbortController();
          const timeoutId = setTimeout(() => controller.abort(), 8000); // 8 second timeout
          
          try {
            // Panggil endpoint API untuk memverifikasi token dan mendapatkan data pengguna
            const response = await authService.getUser();
            clearTimeout(timeoutId);
            
            if (response.data.success) {
              const fetchedUser = response.data.data.user;
              setUser(fetchedUser);
              setIsAuthenticated(true);
            } else {
              // Jika token tidak valid, hapus
              Cookies.remove("token");
              setUser(null);
              setIsAuthenticated(false);
            }
          } catch (apiError) {
            clearTimeout(timeoutId);
            // If API call fails, don't block the UI - just assume not authenticated
            console.warn("Token verification failed, proceeding as guest:", apiError);
            Cookies.remove("token");
            setUser(null);
            setIsAuthenticated(false);
          }
        } else {
          // No token found, user is not authenticated
          setUser(null);
          setIsAuthenticated(false);
        }
      } catch (error) {
        console.warn("Auth initialization failed, user not authenticated:", error);
        // Clear any invalid tokens/cookies
        Cookies.remove("token");
        setUser(null);
        setIsAuthenticated(false);
      } finally {
        setLoading(false);
      }
    };

    // Add a small delay to prevent blocking UI render
    const timeoutId = setTimeout(initializeAuth, 100);
    return () => clearTimeout(timeoutId);
  }, []);

  // Main login function to handle API call and token storage
  const login = async (credentials: {
    username: string;
    password: string;
    login_type: string;
    role?: string;
  }): Promise<LoginResponseData> => {
    try {
      const response = await authService.login(credentials);

      const {
        token,
        data: { user, role },
      } = response.data;

      // Simpan token di cookie
      Cookies.set("token", token, {
        expires: 7,
        sameSite: "Lax",
        path: "/",
        domain:
          window.location.hostname === "0.0.0.0"
            ? ""
            : window.location.hostname,
      });

      setUser({ ...user, role });
      setIsAuthenticated(true);

      return response.data;
    } catch (error: any) {
      console.error("Login failed in AuthProvider:", error);
      setIsAuthenticated(false);
      setUser(null);
      Cookies.remove("token");
      throw error;
    }
  };

  const logout = async (skipApiCall: boolean = false) => {
    try {
      if (!skipApiCall) {
        // Panggil endpoint API untuk menghapus token di backend hanya jika tidak skip
        await authService.logout();
      }
    } catch (error) {
      if (!skipApiCall) {
        console.error("Logout API call failed:", error);
        // Don't show error toast as it might confuse users
        console.warn("API logout failed, proceeding with client-side cleanup");
      }
    } finally {
      // Hapus semua token dan cookie di frontend
      Cookies.remove("token");
      Cookies.remove("laravel_session");
      Cookies.remove("XSRF-TOKEN");
      
      // Clear additional cookies that might exist
      const cookies = document.cookie.split(";");
      cookies.forEach(cookie => {
        const eqPos = cookie.indexOf("=");
        const name = eqPos > -1 ? cookie.substr(0, eqPos).trim() : cookie.trim();
        if (name.includes('session') || name.includes('token') || name.includes('csrf')) {
          Cookies.remove(name);
          // Also try to remove with different path and domain combinations
          Cookies.remove(name, { path: '/' });
          Cookies.remove(name, { domain: window.location.hostname });
        }
      });
      
      setUser(null);
      setIsAuthenticated(false);
      
      // Don't rely on Laravel logout, just clean everything and redirect
      // This avoids the 419 CSRF token expired error
    }
  };

  const register = async (userData: any): Promise<LoginResponseData> => {
    try {
      const response = await authService.register(userData);
      const {
        token,
        data: { user, role },
      } = response.data;

      Cookies.set("token", token, {
        expires: 7,
        sameSite: "Lax",
        domain:
          window.location.hostname === "0.0.0.0"
            ? ""
            : window.location.hostname,
        path: "/",
      });

      setUser({ ...user, role });
      setIsAuthenticated(true);
      return response.data;
    } catch (error: any) {
      console.error("Register failed in AuthProvider:", error);
      setIsAuthenticated(false);
      setUser(null);
      Cookies.remove("token");
      throw error;
    }
  };

  const value: AuthContextType = {
    user,
    isAuthenticated,
    loading,
    login,
    logout,
    register,
  };

  return (
    <AuthContext.Provider value={value}>
      {children}
    </AuthContext.Provider>
  );
};
