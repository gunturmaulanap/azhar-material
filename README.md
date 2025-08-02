# Azhar Material - Project

## 🏗️ Struktur Project (Updated)

```
Azhar Material - Project/
├── 📁 backend/                   # Backend Laravel Livewire (Admin Panel)
│   ├── 📁 app/
│   │   ├── 📁 Http/
│   │   │   ├── 📁 Controllers/Api/  # API Controllers untuk React
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── ContactController.php
│   │   │   │   ├── HeroSectionController.php
│   │   │   │   └── BrandController.php
│   │   │   └── 📁 Livewire/         # Livewire Components (Admin)
│   │   │       ├── 📁 HeroSection/
│   │   │       │   └── Index.php
│   │   │       └── 📁 Brand/
│   │   │           └── Index.php
│   │   └── 📁 Models/
│   │       ├── Goods.php
│   │       ├── Brand.php
│   │       ├── Category.php
│   │       └── HeroSection.php
│   ├── 📁 database/migrations/   # Database Migrations
│   ├── 📁 routes/
│   │   ├── api.php              # API Routes untuk React
│   │   └── web.php              # Web Routes untuk Admin
│   └── 📁 resources/views/      # Blade Views untuk Admin
├── 📁 frontend/                  # Frontend React (Company Profile)
│   ├── 📁 src/
│   │   ├── 📁 pages/        # React Pages (.tsx)
│   │   │   ├── Home.tsx
│   │   │   ├── Products.tsx
│   │   │   ├── Login.tsx
│   │   │   └── ...
│   │   ├── 📁 components/   # React Components (.tsx)
│   │   │   ├── Layout.tsx
│   │   │   └── 📁 ui/       # UI Components
│   │   ├── 📁 services/     # API Services (.js)
│   │   │   └── api.js
│   │   ├── 📁 hooks/        # Custom Hooks (.js)
│   │   │   └── useAuth.js
│   │   └── 📁 config/       # Configuration (.js)
│   │       └── api.js
│   └── 📁 public/           # Static Assets
├── 🚀 start-dev.sh              # Development script (macOS/Linux)
├── 🚀 start-dev.bat             # Development script (Windows)
├── 📋 run-seeder.sh             # Database seeder script
├── 📋 run-seeder.bat            # Database seeder script (Windows)
├── 📝 SETUP.md                  # Detailed setup guide
└── 📖 README.md                 # This file
```

## 🎯 Teknologi yang Digunakan

### 📁 `backend/` - Backend Laravel Livewire
- **Framework**: Laravel 10 + Livewire
- **Database**: MySQL
- **PHP Version**: 8.3.9+
- **Port**: 8000 (http://localhost:8000)
- **Fitur**: 
  - ✅ POS (Point of Sale)
  - ✅ Inventory Management
  - ✅ Delivery Management
  - ✅ User Management
  - ✅ Reports
  - ✅ API Endpoints untuk React
  - ✅ Livewire Admin Panel
  - ✅ Hero Section CRUD
  - ✅ Brand Management CRUD

### 📁 `frontend/` - Frontend React Company Profile
- **Framework**: React + TypeScript
- **UI Library**: Tailwind CSS + Shadcn/ui
- **Port**: 3000 (http://localhost:3000)
- **Fitur**:
  - ✅ Company Profile Website
  - ✅ Product Catalog (terintegrasi dengan Laravel)
  - ✅ Authentication (terintegrasi dengan Laravel)
  - ✅ Contact Form
  - ✅ Responsive Design
  - ✅ Dynamic Hero Section
  - ✅ Dynamic Brand Display

## 🚀 Quick Start

### Option 1: Automated Setup (Recommended)

**macOS/Linux:**
```bash
./start-dev.sh
```

**Windows:**
```bash
start-dev.bat
```

### Option 2: Manual Setup

#### Backend (Laravel Livewire)
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
# Configure database in .env file
php artisan migrate
php artisan db:seed
php artisan serve
```

#### Frontend (React)
```bash
cd frontend
yarn install
cp env.example .env
yarn start
```

## 🔗 URLs

- **Company Profile (React)**: http://localhost:3000
- **Backend API (Laravel)**: http://localhost:8000/api
- **Admin Panel (Laravel)**: http://localhost:8000/admin
- **Admin Login**: http://localhost:8000/login

## 📋 Prerequisites

- PHP 8.3.9+
- Composer
- Node.js 16+
- MySQL 8.0+
- Yarn (recommended)
- Laravel Sanctum (sudah terinstall)

## 🗃️ Database Configuration

Konfigurasi database pada `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=8889
DB_DATABASE=inventory-azhar
DB_USERNAME=root
DB_PASSWORD=root
```

## 🔧 API Integration

Frontend React mengambil data dari backend Laravel melalui API endpoints:

### Authentication
- `POST /api/auth/login` - Login user
- `POST /api/auth/register` - Register user
- `POST /api/auth/logout` - Logout user (protected)
- `GET /api/user` - Get user info (protected)

### Products
- `GET /api/products` - Get all products
- `GET /api/products/{id}` - Get product by ID
- `GET /api/products/featured` - Get featured products
- `GET /api/categories` - Get all categories
- `GET /api/brands` - Get all brands

### Hero Section
- `GET /api/hero-sections` - Get all hero sections
- `GET /api/hero-sections/{id}` - Get hero section by ID
- `GET /api/hero-sections/active` - Get active hero section

### Brand
- `GET /api/brands/active` - Get active brands
- `GET /api/brands/{id}` - Get brand by ID

### Contact
- `POST /api/contact` - Send contact message

## 🛠️ Development

### Backend Development (Laravel Livewire)
- Laravel Livewire untuk admin panel
- API endpoints untuk frontend React
- MySQL database
- Laravel Sanctum untuk authentication
- CRUD untuk Hero Section dan Brand

### Frontend Development (React)
- React dengan TypeScript
- Tailwind CSS untuk styling
- Shadcn/ui untuk components
- React Router untuk navigation
- Axios untuk API calls
- Dynamic content dari Laravel API

## 🎨 Admin Panel Features

### Hero Section Management
- Create, Read, Update, Delete hero sections
- Upload background images
- Set active/inactive status
- Dynamic content for company profile

### Brand Management
- Create, Read, Update, Delete brands
- Upload brand logos
- Set active/inactive status
- Website URL management

## 🐛 Troubleshooting

### CORS Issues
Jika ada masalah CORS, pastikan konfigurasi CORS di Laravel sudah benar:
- File: `backend/config/cors.php`
- Pastikan `allowed_origins` sudah diset ke `['*']` atau `['http://localhost:3000']`

### Database Connection
- Pastikan MySQL server berjalan
- Periksa konfigurasi database di `.env`
- Jalankan `php artisan migrate:fresh --seed` jika perlu reset database

### API Connection
- Pastikan backend Laravel berjalan di port 8000
- Periksa URL API di frontend: `frontend/src/config/api.js`
- Pastikan tidak ada firewall yang memblokir koneksi

## 📚 Documentation

- [SETUP.md](./SETUP.md) - Detailed setup guide
- [Laravel Documentation](https://laravel.com/docs)
- [React Documentation](https://react.dev)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Livewire Documentation](https://laravel-livewire.com/docs)

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## ⚡ Performance Optimizations

- Menggunakan Yarn untuk dependency management (lebih cepat dari npm)
- CRACO configuration untuk optimisasi webpack
- Hot reload dioptimisasi untuk mengurangi CPU usage
- Database indexing untuk query yang lebih cepat
- PHP 8.3.9 compatibility untuk performa terbaru

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 📞 Support

- Email: azharmaterial@gmail.com
- WhatsApp: 081392854911 