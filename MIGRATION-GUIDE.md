# Migration Guide: Separate Ports → Unified Application

## 🎯 Overview

Panduan ini menjelaskan migrasi dari arsitektur terpisah (React port 3000 + Laravel port 8000) ke aplikasi unified (single port 8000).

## 🔄 What Changed

### Before (Separate Architecture)
```
├── frontend/ (React - port 3000)
├── backend/ (Laravel API - port 8000)
└── inventory-azhar/ (Laravel Livewire - port 8000)

Issues:
❌ CORS issues between React and Laravel
❌ Session/cookie tidak persist antar domain
❌ Complex deployment (multiple ports)
❌ SSO redirect problems
```

### After (Unified Architecture)
```
├── Laravel Application (port 8000)
├── resources/js/react/ (React SPA - integrated)
├── app/Http/Livewire/ (Livewire - integrated)
└── app/Http/Controllers/Api/ (API - integrated)

Benefits:
✅ Single domain/port - no CORS
✅ Shared Laravel sessions
✅ Seamless authentication
✅ Easy deployment
```

## 📋 Migration Steps

### Step 1: Backup Original Projects
```bash
# Backup existing folders
mv frontend frontend-backup
mv backend backend-backup  
mv inventory-azhar inventory-azhar-backup
```

### Step 2: Use Unified Application
```bash
# The unified application is already ready in root directory
# All features from separate folders have been merged

# Start unified app
./start-unified.sh
```

### Step 3: Verify Migration

#### ✅ Check React SPA Features:
- Visit: `http://localhost:8000` (Company Profile)
- Login: `http://localhost:8000/login`
- Admin Dashboard: `http://localhost:8000/admin/dashboard`

#### ✅ Check Livewire Admin Features:
- Admin Login: `http://localhost:8000/admin-login`
- Dashboard: `http://localhost:8000/admin`
- Content Management: `http://localhost:8000/content/*`

#### ✅ Check API Features:
- Test APIs: `http://localhost:8000/api/*`
- Authentication: Works with shared session

## 🔐 Access Control Migration

### Old Role System → New Role System

| Role | Old Access | New Access | 
|------|------------|------------|
| **superadmin** | All features | ✅ All features except content-admin exclusive |
| **content-admin** | Content only | ✅ Only content management `/content/*` |
| **admin** | Limited access | ✅ Redirected to appropriate dashboard |

### Access URLs:

#### Superadmin:
```bash
# Can access ALL of these:
http://localhost:8000/admin                    # Livewire Dashboard
http://localhost:8000/admin/dashboard          # React Dashboard  
http://localhost:8000/transaksi               # Transactions
http://localhost:8000/data-barang             # Goods Management
http://localhost:8000/data-order              # Order Management
http://localhost:8000/content/*               # Content Management
```

#### Content-Admin:
```bash
# Can ONLY access these:
http://localhost:8000/admin/content            # React Content Dashboard
http://localhost:8000/content/hero-sections   # Hero Management
http://localhost:8000/content/brands          # Brand Management  
http://localhost:8000/content/teams           # Team Management
http://localhost:8000/content/services        # Service Management
http://localhost:8000/content/about           # About Management
http://localhost:8000/content/analytics       # Content Analytics
```

## 🔧 Configuration Changes

### Frontend API Calls
**Before:**
```javascript
const API_BASE_URL = "http://localhost:8000/api"; // Cross-origin
```

**After:**
```javascript  
const API_BASE_URL = "/api"; // Same domain
```

### Laravel Routes
**Before:**
```php
// Separate route files for different apps
```

**After:**
```php
// Single web.php with role-based middleware:
Route::middleware(['auth:web', 'role:superadmin'])->group(function () {
    // Superadmin only routes
});

Route::middleware(['auth:web', 'role:content-admin|superadmin'])->group(function () {
    // Content management routes
});
```

## 🛠️ Development Workflow

### Old Workflow:
```bash
# Terminal 1: Laravel Backend
cd backend && php artisan serve --port=8000

# Terminal 2: React Frontend  
cd frontend && npm start

# Terminal 3: Laravel Livewire
cd inventory-azhar && php artisan serve --port=8001
```

### New Workflow:
```bash
# Single terminal for production:
./start-unified.sh

# OR for development with hot reload:
# Terminal 1: Laravel server
php artisan serve --host=0.0.0.0 --port=8000

# Terminal 2: React dev server (with Vite)
npm run dev
```

## 📦 Deployment Changes

### Old Deployment:
```bash
# Deploy 3 separate applications
# Configure multiple domains/subdomains
# Manage CORS settings
# Complex nginx configuration
```

### New Deployment:
```bash
# Single Laravel application deployment
composer install --optimize-autoloader --no-dev
npm ci && npm run build
php artisan migrate --force
php artisan config:cache

# Single nginx/apache configuration pointing to public/
```

## 🐛 Troubleshooting Migration

### Issue 1: "React components not loading"
```bash
# Solution: Build React assets
npm run build
```

### Issue 2: "API calls failing"
```bash
# Check if API routes are accessible:
curl http://localhost:8000/api/hero-sections

# If not working, check:
1. php artisan route:list | grep api
2. Check .env database configuration
3. php artisan migrate --seed
```

### Issue 3: "Livewire components not found"
```bash
# Clear application cache:
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

### Issue 4: "Role-based access not working"
```bash
# Check role seeder:
php artisan db:seed --class=RoleSeeder

# Verify user roles:
php artisan tinker
>>> App\Models\User::with('roles')->get()
```

## 📊 Feature Comparison

| Feature | Old Setup | New Setup | Status |
|---------|-----------|-----------|---------|
| Company Profile | React (port 3000) | React SPA (port 8000) | ✅ Migrated |
| Admin Dashboard | React (port 3000) | React SPA (port 8000) | ✅ Migrated |
| Inventory Management | Livewire (port 8000) | Livewire (port 8000) | ✅ Migrated |
| API Services | Laravel (port 8000) | Laravel (port 8000) | ✅ Migrated |
| Authentication | Separate sessions | Shared Laravel session | ✅ Improved |
| CORS | Cross-origin issues | Same domain | ✅ Resolved |
| Content Management | Mixed access | Role-based access | ✅ Improved |

## 🎯 Next Steps

1. **Test all features** in unified application
2. **Update any remaining configurations** if needed
3. **Deploy to production** using single port setup
4. **Remove backup folders** once migration confirmed successful:
   ```bash
   rm -rf frontend-backup backend-backup inventory-azhar-backup
   ```

## 🆘 Rollback Plan

If migration issues occur:
```bash
# Restore original structure:
mv frontend-backup frontend
mv backend-backup backend  
mv inventory-azhar-backup inventory-azhar

# Use old startup script:
./start-dev.sh
```

---

**Migration completed successfully! 🎉**

The unified application now provides:
- ✅ Single port operation (8000)
- ✅ Seamless session management
- ✅ Proper role-based access control
- ✅ No CORS issues
- ✅ Simplified deployment