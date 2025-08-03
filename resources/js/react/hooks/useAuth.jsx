import { useState, useEffect, createContext, useContext } from "react";
import { authService } from "../services/api";
import Cookies from "js-cookie";

const AuthContext = createContext();

export const useAuth = () => {
  return useContext(AuthContext);
};

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const token = Cookies.get("token");
    if (token) {
      authService
        .verifyToken(token)
        .then((response) => {
          setUser(response.data.user);
          setIsAuthenticated(true);
        })
        .catch(() => {
          Cookies.remove("token");
        })
        .finally(() => {
          setLoading(false);
        });
    } else {
      setLoading(false);
    }
  }, []);

  const login = async (credentials) => {
    const response = await authService.login(credentials);
    const { token, user } = response.data;
    Cookies.set("token", token, { expires: 7 });
    setUser(user);
    setIsAuthenticated(true);
  };

  const logout = () => {
    Cookies.remove("token");
    setUser(null);
    setIsAuthenticated(false);
  };

  const register = async (userData) => {
    const response = await authService.register(userData);
    const { token, user } = response.data;
    Cookies.set("token", token, { expires: 7 });
    setUser(user);
    setIsAuthenticated(true);
  };

  const value = {
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
