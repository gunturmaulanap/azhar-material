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
}

interface LoginResponseData {
  success: boolean;
  message: string;
  data: {
    user: User;
    role: string;
    redirect_url: string;
  };
  token?: string; // no longer used on client
  error?: string;
}

interface AuthContextType {
  user: User | null;
  isAuthenticated: boolean;
  ready: boolean; // SPA hydration flag
  login: (credentials: {
    username: string;
    password: string;
    login_type: string;
    role?: string;
  }) => Promise<LoginResponseData>;
  logout: (skipApiCall?: boolean) => Promise<void>;
  refresh: () => Promise<void>;
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
  const [ready, setReady] = useState(false);

  // Hydrate from server session on mount
  useEffect(() => {
    let isMounted = true;
    const hydrate = async () => {
      try {
        // Fetch current user from server session (no cache)
        const sessionResp = await authService.getUser();
        const serverUser: User | null = sessionResp?.data?.data?.user ?? null;
        if (!isMounted) return;
        if (serverUser && serverUser.id) {
          setUser(serverUser);
          setIsAuthenticated(true);
        } else {
          setUser(null);
          setIsAuthenticated(false);
        }
      } catch (_) {
        if (!isMounted) return;
        setUser(null);
        setIsAuthenticated(false);
      } finally {
        if (isMounted) setReady(true);
      }
    };
    hydrate();

    return () => {
      isMounted = false;
    };
  }, []);

  // Revalidate auth on back/forward cache restore (Safari/iOS)
  useEffect(() => {
    const handlePageShow = (event: PageTransitionEvent) => {
      // @ts-ignore
      if (event.persisted === true) {
        refresh();
      }
    };
    window.addEventListener('pageshow', handlePageShow as any);
    return () => window.removeEventListener('pageshow', handlePageShow as any);
  }, []);

  const refresh = async () => {
    try {
      const sessionResp = await authService.getUser();
      const serverUser: User | null = sessionResp?.data?.data?.user ?? null;
      if (serverUser && serverUser.id) {
        setUser(serverUser);
        setIsAuthenticated(true);
      } else {
        setUser(null);
        setIsAuthenticated(false);
      }
    } catch (_) {
      setUser(null);
      setIsAuthenticated(false);
    }
  };

  const login = async (credentials: {
    username: string;
    password: string;
    login_type: string;
    role?: string;
  }): Promise<LoginResponseData> => {
    // Ensure CSRF is fresh before login (Safari fix)
    try { await authService.getSanctumCookie(); } catch (_) {}

    const response = await authService.login(credentials);

    // Do NOT store bearer token on client. Server session cookie is authoritative.

    // After server login succeeds, immediately rehydrate from /api/me
    await refresh();

    return response.data as LoginResponseData;
  };

  const logout = async (skipApiCall: boolean = false) => {
    try {
      if (!skipApiCall) {
        await authService.logout();
      }
    } catch (_) {
      // ignore
    } finally {
      setUser(null);
      setIsAuthenticated(false);
      // Pre-fetch fresh CSRF cookie for next login (Safari role switch)
      try { await authService.getSanctumCookie(); } catch (_) {}
    }
  };

  const value: AuthContextType = {
    user,
    isAuthenticated,
    ready,
    login,
    logout,
    refresh,
  };

  return (
    <AuthContext.Provider value={value}>
      {children}
    </AuthContext.Provider>
  );
};
