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
import toast, { Toaster } from "react-hot-toast";

const Login = () => {
  const [formData, setFormData] = useState({
    username: "",
    password: "",
    role: "",
  });
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);
  const { login } = useAuth();
  const navigate = useNavigate();

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value,
    });
  };

  const handleRoleChange = (value: string) => {
    setFormData({
      ...formData,
      role: value,
    });
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError("");
    setLoading(true);

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
      const result = await login(formData);
      console.log("Login result:", result); // Debug log

      if (result.success) {
        // Success toast
        toast.success("Login berhasil! Mengarahkan ke dashboard...", {
          duration: 3000,
          position: "top-center",
          style: {
            background: "#10b981",
            color: "#fff",
            borderRadius: "8px",
          },
        });

        // Gunakan redirectUrl dari response API jika tersedia
        if (result.redirectUrl) {
          console.log("Redirecting to:", result.redirectUrl); // Debug log
          // Tambahkan delay kecil untuk memastikan state terupdate
          setTimeout(() => {
            window.location.href = result.redirectUrl;
          }, 1500);
        } else {
          // Fallback ke role-based routing
          const userRole = result.data?.data?.user?.role;
          console.log("User role:", userRole); // Debug log

          let redirectUrl = "/";
          switch (userRole) {
            case "customer":
              redirectUrl = "http://localhost:8000/customer/dashboard";
              break;
            case "admin":
            case "super_admin":
              redirectUrl =
                "http://localhost:8000/sso-login/" +
                result.data?.data?.user?.id;
              break;
            case "content-admin":
              redirectUrl = "http://localhost:3000/admin/content";
              break;
            default:
              redirectUrl = "/";
          }

          console.log("Fallback redirect to:", redirectUrl); // Debug log
          setTimeout(() => {
            window.location.href = redirectUrl;
          }, 1500);
        }
      } else {
        // Error toast
        toast.error(result.error || "Login gagal", {
          duration: 4000,
          position: "top-center",
          style: {
            background: "#ef4444",
            color: "#fff",
            borderRadius: "8px",
          },
        });
        setError(result.error);
      }
    } catch (err: any) {
      // Handle specific error types
      if (err.response?.data?.errors) {
        const errors = err.response.data.errors;
        if (errors.role) {
          toast.error(errors.role[0], {
            duration: 4000,
            position: "top-center",
            style: {
              background: "#ef4444",
              color: "#fff",
              borderRadius: "8px",
            },
          });
        } else if (errors.username) {
          toast.error(errors.username[0], {
            duration: 4000,
            position: "top-center",
            style: {
              background: "#ef4444",
              color: "#fff",
              borderRadius: "8px",
            },
          });
        } else if (errors.password) {
          toast.error(errors.password[0], {
            duration: 4000,
            position: "top-center",
            style: {
              background: "#ef4444",
              color: "#fff",
              borderRadius: "8px",
            },
          });
        } else {
          toast.error("Terjadi kesalahan saat login", {
            duration: 4000,
            position: "top-center",
            style: {
              background: "#ef4444",
              color: "#fff",
              borderRadius: "8px",
            },
          });
        }
      } else {
        toast.error("Terjadi kesalahan saat login", {
          duration: 4000,
          position: "top-center",
          style: {
            background: "#ef4444",
            color: "#fff",
            borderRadius: "8px",
          },
        });
      }
      setError("Terjadi kesalahan saat login");
    } finally {
      setLoading(false);
    }
  };

  const roleOptions = [
    { value: "customer", label: "Customer - Pelanggan" },
    { value: "admin", label: "Admin - Administrator" },
    { value: "super_admin", label: "Super Admin - Super Administrator" },
    { value: "content-admin", label: "Content Admin - Pengelola Konten" },
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
                <SelectContent>
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
                <div>Customer: customer / password</div>
                <div>Admin: admin / password</div>
                <div>Super Admin: miura / password</div>
                <div>Content Admin: contentadmin / password</div>
              </div>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  );
};

export default Login;
