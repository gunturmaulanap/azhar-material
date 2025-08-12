// resources/js/react/hooks/useAuth.tsx
import React, {
  useState,
  useEffect,
  createContext,
  useContext,
  ReactNode,
  useMemo,
} from "react";
import { authService } from "../services/api";
import Cookies from "js-cookie";

interface User {
  id: number;
  name: string;
  email: string;
  role: string;
  username?: string;
}

interface LoginResponseData {
  success: boolean;
  message: string;
  data: {
    user: User;
    role: string;
    redirect_url: string;
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
  setUser: (u: User | null) => void;
  refresh: () => Promise<void>;
}

type BootData = { isAuth: boolean; user?: User } | undefined;

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const useAuth = (): AuthContextType => {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error("useAuth must be used within an AuthProvider");
  return ctx;
};

type ProviderProps = { children: ReactNode; boot?: BootData };

export const AuthProvider = ({ children, boot }: ProviderProps) => {
  const [user, setUser] = useState<User | null>(boot?.user ?? null);
  const [isAuthenticated, setIsAuthenticated] = useState<boolean>(
    boot ? !!boot.isAuth : false
  );
  const [loading, setLoading] = useState<boolean>(boot ? false : true);

  // cap 10s agar tidak "loading" selamanya di mobile
  useEffect(() => {
    if (!loading) return;
    const max = setTimeout(() => setLoading(false), 10000);
    return () => clearTimeout(max);
  }, [loading]);

  // refresh session dari API
  const refresh = async () => {
    try {
      const res = await authService.getUser();
      if (res?.data?.success) {
        const fetched = res.data.data.user as User;
        setUser(fetched);
        setIsAuthenticated(true);
      } else {
        setUser(null);
        setIsAuthenticated(false);
      }
    } catch {
      setUser(null);
      setIsAuthenticated(false);
    } finally {
      setLoading(false);
    }
  };

  // jalankan refresh hanya kalau tidak ada BOOT
  useEffect(() => {
    if (!boot) {
      const t = setTimeout(refresh, 100);
      return () => clearTimeout(t);
    }
  }, [boot]); // eslint-disable-line react-hooks/exhaustive-deps

  const host = window.location.hostname;
  const cookieDomain =
    host === "0.0.0.0" || host === "localhost" || host === "127.0.0.1"
      ? undefined
      : host;

  const login = async (credentials: {
    username: string;
    password: string;
    login_type: string;
    role?: string;
  }): Promise<LoginResponseData> => {
    try {
      // pastikan CSRF cookie fresh
      await authService.ensureCsrf();

      const response = await authService.login(credentials);
      const {
        token,
        data: { user, role },
      } = response.data;

      Cookies.set("token", token, {
        expires: 7,
        sameSite: "Lax",
        path: "/",
        domain: cookieDomain,
      });

      setUser({ ...user, role });
      setIsAuthenticated(true);
      setLoading(false);

      return response.data;
    } catch (error: any) {
      setIsAuthenticated(false);
      setUser(null);
      Cookies.remove("token");
      throw error;
    }
  };

  const logout = async (skipApiCall: boolean = false) => {
    try {
      if (!skipApiCall) {
        await authService.logout().catch(() => {});
      }
    } finally {
      Cookies.remove("token");
      Cookies.remove("laravel_session");
      Cookies.remove("XSRF-TOKEN");
      document.cookie.split(";").forEach((c) => {
        const eq = c.indexOf("=");
        const name = (eq > -1 ? c.substr(0, eq) : c).trim();
        if (name.match(/session|token|csrf/i)) {
          Cookies.remove(name);
          Cookies.remove(name, { path: "/" });
          if (cookieDomain) Cookies.remove(name, { domain: cookieDomain });
        }
      });

      setUser(null);
      setIsAuthenticated(false);
      setLoading(false);

      // siap login lagi tanpa refresh
      setTimeout(() => authService.ensureCsrf().catch(() => {}), 50);
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
        domain: cookieDomain,
      });

      setUser({ ...user, role });
      setIsAuthenticated(true);
      setLoading(false);
      return response.data;
    } catch (error: any) {
      setIsAuthenticated(false);
      setUser(null);
      Cookies.remove("token");
      throw error;
    }
  };

  const value = useMemo<AuthContextType>(
    () => ({
      user,
      isAuthenticated,
      loading,
      login,
      logout,
      register,
      setUser,
      refresh,
    }),
    [user, isAuthenticated, loading]
  );

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};
