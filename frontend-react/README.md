# Frontend Application

Aplikasi frontend React yang terintegrasi dengan Laravel backend.

## Instalasi

1. Install dependencies:
```bash
npm install
# atau
yarn install
```

2. Copy file environment:
```bash
cp env.example .env
```

3. Sesuaikan konfigurasi di file `.env`:
```env
REACT_APP_API_URL=http://localhost:8000/api
REACT_APP_LARAVEL_URL=http://localhost:8000
```

## Menjalankan Aplikasi

### Development Mode
```bash
npm run dev
# atau
npm start
```

Aplikasi akan berjalan di `http://localhost:3000`

### Production Build
```bash
npm run build
```

## Integrasi dengan Laravel

### 1. Konfigurasi Laravel

Pastikan Laravel backend berjalan di `http://localhost:8000` dan memiliki:

- CORS middleware yang dikonfigurasi
- API routes yang sesuai dengan endpoints di `src/config/api.js`
- Sanctum atau Passport untuk authentication

### 2. CORS Configuration di Laravel

Tambahkan di `config/cors.php`:
```php
return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:3000'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
```

### 3. API Routes di Laravel

Pastikan Laravel memiliki routes yang sesuai:
```php
// routes/api.php
Route::prefix('api')->group(function () {
    // Auth routes
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [UserController::class, 'show']);
        
        // Products
        Route::apiResource('products', ProductController::class);
        
        // Brands
        Route::apiResource('brands', BrandController::class);
        
        // Services
        Route::apiResource('services', ServiceController::class);
        
        // Team
        Route::apiResource('team', TeamController::class);
    });
    
    // Public routes
    Route::post('/contact', [ContactController::class, 'store']);
});
```

## Struktur Proyek

```
src/
├── components/          # Reusable components
├── pages/              # Page components
├── services/           # API services
├── config/             # Configuration files
├── hooks/              # Custom React hooks
├── lib/                # Utility libraries
└── utils/              # Helper functions
```

## Fitur

- ✅ React Router untuk navigation
- ✅ Tailwind CSS untuk styling
- ✅ Axios untuk HTTP requests
- ✅ CSRF token support untuk Laravel
- ✅ Authentication dengan JWT/Sanctum
- ✅ Responsive design
- ✅ TypeScript support

## Troubleshooting

### CORS Issues
Jika mengalami masalah CORS, pastikan:
1. Laravel CORS middleware sudah dikonfigurasi
2. Frontend dan backend berjalan di port yang benar
3. Credentials diaktifkan di axios config

### Authentication Issues
1. Pastikan CSRF token tersedia di meta tag
2. Check Authorization header di request
3. Verify Laravel Sanctum/Passport configuration

## Development

### Hot Reload
Aplikasi menggunakan CRACO untuk konfigurasi webpack yang lebih fleksibel. Hot reload sudah dikonfigurasi untuk development.

### Environment Variables
Semua environment variables harus diawali dengan `REACT_APP_` untuk dapat diakses di aplikasi React.
