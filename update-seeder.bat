@echo off
REM Azhar Material - Update Seeder Script for Windows

echo 🔄 Updating Database with Additional Fields...

cd inventory-azhar

REM Check if .env exists
if not exist .env (
    echo ❌ .env file not found. Please run setup first.
    pause
    exit /b 1
)

REM Run additional migrations
echo 🔧 Running additional migrations...
php artisan migrate

echo ✅ Database updated successfully!
echo.
echo 📋 New fields added:
echo    - description: Text field for product description
echo    - image: String field for product image URL
echo.
echo 🔗 You can now access:
echo    - Frontend: http://localhost:3000
echo    - Backend: http://localhost:8000
echo    - Admin: http://localhost:8000/login

pause 