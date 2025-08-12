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

        // 1) Coba ambil user via endpoint yang memakai session (withCredentials true di axios)
        // Ini akan berhasil jika login via session (guard web/customer)
        try {
          const sessionResp = await authService.getUser();
          if (sessionResp.data?.success && sessionResp.data?.data?.user) {
            setUser(sessionResp.data.data.user);
            setIsAuthenticated(true);
            setLoading(false);
            return;
          }
        } catch (e) {
          // Abaikan, lanjut gunakan token jika ada
        }

        // 2) Jika ada token, verify via endpoint protected
        if (token) {
          try {
            const verifyResp = await authService.verifyToken(token);
            if (verifyResp.data?.success && verifyResp.data?.data?.user) {
              setUser(verifyResp.data.data.user);
              setIsAuthenticated(true);
            } else {
              Cookies.remove("token");
              setUser(null);
              setIsAuthenticated(false);
            }
          } catch (apiError) {
            Cookies.remove("token");
            setUser(null);
            setIsAuthenticated(false);
          }
        } else {
          setUser(null);
          setIsAuthenticated(false);
        }
      } catch (error) {
        Cookies.remove("token");
        setUser(null);
        setIsAuthenticated(false);
      } finally {
        setLoading(false);
      }
    };

    const timeoutId = setTimeout(initializeAuth, 50);
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

      // Simpan token tanpa memaksa domain, agar sesuai dengan current host
      Cookies.set("token", token, {
        expires: 7,
        sameSite: "Lax",
        path: "/",
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
        await authService.logout();
      }
    } catch (error) {
      // Lanjutkan pembersihan client-side meski API gagal
    } finally {
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
