# Setup Guide - Azhar Material Project

## Struktur Project

```
Azhar Material - Project/
├── inventory-azhar/          # Backend Laravel Livewire
│   ├── app/
│   │   ├── Http/Controllers/Api/  # API Controllers untuk React
│   │   └── Models/
│   ├── routes/api.php        # API Routes
│   └── ...
└── abu/                      # Frontend React
    └── frontend/
        ├── src/
        │   ├── pages/        # Halaman React
        │   ├── services/     # API Services
        │   ├── hooks/        # Custom Hooks
        │   └── config/       # Konfigurasi API
        └── ...
```

## Prerequisites

- PHP 8.1+
- Composer
- Node.js 16+
- MySQL 8.0+
- Laravel Sanctum (sudah terinstall)

## Setup Backend (Laravel Livewire)

1. **Masuk ke direktori backend:**
   ```bash
   cd inventory-azhar
   ```

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Setup environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi database di .env:**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=inventory_azhar
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Jalankan migration dan seeder:**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Jalankan server:**
   ```bash
   php artisan serve
   ```

   Backend akan berjalan di: http://localhost:8000

## Setup Frontend (React)

1. **Masuk ke direktori frontend:**
   ```bash
   cd abu/frontend
   ```

2. **Install dependencies:**
   ```bash
   npm install
   ```

3. **Setup environment:**
   ```bash
   cp env.example .env
   ```

4. **Jalankan development server:**
   ```bash
   npm start
   ```

   Frontend akan berjalan di: http://localhost:3000

## API Endpoints

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

### Contact
- `POST /api/contact` - Send contact message

## Fitur yang Sudah Diintegrasikan

### Backend (Laravel Livewire)
- ✅ POS (Point of Sale)
- ✅ Inventory Management
- ✅ Delivery Management
- ✅ User Management
- ✅ Reports
- ✅ API Endpoints untuk React

### Frontend (React)
- ✅ Company Profile Website
- ✅ Product Catalog (terintegrasi dengan Laravel)
- ✅ Authentication (terintegrasi dengan Laravel)
- ✅ Contact Form
- ✅ Responsive Design

## Testing

### Test Backend API
```bash
cd inventory-azhar
php artisan serve
```

Kemudian test API dengan Postman atau curl:
```bash
curl http://localhost:8000/api/products
```

### Test Frontend
```bash
cd abu/frontend
npm start
```

Buka browser dan akses: http://localhost:3000

## Troubleshooting

### CORS Issues
Jika ada masalah CORS, pastikan konfigurasi CORS di Laravel sudah benar:
- File: `inventory-azhar/config/cors.php`
- Pastikan `allowed_origins` sudah diset ke `['*']` atau `['http://localhost:3000']`

### Database Connection
- Pastikan MySQL server berjalan
- Periksa konfigurasi database di `.env`
- Jalankan `php artisan migrate:fresh --seed` jika perlu reset database

### API Connection
- Pastikan backend Laravel berjalan di port 8000
- Periksa URL API di frontend: `abu/frontend/src/config/api.js`
- Pastikan tidak ada firewall yang memblokir koneksi

## Development Notes

- Backend menggunakan Laravel 10 + Livewire untuk admin panel
- Frontend menggunakan React + TypeScript untuk company profile
- API menggunakan Laravel Sanctum untuk authentication
- Database menggunakan MySQL
- UI menggunakan Tailwind CSS + Shadcn/ui 