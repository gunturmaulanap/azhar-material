# Azhar Material - Final Setup Guide

## ✅ Reorganisasi Selesai!

Struktur folder telah dioptimisasi untuk kemudahan penggunaan dan deployment:

```
/app/
├── 📁 backend/          # Laravel Livewire (inventory-azhar -> backend)
│   ├── .env             # Database config: inventory-azhar @ port 8889
│   ├── composer.json    # PHP 8.2+ compatibility
│   └── ...
├── 📁 frontend/         # React TypeScript (frontend-react -> frontend)
│   ├── .env             # API config pointing to localhost:8000
│   ├── package.json     # Yarn package manager
│   └── ...
├── 🚀 start-dev.sh      # Automated startup script
├── ⚙️ setup.sh          # Quick setup script
└── 📚 Documentation files
```

## 🎯 Perubahan Utama

### 1. **Struktur Folder Simplified**
- `inventory-azhar/` → `backend/`
- `frontend-react/` → `frontend/` (main)
- `abu/frontend/` → DIHAPUS (duplikat)

### 2. **Database Configuration**
- Host: 127.0.0.1:8889 ✅
- Database: inventory-azhar ✅
- User: root/root ✅

### 3. **PHP Compatibility**
- Updated untuk PHP 8.2+ (dari requirement 8.1)
- Compatible dengan PHP 8.3.9 ketika tersedia

### 4. **Package Management**
- Frontend menggunakan **Yarn** (lebih cepat dari npm)
- Backend menggunakan **Composer** dengan optimized autoloader

### 5. **Environment Files**
- Backend `.env` sudah dikonfigurasi dengan database yang benar
- Frontend `.env` sudah pointing ke Laravel API

## 🚀 Quick Start Commands

### Option 1: Automated Setup (Recommended)
```bash
# Quick setup
./setup.sh

# Start development servers
./start-dev.sh
```

### Option 2: Manual Setup
```bash
# Backend
cd backend
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate  # (requires MySQL running)
php artisan serve

# Frontend (in new terminal)
cd frontend
yarn install
yarn start
```

## 📋 Prerequisites

- ✅ PHP 8.2+ (installed)
- ✅ Composer (installed)
- ✅ Node.js & Yarn (installed)
- ⚠️ MySQL 8.0+ on port 8889 (needs to be started manually)

## 🗃️ Database Setup

```sql
-- MySQL commands to run before starting
CREATE DATABASE `inventory-azhar`;
-- Or ensure your MySQL is running on port 8889
```

## 🔗 Access URLs

- **Frontend (React)**: http://localhost:3000
- **Backend API**: http://localhost:8000/api
- **Admin Panel**: http://localhost:8000/admin
- **Admin Login**: http://localhost:8000/login

## 📝 Scripts Available

| Script | Description |
|--------|-------------|
| `./setup.sh` | Quick setup for both frontend & backend |
| `./start-dev.sh` | Start both servers simultaneously |
| `./run-migrations.sh` | Run database migrations |
| `./run-seeder.sh` | Seed database with sample data |

## 🎨 Tech Stack

### Backend
- **Laravel 10** + **Livewire** (Hybrid approach)
- **PHP 8.2+** compatibility
- **MySQL** database (port 8889)
- **API endpoints** for React frontend

### Frontend
- **React 18** + **TypeScript**
- **Tailwind CSS** + **Shadcn/ui**
- **Yarn** package manager
- **CRACO** for optimized builds

## 🚨 Important Notes

1. **Hybrid Architecture**: 
   - Laravel Livewire untuk admin panel
   - React untuk company profile frontend
   - Keduanya berjalan bersamaan

2. **Database Configuration**:
   - Pastikan MySQL berjalan di port 8889
   - Database name harus: `inventory-azhar`

3. **Development Workflow**:
   - Gunakan `yarn` untuk frontend (JANGAN npm)
   - Backend auto-reload dengan Laravel
   - Frontend auto-reload dengan CRACO

4. **Performance Optimizations**:
   - Composer optimized autoloader
   - Yarn caching
   - CRACO webpack optimizations
   - Hot reload configuration

## 🛠️ Troubleshooting

### CORS Issues
```bash
# Check backend/config/cors.php
# Ensure allowed_origins includes 'http://localhost:3000'
```

### Database Connection
```bash
# Check MySQL is running on port 8889
netstat -ln | grep :8889

# Test database connection
cd backend && php artisan migrate:status
```

### Port Conflicts
```bash
# If ports are busy, kill processes:
lsof -ti:3000 | xargs kill -9  # Frontend
lsof -ti:8000 | xargs kill -9  # Backend
```

## ✅ Verification Checklist

- [ ] MySQL server running on port 8889
- [ ] Database `inventory-azhar` created
- [ ] Backend dependencies installed (`vendor/` folder exists)
- [ ] Frontend dependencies installed (`node_modules/` folder exists)
- [ ] Laravel app key generated (check `backend/.env`)
- [ ] Both servers start without errors
- [ ] Frontend can reach backend API

---

**Setup berhasil! Website siap untuk development dan deployment.** 🎉