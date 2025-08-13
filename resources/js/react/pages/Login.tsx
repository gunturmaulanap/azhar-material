import React, { useState, useEffect } from "react";
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
import toast from "react-hot-toast";

const Login: React.FC = () => {
  const [formData, setFormData] = useState({
    username: "",
    password: "",
    role: "",
  });
  const [error, setError] = useState("");
  const [submitting, setSubmitting] = useState(false);
  const [mounted, setMounted] = useState(false); // cegah glitch portal Radix

  const { login, isAuthenticated, user, refresh } = useAuth();
  const navigate = useNavigate();

  useEffect(() => {
    setMounted(true);
  }, []);

  // Kalau sudah login, jangan tampilkan form lagi
  useEffect(() => {
    if (isAuthenticated && user) {
      navigate("/", { replace: true });
    }
  }, [isAuthenticated, user, navigate]);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    setFormData((s) => ({ ...s, [name]: value }));
  };

  const handleRoleChange = (value: string) => {
    setFormData((s) => ({ ...s, role: value }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (submitting) return;
    setError("");

    const username = formData.username.trim();
    const password = formData.password;
    const role = formData.role;

    if (!role) {
      toast.error("Silakan pilih role terlebih dahulu", {
        duration: 3000,
        position: "top-center",
      });
      return;
    }
    if (!username || !password) {
      setError("Username dan password wajib diisi.");
      return;
    }

    setSubmitting(true);
    try {
      const loginType = role === "customer" ? "customer" : "user";

      const result = await login({
        username,
        password,
        login_type: loginType,
        role,
      });

      if (result.success) {
        toast.success("Login berhasil! Selamat datang 👋", {
          duration: 2000,
          position: "top-center",
        });

        // sinkronkan state auth & arahkan ke beranda
        await refresh?.();
        navigate("/", { replace: true });

        // reset form
        setFormData({ username: "", password: "", role: "" });
      } else {
        const backendError = result.error;
        const displayMessage =
          backendError === "Username tidak ditemukan."
            ? "Username tidak ditemukan. Mohon periksa kembali."
            : backendError === "Password salah."
            ? "Password salah. Mohon periksa kembali."
            : backendError === "Role yang digunakan salah."
            ? "Role yang Anda pilih tidak sesuai dengan akun ini."
            : backendError || "Login gagal: kredensial tidak valid.";

        toast.error(displayMessage, { duration: 3500, position: "top-center" });
        setError(displayMessage);
      }
    } catch (err: any) {
      let msg = "Terjadi kesalahan saat login.";
      if (err?.response?.data?.errors) {
        msg =
          (Object.values(err.response.data.errors).flat()[0] as string) || msg;
      } else if (err?.response?.data?.error) {
        msg = err.response.data.error;
      } else if (err?.response?.data?.message) {
        msg = err.response.data.message;
      } else if (err?.message) {
        msg = err.message;
      }
      toast.error(msg, { duration: 3500, position: "top-center" });
      setError(msg);
    } finally {
      setSubmitting(false);
    }
  };

  const roleOptions = [
    { value: "customer", label: "Customer - Pelanggan" },
    { value: "super_admin", label: "Super Admin - Super Administrator" },
    { value: "driver", label: "Driver - Supir Pengiriman" },
    { value: "admin", label: "Admin - Administrator" },
    { value: "content-admin", label: "Content Admin - Pengelola Konten" },
    { value: "owner", label: "Owner - Pemilik Bisnis" },
  ];

  return (
    <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-orange-50 py-12 px-4 sm:px-6 lg:px-8">
      {/* Toaster lokal DIHAPUS — pakai global dari <Notifications /> */}
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
              {/* Delay mount untuk mencegah konflik portal Radix pada dev/HMR */}
              {mounted && (
                <Select onValueChange={handleRoleChange} value={formData.role}>
                  <SelectTrigger id="role" aria-label="Pilih Role">
                    <SelectValue placeholder="Pilih role Anda..." />
                  </SelectTrigger>

                  {/* penting: matikan portal di halaman Login */}
                  <SelectContent disablePortal className="bg-white">
                    {roleOptions.map((option) => (
                      <SelectItem key={option.value} value={option.value}>
                        {option.label}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              )}
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
                autoComplete="username"
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
                autoComplete="current-password"
                className="border-gray-300 focus:border-blue-500"
              />
            </div>

            <Button
              type="submit"
              className="w-full bg-gradient-to-r from-blue-600 to-orange-500 hover:from-blue-700 hover:to-orange-600 text-white font-semibold py-2 px-4 rounded-md transition-all duration-200"
              disabled={submitting}
            >
              {submitting ? "Memproses..." : "Login"}
            </Button>

            <div className="mt-4 p-3 bg-gray-50 rounded-md">
              <p className="text-xs text-gray-600 mb-2">Demo Accounts:</p>
              <div className="text-xs space-y-1 text-gray-500">
                <div>Customer: customer / password</div>
                <div>Super Admin: super / password</div>
                <div>Admin: admin / password</div>
                <div>Driver: driver / password</div>
                <div>Content Admin: contentadmin / password</div>
                <div>Owner: owner / password</div>
              </div>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  );
};

export default Login;
