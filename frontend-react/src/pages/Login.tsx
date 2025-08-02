import React, { useState } from 'react';
import { useAuth } from '../hooks/useAuth';
import { Button } from '../components/ui/button';
import { Input } from '../components/ui/input';
import { Label } from '../components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '../components/ui/card';
import { Alert, AlertDescription } from '../components/ui/alert';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../components/ui/select';
import { useNavigate } from 'react-router-dom';

const Login = () => {
  const [formData, setFormData] = useState({
    username: '',
    password: '',
    role: '',
  });
  const [error, setError] = useState('');
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
    setError('');
    setLoading(true);

    if (!formData.role) {
      setError('Silakan pilih role terlebih dahulu');
      setLoading(false);
      return;
    }

    try {
      const result = await login(formData);
      if (result.success) {
        const userRole = result.data.data.user.role;
        // Role-based routing
        switch (userRole) {
          case 'customer':
            navigate('/');
            break;
          case 'admin':
          case 'superadmin':
            navigate('/admin/dashboard');
            break;
          case 'content-admin':
            navigate('/admin/content');
            break;
          default:
            navigate('/');
        }
      } else {
        setError(result.error);
      }
    } catch (err) {
      setError('Terjadi kesalahan saat login');
    } finally {
      setLoading(false);
    }
  };

  const roleOptions = [
    { value: 'customer', label: 'Customer - Pelanggan' },
    { value: 'admin', label: 'Admin - Administrator' },
    { value: 'superadmin', label: 'Super Admin - Super Administrator' },
    { value: 'content-admin', label: 'Content Admin - Pengelola Konten' },
  ];

  return (
    <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-orange-50 py-12 px-4 sm:px-6 lg:px-8">
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
              {loading ? 'Memproses...' : 'Login'}
            </Button>

            <div className="mt-4 p-3 bg-gray-50 rounded-md">
              <p className="text-xs text-gray-600 mb-2">Demo Accounts:</p>
              <div className="text-xs space-y-1 text-gray-500">
                <div>Customer: customer / password</div>
                <div>Admin: admin / password</div>
                <div>Super Admin: superadmin / password</div>
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