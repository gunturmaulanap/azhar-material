@echo off
REM Azhar Material - Database Seeder Script for Windows

echo 🌱 Running Database Seeders...

cd inventory-azhar

REM Check if .env exists
if not exist .env (
    echo ❌ .env file not found. Please run setup first.
    pause
    exit /b 1
)

REM Run migrations
echo 📊 Running migrations...
php artisan migrate:fresh

REM Run seeders
echo 🌱 Running seeders...
php artisan db:seed

echo ✅ Database seeded successfully!
echo.
echo 📋 Sample data includes:
echo    - Categories: 100+ categories
echo    - Brands: 50+ brands
echo    - Products: 1000+ products
echo.
echo 🔗 You can now access:
echo    - Frontend: http://localhost:3000
echo    - Backend: http://localhost:8000
echo    - Admin: http://localhost:8000/login

pause 