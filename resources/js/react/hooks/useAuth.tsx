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
  logout: () => void;
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

  // useEffect runs once on component mount to check for an existing token
  useEffect(() => {
    const initializeAuth = async () => {
      try {
        const token = Cookies.get("token");
        if (token) {
          // Panggil endpoint API untuk memverifikasi token dan mendapatkan data pengguna
          const response = await authService.getUser();
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
        }
      } catch (error) {
        console.error("Auth initialization error:", error);
        Cookies.remove("token");
        setUser(null);
        setIsAuthenticated(false);
      } finally {
        setLoading(false);
      }
    };

    initializeAuth();
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

  const logout = async () => {
    try {
      // Panggil endpoint API untuk menghapus token di backend
      await authService.logout();
    } catch (error) {
      console.error("Logout API call failed:", error);
      toast.error("Terjadi kesalahan saat logout dari server.");
    } finally {
      // Hapus token di frontend tanpa peduli respons API
      Cookies.remove("token");
      setUser(null);
      setIsAuthenticated(false);
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
      {!loading && children}
    </AuthContext.Provider>
  );
};
