import React, { useState } from "react";
import { useAuth } from "../hooks/useAuth";
import { Button } from "../components/ui/button";
import { Input } from "../components/ui/input";
import { Label } from "../components/ui/label";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "../components/ui/card";
import { Alert, AlertDescription } from "../components/ui/alert";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "../components/ui/select";
import { useNavigate } from "react-router-dom";
import { useLocation } from "react-router-dom";
import toast, { Toaster } from "react-hot-toast";

// The Login component handles user authentication
const Login = () => {
  // State to manage form data (username, password, and role)
  const [formData, setFormData] = useState({
    username: "",
    password: "",
    role: "", // Stores the selected role from the dropdown
  });
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);

  // Get the login function from the authentication context
  const { login, isAuthenticated, user } = useAuth();
  const navigate = useNavigate();
  const location = useLocation();

  // Redirect if already authenticated
  React.useEffect(() => {
    if (isAuthenticated && user) {
      // Prefer returning to the previous route (if provided), otherwise stay on home
      const state = location.state as { from?: string } | null;
      const target = state?.from && state.from !== "/login" ? state.from : "/";
      navigate(target, { replace: true });
    }
  }, [isAuthenticated, user, navigate, location.state]);

  // Handle changes in the input fields
  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value,
    });
  };

  // Handle changes in the role select dropdown
  const handleRoleChange = (value: string) => {
    setFormData({
      ...formData,
      role: value,
    });
  };

  // Handle form submission for login
  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError("");
    setLoading(true);

    // Validate that a role has been selected
    if (!formData.role) {
      toast.error("Silakan pilih role terlebih dahulu", {
        duration: 4000,
        position: "top-center",
        style: {
          background: "#ef4444",
          color: "#fff",
          borderRadius: "8px",
        },
      });
      setLoading(false);
      return;
    }

    try {
      // Determine the login type for the API call based on the selected role
      const loginType = formData.role === "customer" ? "customer" : "user"; // tetap

      // Call the login function from useAuth, which handles the API call and cookie storage
      const result = await login({
        username: formData.username,
        password: formData.password,
        login_type: loginType,
        role: formData.role,
      });

      console.log("Login result:", result);

      if (result.success) {
        toast.success("Login berhasil! Selamat datang!", {
          duration: 2000,
          position: "top-center",
          style: {
            background: "#10b981",
            color: "#fff",
            borderRadius: "8px",
          },
        });

        // SPA-only: do not full-reload or redirect to homepage automatically
        // Prefer navigating back to the previous route that initiated login
        const state = location.state as { from?: string } | null;
        const target = state?.from && state.from !== "/login" ? state.from : "/";
        navigate(target, { replace: true });
      } else {
        // --- START: Perubahan di sini untuk pesan error lebih spesifik ---
        let displayMessage: string;
        const backendError = result.error; // Ambil pesan error dari backend

        switch (backendError) {
          case "Username tidak ditemukan.":
            displayMessage = "Username tidak ditemukan. Mohon periksa kembali.";
            break;
          case "Password salah.":
            displayMessage = "Password salah. Mohon periksa kembali.";
            break;
          case "Role yang digunakan salah.": // Sesuaikan dengan pesan dari backend
            displayMessage =
              "Role yang Anda pilih tidak sesuai dengan akun ini.";
            break;
          default:
            displayMessage =
              backendError || "Login gagal: Kredensial tidak valid.";
            break;
        }

        toast.error(displayMessage, {
          duration: 4000,
          position: "top-center",
          style: {
            background: "#ef4444",
            color: "#fff",
            borderRadius: "8px",
          },
        });
        setError(displayMessage);
        // --- END: Perubahan di sini ---
      }
    } catch (err: any) {
      console.error("Login request failed in catch block:", err);
      let displayMessage = "Terjadi kesalahan saat login.";

      if (err.response?.data?.errors) {
        displayMessage = Object.values(
          err.response.data.errors
        ).flat()[0] as string;
      } else if (err.response?.data?.error) {
        displayMessage = err.response.data.error;
      } else if (err.response?.data?.message) {
        displayMessage = err.response.data.message;
      } else if (err.message) {
        displayMessage = err.message;
      }

      toast.error(displayMessage, {
        duration: 4000,
        position: "top-center",
        style: {
          background: "#ef4444",
          color: "#fff",
          borderRadius: "8px",
        },
      });
      setError(displayMessage);
    } finally {
      setLoading(false);
    }
  };

  const roleOptions = [
    { value: "super_admin", label: "Super Admin - Super Administrator" },
    { value: "admin", label: "Admin - Administrator" },
    { value: "owner", label: "Owner - Pemilik Bisnis" },
    { value: "driver", label: "Driver - Supir Pengiriman" },
    { value: "content-admin", label: "Content Admin - Pengelola Konten" },
    { value: "customer", label: "Customer - Pelanggan" },
  ];

  return (
    <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-orange-50 py-12 px-4 sm:px-6 lg:px-8">
      <Toaster />
      <Card className="w-full max-w-md shadow-xl border-0 bg-white/80 backdrop-blur-sm">
        <CardHeader className="space-y-1">
          <div className="flex justify-center mb-4">
            <div className="w-16 h-16 bg-gradient-to-r from-blue-600 to-orange-500 rounded-full flex items-center justify-center">
              <span className="text-2xl font-bold text-white">AZ</span>
            </div>
          </div>
          <CardTitle className="text-2xl font-bold text-center text-gray-800">
            Login Azhar Material
          </CardTitle>
          <CardDescription className="text-center text-gray-600">
            Masuk ke sistem berdasarkan peran Anda
          </CardDescription>
        </CardHeader>
        <CardContent>
          <form onSubmit={handleSubmit} className="space-y-4">
            {error && (
              <Alert variant="destructive">
                <AlertDescription>{error}</AlertDescription>
              </Alert>
            )}

            <div className="space-y-2">
              <Label htmlFor="role">Pilih Role</Label>
              <Select onValueChange={handleRoleChange} value={formData.role}>
                <SelectTrigger>
                  <SelectValue placeholder="Pilih role Anda..." />
                </SelectTrigger>
                <SelectContent className="bg-white">
                  {roleOptions.map((option) => (
                    <SelectItem key={option.value} value={option.value}>
                      {option.label}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>

            <div className="space-y-2">
              <Label htmlFor="username">Username</Label>
              <Input
                id="username"
                name="username"
                type="text"
                required
                value={formData.username}
                onChange={handleChange}
                placeholder="Masukkan username Anda"
                className="border-gray-300 focus:border-blue-500"
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="password">Password</Label>
              <Input
                id="password"
                name="password"
                type="password"
                required
                value={formData.password}
                onChange={handleChange}
                placeholder="Masukkan password Anda"
                className="border-gray-300 focus:border-blue-500"
              />
            </div>

            <Button
              type="submit"
              className="w-full bg-gradient-to-r from-blue-600 to-orange-500 hover:from-blue-700 hover:to-orange-600 text-white font-semibold py-2 px-4 rounded-md transition-all duration-200"
              disabled={loading}
            >
              {loading ? "Memproses..." : "Login"}
            </Button>

            <div className="mt-4 p-3 bg-gray-50 rounded-md">
              <p className="text-xs text-gray-600 mb-2">Demo Accounts:</p>
              <div className="text-xs space-y-1 text-gray-500">
                <div>Superadmin: superadmin / password</div>
                <div>Admin: admin / password</div>
                <div>Owner: guntur / gugun1710</div>
                <div>Driver: driver / password</div>
                <div>Content Admin: contentadmin / password</div>
                <div>Customer: guntur / password</div>
              </div>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  );
};

export default Login;
