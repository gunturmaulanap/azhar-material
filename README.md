# Azhar Material - Comprehensive Business Management System

## 🎯 Overview

**Azhar Material** adalah sistem manajemen bisnis komprehensif yang menggabungkan **React SPA** (Company Profile) dan **Livewire Admin Panel** (Inventory & Business Management) dalam satu aplikasi terintegrasi pada port 8000. Sistem ini dirancang untuk mengatasi masalah session/cookie persistence dan CORS dengan arsitektur unified yang powerful.

## ✨ Key Features

### 🌐 Frontend (React SPA) - Company Profile
- **Modern Company Profile** dengan responsive design
- **Product Catalog** dengan advanced filtering dan search
- **Brand Showcase** untuk partner dan supplier
- **Service Portfolio** dengan detail lengkap
- **Team Directory** dengan informasi lengkap
- **Contact Integration** dengan form terintegrasi
- **SEO Optimized** untuk search engine visibility

### 🔧 Admin Panel (Livewire) - Business Management
- **Complete Inventory Management** (Goods, Categories, Brands, Stock Control)
- **Transaction Processing** (POS, Sales, Credit Sales, Invoicing)
- **Order Management** (Order tracking, fulfillment, delivery)
- **Master Data Management** (Suppliers, Customers, Employees, Admins)
- **Attendance System** (Employee attendance tracking)
- **Delivery Management** (Shipping, tracking, status updates)
- **Debt Management** (Credit tracking, payment management)
- **Comprehensive Reports** (Sales, Inventory, Financial analytics)
- **Multi-level User Management** dengan role-based permissions

### 👥 Advanced Role-Based Access Control

#### 🔴 **Super Admin** (Username: `superadmin`, Password: `password`)
**Full System Access** - Complete control over all features:
- ✅ **Master Data**: Admin, Employee, Supplier, Customer management
- ✅ **Inventory**: Goods, Categories, Brands, Stock management
- ✅ **Transactions**: All sales, POS, credit sales, invoicing
- ✅ **Orders**: Order management, fulfillment, tracking
- ✅ **Delivery**: Shipping management, delivery tracking
- ✅ **Attendance**: Employee attendance tracking
- ✅ **Debt Management**: Credit tracking, payment management
- ✅ **Reports**: All analytics and financial reports
- ❌ **Hero Sections**: Content-admin exclusive access

#### 🟡 **Admin** (Username: `admin`, Password: `password`)
**Operational Management** - Day-to-day business operations:
- ✅ **Transactions**: Create and manage sales transactions
- ✅ **Orders**: Process and track customer orders
- ✅ **Goods**: View and manage product inventory
- ✅ **Delivery**: Handle shipping and delivery processes
- ❌ **Master Data**: No access to user/supplier management
- ❌ **Reports**: Limited reporting access
- ❌ **System Settings**: No administrative privileges

#### 🟢 **Content Admin** (Username: `contentadmin`, Password: `password`)
**Website Content Management** - Frontend content control:
- ✅ **Hero Sections**: Homepage banner management
- ✅ **Brand Management**: Company and partner brands
- ✅ **Team Management**: Team member profiles
- ✅ **Service Management**: Service portfolio
- ✅ **About Management**: Company information
- ✅ **Analytics**: Website visitor analytics
- ❌ **Business Operations**: No access to transactions/inventory
- ❌ **Master Data**: No access to business data

#### 🔵 **Owner** (Username: `guntur`, Password: `gugun1710`)
**Business Intelligence** - Strategic oversight and reporting:
- ✅ **Dashboard**: Business overview and KPIs
- ✅ **Reports**: Sales, inventory, and financial reports
- ✅ **Stock Management**: Inventory level adjustments
- ✅ **Analytics**: Business performance metrics
- ❌ **Daily Operations**: No transaction processing
- ❌ **Master Data**: No user management access
- ❌ **Content**: No website content access

## 🚀 Quick Start

### Method 1: Automated Setup (Recommended)
```bash
# Clone repository
git clone <repository-url>
cd azhar-material

# Run automated setup
chmod +x start-unified.sh
./start-unified.sh
```

### Method 2: Manual Setup
```bash
# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup (configure .env first)
php artisan migrate --seed

# Build assets
npm run build

# Start server
php artisan serve --host=0.0.0.0 --port=8000
```

## 🌍 Application Access Points

### 🌐 Public Frontend (React SPA)
| Feature | URL | Access |
|---------|-----|--------|
| **Company Profile** | `http://localhost:8000` | Public |
| **Products** | `http://localhost:8000/products` | Public |
| **Brands** | `http://localhost:8000/brands` | Public |
| **Services** | `http://localhost:8000/services` | Public |
| **Team** | `http://localhost:8000/team` | Public |
| **Contact** | `http://localhost:8000/contact` | Public |

### 🔐 Admin Panel Access
| Role | Login URL | Dashboard URL |
|------|-----------|---------------|
| **All Admin Roles** | `http://localhost:8000/admin-login` | `http://localhost:8000/admin` |

### 🎯 Role-Specific Feature Access

#### Super Admin Routes
```
🏠 Dashboard: /admin
👥 Master Data: /data-admin, /data-employee, /data-supplier, /data-customer
📋 Attendance: /absensi, /absensi-baru, /detail-absensi/{id}
📦 Inventory: /data-barang, /data-kategori, /data-brand
💰 Transactions: /transaksi, /riwayat-transaksi, /point-of-sale
📋 Orders: /data-order, /order, /detail-order/{id}
🚚 Delivery: /pengiriman-barang, /pengiriman-barang/{id}
💳 Debt: /data-hutang, /kredit-penjualan
📊 Reports: /laporan-penjualan, /laporan-barang
```

#### Admin Routes (Operational)
```
🏠 Dashboard: /admin
📦 Goods: /admin/data-barang, /admin/tambah-data-barang
💰 Transactions: /admin/transaksi, /admin/riwayat-transaksi
📋 Orders: /admin/data-order, /admin/order
🚚 Delivery: /admin/pengiriman-barang
```

#### Content Admin Routes
```
🏠 Dashboard: /admin
🎨 Hero Sections: /content/hero-sections
🏢 Brands: /content/brands
👥 Teams: /content/teams
🛠️ Services: /content/services
ℹ️ About: /content/about
📈 Analytics: /content/analytics
```

#### Owner Routes
```
🏠 Dashboard: /admin
📊 Reports: /owner/laporan-penjualan, /owner/laporan-barang
📦 Stock Management: /owner/data-barang, /owner/kelola-stok-barang
```

## 🔧 System Requirements

### Prerequisites
- **PHP**: 8.2+ with extensions (mbstring, xml, bcmath, pdo, tokenizer, json, openssl)
- **Composer**: Latest version
- **Node.js**: 18+ dengan npm
- **Database**: MySQL 8.0+ atau PostgreSQL 13+
- **Web Server**: Apache 2.4+ atau Nginx 1.18+
- **Storage**: Minimum 2GB free space

### Environment Configuration

1. **Database Setup** (.env configuration)
```bash
# MySQL Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=azhar_material
DB_USERNAME=root
DB_PASSWORD=your_password

# PostgreSQL Alternative
# DB_CONNECTION=pgsql
# DB_HOST=127.0.0.1
# DB_PORT=5432
# DB_DATABASE=azhar_material
# DB_USERNAME=postgres
# DB_PASSWORD=your_password
```

2. **Storage Configuration**
```bash
# File Storage
FILESYSTEM_DISK=local

# Mail Configuration (for notifications)
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
```

3. **Application Configuration**
```bash
APP_NAME="Azhar Material"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
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
