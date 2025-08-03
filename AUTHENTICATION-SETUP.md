# Authentication Setup Documentation

## Sistem Authentication Hybrid React-Laravel

Sistem ini mendukung authentication hybrid antara React frontend (localhost:3000) dan Laravel backend (localhost:8000) dengan session-based authentication dan role management menggunakan Spatie Laravel Permission.

## Konfigurasi yang Telah Dibuat

### 1. CORS Configuration
File: `backend/config/cors.php`
- ✅ Mengizinkan `http://localhost:3000` sebagai origin
- ✅ `supports_credentials` set ke `true` untuk cookies
- ✅ Mengizinkan headers yang diperlukan

### 2. Session Configuration
File: `backend/config/session.php`
- ✅ Domain configurable melalui `.env`
- ✅ Same-site policy set ke `lax`
- ✅ Secure cookie configurable
- ✅ File permissions set untuk storage/framework/sessions

### 3. Environment Variables (.env)
```bash
# Session Configuration
SESSION_DOMAIN=localhost
SESSION_SECURE_COOKIE=false
SESSION_SAME_SITE=lax

# Frontend URL untuk CORS
FRONTEND_URL=http://localhost:3000

# Sanctum Configuration
SANCTUM_STATEFUL_DOMAINS=localhost:3000,127.0.0.1:3000
```

### 4. Authentication Guards
File: `backend/config/auth.php`
- ✅ Guard `web` untuk admin users (User model)
- ✅ Guard `customer` untuk customers (Customer model)
- ✅ Kedua guard menggunakan session driver

### 5. Spatie Roles & Permissions
File: `backend/database/seeders/RolePermissionSeeder.php`

#### Roles yang Tersedia:
- **super_admin**: Full access ke semua fitur
- **admin**: Access ke CRUD customers, products, transactions, orders
- **content-admin**: Hanya content management (hero sections, brands, services, about, teams)
- **owner**: Business owner access (view reports, analytics, customers, products)

#### Demo Accounts:
- Customer: `customer / password`
- Admin: `admin / password`
- Super Admin: `miura / password`
- Content Admin: `contentadmin / password`

### 6. API Endpoints
File: `backend/routes/api.php`
- ✅ `GET /api/csrf-token` - Get CSRF token
- ✅ `POST /api/auth/login` - Login endpoint
- ✅ Session regeneration untuk security
- ✅ Cookie handling untuk cross-origin

### 7. Frontend Configuration
File: `frontend/src/config/api.js`
- ✅ `withCredentials: true` untuk cookies
- ✅ CSRF token handling
- ✅ Auto token refresh

File: `frontend/src/hooks/useAuth.js`
- ✅ Session-based authentication
- ✅ CSRF token management
- ✅ Proper error handling

### 8. Livewire Components Update
File: `backend/app/Http/Livewire/Master/AdminForm.php`
- ✅ Spatie role assignment untuk new users
- ✅ Role sync untuk existing users
- ✅ Dynamic role dropdown

File: `backend/app/Http/Livewire/Master/Admin.php`
- ✅ Filter users berdasarkan admin roles
- ✅ Role display dengan badges

### 9. Route Protection
File: `backend/routes/web.php`
- ✅ Role middleware integration
- ✅ Owner role support
- ✅ Multiple guard support

## Cara Kerja Authentication Flow

### 1. Login dari React (localhost:3000)
1. User memilih role dan memasukkan credentials
2. Frontend request CSRF token ke `/api/csrf-token`
3. Login request ke `/api/auth/login` dengan cookies enabled
4. Laravel validates credentials dan regenerate session
5. Return response dengan redirectUrl berdasarkan role
6. Frontend redirect ke Laravel dengan session yang sudah terset

### 2. Role-based Redirect
- **Customer**: `http://localhost:8000/customer/{id}` (Livewire hybrid)
- **Admin/Super Admin/Owner**: `http://localhost:8000/sso-login/{userId}` → `/dashboard`
- **Content Admin**: `http://localhost:8000/sso-login/{userId}` → `/content/dashboard`

### 3. SSO Login Handler
File: `backend/app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- Validates user ID
- Login user ke Laravel session
- Redirect sesuai role dengan session yang valid

## Komponen Livewire yang Diupdate

### 1. Admin Management
- **List**: Role badges dengan color coding
- **Form**: Dynamic role dropdown dari Spatie
- **Integration**: Auto assign/sync Spatie roles

### 2. Customer Management
- Password hashing yang benar
- Username unique validation
- Integration dengan customer guard

## Security Features

1. **Session Regeneration**: Session di-regenerate setelah login
2. **CSRF Protection**: Token validation untuk semua requests
3. **Role-based Access**: Spatie permission system
4. **Cross-Origin Cookies**: Secure cookie handling
5. **Password Hashing**: Proper bcrypt hashing

## Testing Authentication

### 1. Test Login Flow
```bash
# 1. Akses React frontend
http://localhost:3000/login

# 2. Pilih role dan masukkan credentials
# 3. Verify redirect ke Laravel dashboard
# 4. Check session persistence
```

### 2. Test Role Access
```bash
# Test different roles:
- Super Admin: Full dashboard access
- Admin: Limited dashboard access
- Content Admin: Content management only
- Customer: Customer dashboard
```

## Troubleshooting

### 1. CORS Issues
- Pastikan `FRONTEND_URL` di .env sudah benar
- Check browser console untuk CORS errors
- Verify `supports_credentials: true` di cors.php

### 2. Session Issues
- Check file permissions di `storage/framework/sessions`
- Verify session domain configuration
- Clear browser cookies jika ada konflik

### 3. Role Issues
- Run RolePermissionSeeder: `php artisan db:seed --class=RolePermissionSeeder`
- Check user roles: `User::with('roles')->find($id)`
- Verify middleware configuration

## Future Enhancements

1. **Password Reset**: Implement forgot password flow
2. **2FA**: Add two-factor authentication
3. **API Tokens**: Personal access tokens untuk mobile
4. **Audit Log**: Track user activities
5. **Permission Management**: Dynamic permission assignment

## File Structure
```
backend/
├── app/Http/
│   ├── Controllers/Api/AuthController.php
│   ├── Controllers/Auth/AuthenticatedSessionController.php
│   ├── Livewire/Master/
│   │   ├── Admin.php
│   │   ├── AdminForm.php
│   │   ├── Customer.php
│   │   └── CustomerForm.php
│   └── Middleware/CheckRole.php
├── config/
│   ├── cors.php
│   ├── session.php
│   ├── auth.php
│   └── sanctum.php
├── database/seeders/
│   ├── RolePermissionSeeder.php
│   └── DatabaseSeeder.php
└── routes/
    ├── api.php
    └── web.php

frontend/
├── src/
│   ├── config/api.js
│   ├── hooks/useAuth.js
│   ├── services/api.js
│   └── pages/Login.tsx
```

Sistem authentication ini memberikan pengalaman yang seamless antara React frontend dan Laravel backend dengan security yang robust dan role management yang fleksibel.