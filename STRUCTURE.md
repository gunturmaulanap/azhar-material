# Struktur Folder - Azhar Material Project

## 📁 Struktur Utama

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
├── 🔄 run-migrations.sh         # Migration script
├── 🔄 run-migrations.bat        # Migration script (Windows)
├── 📝 SETUP.md                  # Detailed setup guide
├── 📖 README.md                 # Main documentation
└── 📋 STRUCTURE.md              # This file
```

## 🎯 Penjelasan Struktur

### 📁 `backend/` - Backend Laravel Livewire

**Tujuan**: Admin Panel dan API untuk React

#### 📁 `app/Http/Controllers/Api/`

- **AuthController.php** - Authentication API
- **ProductController.php** - Products/Goods API
- **ContactController.php** - Contact form API
- **HeroSectionController.php** - Hero section API
- **BrandController.php** - Brand API

#### 📁 `app/Http/Livewire/`

- **HeroSection/Index.php** - CRUD Hero Section (Admin)
- **Brand/Index.php** - CRUD Brand (Admin)
- **Goods/** - Inventory management
- **Transaction/** - POS system
- **Master/** - Data management

#### 📁 `app/Models/`

- **Goods.php** - Product model
- **Brand.php** - Brand model
- **Category.php** - Category model
- **HeroSection.php** - Hero section model

#### 📁 `database/migrations/`

- **2024_12_19_000000_add_description_to_goods_table.php**
- **2024_12_19_000001_add_image_to_goods_table.php**
- **2024_12_19_000003_create_hero_sections_table.php**
- **2024_12_19_000004_add_fields_to_brands_table.php**

### 📁 `abu/` - Frontend React

**Tujuan**: Company Profile Website

#### 📁 `frontend/src/pages/`

- **Home.tsx** - Landing page dengan hero section dinamis
- **Products.tsx** - Product catalog
- **Login.tsx** - Authentication page
- **Brands.tsx** - Brand showcase
- **Contact.tsx** - Contact form
- **Services.tsx** - Services page
- **Team.tsx** - Team page

#### 📁 `frontend/src/components/`

- **Layout.tsx** - Main layout dengan navigation
- **ui/** - Shadcn/ui components

#### 📁 `frontend/src/services/`

- **api.js** - API service functions

#### 📁 `frontend/src/hooks/`

- **useAuth.js** - Authentication hook

#### 📁 `frontend/src/config/`

- **api.js** - API configuration

## 🔗 URL Structure

### Frontend (React) - Company Profile

- **Home**: http://localhost:3000
- **Products**: http://localhost:3000/products
- **Login**: http://localhost:3000/login

### Backend (Laravel) - Admin Panel

- **Admin Dashboard**: http://localhost:8000/admin
- **Hero Section Management**: http://localhost:8000/admin/hero-sections
- **Brand Management**: http://localhost:8000/admin/brands
- **Login**: http://localhost:8000/login

### API Endpoints

- **Base URL**: http://localhost:8000/api
- **Products**: `/products`
- **Hero Sections**: `/hero-sections`
- **Brands**: `/brands`
- **Auth**: `/auth/*`

## 🚀 Development Scripts

### Automated Setup

```bash
# macOS/Linux
./start-dev.sh

# Windows
start-dev.bat
```

### Database Setup

```bash
# Run migrations
./run-migrations.sh

# Run seeders
./run-seeder.sh
```

## 📋 File Extensions

### Backend (Laravel)

- **.php** - PHP files (Controllers, Models, Livewire)
- **.blade.php** - Blade template files
- **.json** - Configuration files

### Frontend (React)

- **.tsx** - TypeScript React components
- **.js** - JavaScript files (services, hooks, config)
- **.css** - Stylesheet files
- **.json** - Configuration files

## 🎨 Technology Stack

### Backend

- **Laravel 10** - PHP framework
- **Livewire** - Dynamic interfaces
- **MySQL** - Database
- **Laravel Sanctum** - API authentication

### Frontend

- **React 18** - JavaScript library
- **TypeScript** - Type safety
- **Tailwind CSS** - Utility-first CSS
- **Shadcn/ui** - UI components
- **Axios** - HTTP client

## 🔧 Key Features

### Admin Panel (Laravel Livewire)

- ✅ Hero Section CRUD
- ✅ Brand Management
- ✅ Product Inventory
- ✅ POS System
- ✅ User Management
- ✅ Reports

### Company Profile (React)

- ✅ Dynamic Hero Section
- ✅ Product Catalog
- ✅ Brand Showcase
- ✅ Authentication
- ✅ Contact Form
- ✅ Responsive Design

## 📝 Notes

1. **Domain Redirect**: Root domain (http://localhost:8000) redirects to React app
2. **Admin Access**: Admin panel accessible via `/admin` prefix
3. **API Integration**: React fetches data from Laravel API
4. **File Organization**: Clear separation between frontend (.tsx) and backend (.php)
5. **Migration Only**: Image field added via migration, no seeder for performance
