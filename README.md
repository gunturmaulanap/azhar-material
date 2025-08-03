# Azhar Material - Unified Application

## 🎯 Overview

**Aplikasi Laravel Unified** yang menggabungkan **React SPA** (Company Profile) dan **Livewire Admin Panel** (Inventory Management) dalam **satu port (8000)** untuk mengatasi masalah session/cookie persistence dan CORS.

## ✨ Key Features

### 🌐 Frontend (React SPA)
- **Company Profile** dengan modern UI/UX
- **Product Catalog** dengan filter dan search
- **Contact Form** terintegrasi
- **Responsive Design** untuk semua device

### 🔧 Admin Panel (Livewire)
- **Inventory Management** (Goods, Transaction, Order)
- **Master Data Management** (Supplier, Customer, Employee)
- **POS System** untuk penjualan
- **Reports & Analytics**

### 👥 Role-Based Access Control
- **Superadmin**: Akses ke semua fitur
- **Content-Admin**: Hanya content management

## 🚀 Quick Start

```bash
# Clone repository
git clone <repository-url>
cd azhar-material-unified

# Run unified application
./start-unified.sh
```

## 🌍 Application URLs

| Fitur | URL | Role |
|-------|-----|------|
| Company Profile | `http://localhost:8000` | Public |
| Admin Dashboard | `http://localhost:8000/admin/dashboard` | Superadmin |
| Content Admin | `http://localhost:8000/admin/content` | Content-Admin |
| Livewire Admin | `http://localhost:8000/admin-login` | All Admin |

## 🔧 Manual Setup

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL/PostgreSQL

### Installation
```bash
# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database configuration in .env
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

## 🏗️ Architecture

```
Azhar Material Unified/
├── 📁 app/Http/
│   ├── 📁 Controllers/Api/    # API untuk React
│   ├── 📁 Livewire/          # Livewire Components
│   └── 📁 Middleware/        # Custom Middleware
├── 📁 resources/
│   ├── 📁 js/react/          # React SPA Source
│   └── 📁 views/             # Blade Templates
├── 📁 routes/
│   ├── web.php               # Web & React Routes
│   └── api.php               # API Routes
└── 📁 public/build/          # Compiled Assets
```

## 🔐 Access Control

### Superadmin Features:
- ✅ Inventory Management (Transaction, Order, Goods)
- ✅ Master Data (Admin, Employee, Supplier, Customer)  
- ✅ Reports & Analytics
- ✅ POS System
- ✅ Content Management

### Content-Admin Features:
- ❌ No access to Transaction, Order, Goods
- ❌ No access to Master Data
- ✅ Content Management only (Hero, Brand, Team, Service, About)

## 🛠️ Development

### Development Mode
```bash
# Terminal 1: Laravel server
php artisan serve --host=0.0.0.0 --port=8000

# Terminal 2: Vite dev server (optional for hot reload)
npm run dev
```

### Production Build
```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📡 API Documentation

### Authentication
- `POST /api/auth/login` - User login
- `POST /api/auth/logout` - User logout
- `GET /api/user` - Get authenticated user

### Public APIs
- `GET /api/products` - Product listings
- `GET /api/hero-sections` - Hero sections
- `GET /api/brands` - Brand listings
- `GET /api/services` - Services
- `GET /api/teams` - Team members

### Protected APIs (Sanctum)
- `POST|PUT|DELETE /api/services/*` - Service management
- `POST|PUT|DELETE /api/about/*` - About management

## 🔄 Migration from Separate Ports

Aplikasi ini adalah hasil migrasi dari arsitektur terpisah:
- **Before**: React (port 3000) + Laravel (port 8000)
- **After**: Unified Laravel (port 8000) dengan React terintegrasi

**Benefits**:
- ✅ No CORS issues
- ✅ Shared Laravel sessions
- ✅ Seamless authentication
- ✅ Single deployment

Lihat [MIGRATION-GUIDE.md](MIGRATION-GUIDE.md) untuk detail lengkap.

## 📚 Documentation

- [📖 Unified Documentation](README-UNIFIED.md) - Complete setup guide
- [🔄 Migration Guide](MIGRATION-GUIDE.md) - Migration from separate ports
- [📋 Original Structure](STRUCTURE.md) - Legacy structure reference

## 🛠️ Troubleshooting

### Common Issues:

1. **React tidak loading**: `npm run build`
2. **Database error**: Check `.env` dan `php artisan migrate --seed`
3. **Permission denied**: `chmod +x start-unified.sh`
4. **API tidak accessible**: `php artisan route:list | grep api`

## 📈 Performance

### Optimizations:
- **Laravel caching** untuk route, config, dan view
- **Vite** untuk optimal asset bundling
- **Code splitting** untuk React components
- **Database indexing** untuk query performance

## 🚀 Deployment

### Production Steps:
```bash
composer install --optimize-autoloader --no-dev
npm ci && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Web Server:
Point web server ke `public/` directory.

## 🤝 Contributing

1. Fork repository
2. Create feature branch: `git checkout -b feature/new-feature`
3. Commit changes: `git commit -am 'Add new feature'`
4. Push branch: `git push origin feature/new-feature`
5. Submit Pull Request

### Coding Standards:
- **PHP**: PSR-12
- **JavaScript**: ESLint + Prettier
- **Commit**: Conventional Commits

## 📄 License

MIT License - see [LICENSE](LICENSE) for details.

## 🆘 Support

- 📧 Email: support@azharmaterial.com
- 📱 WhatsApp: +62-xxx-xxxx-xxxx
- 🐛 Issues: [GitHub Issues](https://github.com/username/azhar-material/issues)

---

**🎉 Unified Laravel + React Application**  
*Single port, seamless authentication, no CORS issues*

**Tech Stack**: Laravel 10 + React 18 + Livewire 2 + Vite + Tailwind CSS
