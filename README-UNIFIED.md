# Azhar Material - Unified Application

## 🎯 Overview

Aplikasi Laravel unified yang menggabungkan **React SPA** (Company Profile) dan **Livewire Admin Panel** (Inventory Management) dalam **satu port (8000)** untuk mengatasi masalah session/cookie persistence.

## 🏗️ Arsitektur Aplikasi

```
Azhar Material Unified/
├── 📁 app/
│   ├── 📁 Http/
│   │   ├── 📁 Controllers/Api/          # API untuk React SPA
│   │   ├── 📁 Livewire/                 # Livewire Components
│   │   │   ├── 📁 Transaction/          # ❌ SUPERADMIN ONLY
│   │   │   ├── 📁 Order/                # ❌ SUPERADMIN ONLY  
│   │   │   ├── 📁 Goods/                # ❌ SUPERADMIN ONLY
│   │   │   ├── 📁 Master/               # ❌ SUPERADMIN ONLY
│   │   │   ├── 📁 Report/               # ❌ SUPERADMIN ONLY
│   │   │   ├── 📁 HeroSection/          # ✅ Content Admin + Superadmin
│   │   │   ├── 📁 Brand/                # ✅ Content Admin + Superadmin
│   │   │   ├── 📁 Team/                 # ✅ Content Admin + Superadmin
│   │   │   ├── 📁 Service/              # ✅ Content Admin + Superadmin
│   │   │   └── 📁 About/                # ✅ Content Admin + Superadmin
│   │   └── 📁 Middleware/
│   └── 📁 Models/
├── 📁 resources/
│   ├── 📁 js/react/                     # React SPA Source Code
│   │   ├── 📁 components/               # React Components
│   │   ├── 📁 pages/                    # React Pages
│   │   ├── 📁 services/                 # API Services
│   │   ├── 📁 hooks/                    # Custom Hooks
│   │   ├── App.tsx                      # React App Root
│   │   └── main.tsx                     # React Entry Point
│   ├── 📁 views/
│   │   ├── react.blade.php              # React SPA Template
│   │   └── 📁 livewire/                 # Livewire Views
│   └── 📁 css/
├── 📁 routes/
│   ├── web.php                          # Web Routes (React + Livewire)
│   └── api.php                          # API Routes (untuk React)
├── 📁 public/build/                     # Compiled React Assets
├── package.json                         # Node.js Dependencies
├── composer.json                        # PHP Dependencies
├── vite.config.js                       # Vite Configuration
└── start-unified.sh                     # Startup Script
```

## 🚀 Quick Start

### 1. Setup Environment

```bash
# Clone repository
git clone <repository-url>
cd azhar-material-unified

# Run unified setup
./start-unified.sh
```

### 2. Manual Setup (Alternative)

```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database in .env file
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=azhar_material
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations and seeders
php artisan migrate --seed

# Build React assets
npm run build

# Start server
php artisan serve --host=0.0.0.0 --port=8000
```

## 🔐 User Roles & Access Control

### Superadmin
- **Akses**: Semua fitur kecuali yang dibatasi untuk content-admin
- **Fitur**:
  - ✅ Inventory Management (Transaction, Order, Goods)
  - ✅ Master Data (Admin, Employee, Supplier, Customer)
  - ✅ Reports & Analytics
  - ✅ POS System
  - ✅ Content Management (Hero, Brand, Team, Service, About)

### Content-Admin
- **Akses**: Hanya Content Management
- **Fitur**:
  - ❌ Tidak bisa akses Transaction, Order, Goods
  - ❌ Tidak bisa akses Master Data
  - ❌ Tidak bisa akses Reports
  - ✅ Content Management (Hero, Brand, Team, Service, About)
  - ✅ Analytics Content

## 🌍 Application URLs

| Fitur | URL | Role Required |
|-------|-----|---------------|
| **React SPA (Company Profile)** | `http://localhost:8000` | Public |
| **React Admin Dashboard** | `http://localhost:8000/admin/dashboard` | Superadmin |
| **React Content Admin** | `http://localhost:8000/admin/content` | Content-Admin |
| **Livewire Admin Login** | `http://localhost:8000/admin-login` | Admin |
| **Livewire Dashboard** | `http://localhost:8000/admin` | Superadmin |
| **Livewire Content** | `http://localhost:8000/content/*` | Content-Admin + Superadmin |

## 🔧 Development

### Development Mode
```bash
# Development with hot reload
npm run dev

# In another terminal
php artisan serve --host=0.0.0.0 --port=8000
```

### Production Build
```bash
# Build for production
npm run build
php artisan serve --host=0.0.0.0 --port=8000
```

## 📡 API Endpoints

### Authentication
- `POST /api/auth/login` - Login
- `POST /api/auth/logout` - Logout
- `GET /api/user` - Get user info

### Content Management (Public Read)
- `GET /api/hero-sections` - Hero sections
- `GET /api/brands` - Brands
- `GET /api/products` - Products
- `GET /api/services` - Services
- `GET /api/teams` - Team members
- `GET /api/about` - About content

### Content Management (Protected Write)
- `POST|PUT|DELETE /api/services/*` - Service management
- `POST|PUT|DELETE /api/about/*` - About management
- `PUT /api/brands/{id}/content` - Brand content update

## 🔄 Session & Cookie Management

### Keunggulan Unified Architecture:
1. **Single Domain**: Semua fitur berjalan di `localhost:8000`
2. **Shared Session**: Laravel session bekerja untuk React dan Livewire
3. **No CORS Issues**: Tidak ada cross-origin request
4. **Seamless Authentication**: Login sekali untuk semua fitur

### Technical Implementation:
- React dibangun sebagai SPA dan di-serve oleh Laravel
- Livewire tetap menggunakan server-side rendering
- API menggunakan Laravel Sanctum untuk authentication
- CSRF token otomatis ter-handle

## 🛠️ Troubleshooting

### Common Issues:

1. **Node modules tidak terinstall**:
   ```bash
   rm -rf node_modules package-lock.json
   npm install
   ```

2. **Assets tidak ter-compile**:
   ```bash
   npm run build
   ```

3. **Database connection error**:
   - Pastikan database sudah dibuat
   - Periksa konfigurasi `.env`
   - Jalankan `php artisan migrate --seed`

4. **Permission denied pada script**:
   ```bash
   chmod +x start-unified.sh
   ```

## 📚 Learning Resources

### Laravel Livewire:
- [Official Documentation](https://laravel-livewire.com/docs)
- [Livewire Screencasts](https://laravel-livewire.com/screencasts)

### React + Laravel:
- [Laravel Vite Documentation](https://laravel.com/docs/10.x/vite)
- [React Router Documentation](https://reactrouter.com/)

### Laravel Sanctum:
- [API Authentication](https://laravel.com/docs/10.x/sanctum)

## 🔧 Configuration Files

### Key Files:
- `vite.config.js` - React build configuration
- `routes/web.php` - Route definitions
- `routes/api.php` - API route definitions
- `app/Http/Kernel.php` - Middleware registration
- `resources/views/react.blade.php` - React SPA template

## 🚀 Production Deployment

### Steps:
1. **Environment Setup**:
   ```bash
   cp .env.example .env
   # Configure production database and app settings
   ```

2. **Build Assets**:
   ```bash
   composer install --optimize-autoloader --no-dev
   npm ci
   npm run build
   ```

3. **Database**:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

4. **Permissions**:
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```

5. **Web Server**: Configure Nginx/Apache to point to `public/` directory

## 📈 Performance Tips

1. **Cache Optimization**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Asset Optimization**: Vite automatically optimizes React assets in production

3. **Database**: Use appropriate indexes and query optimization

## 🤝 Contributing

1. Fork the repository
2. Create feature branch
3. Follow PSR-12 coding standards for PHP
4. Use ESLint for JavaScript/TypeScript
5. Submit pull request

---

**© 2024 Azhar Material - Unified Laravel + React Application**