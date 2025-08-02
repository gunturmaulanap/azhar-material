@echo off
REM Azhar Material - Development Startup Script for Windows

echo 🚀 Starting Azhar Material Development Environment...

REM Check prerequisites
echo 📋 Checking prerequisites...

where php >nul 2>nul
if %errorlevel% neq 0 (
    echo ❌ PHP is not installed. Please install PHP 8.1+
    pause
    exit /b 1
)

where composer >nul 2>nul
if %errorlevel% neq 0 (
    echo ❌ Composer is not installed. Please install Composer
    pause
    exit /b 1
)

where node >nul 2>nul
if %errorlevel% neq 0 (
    echo ❌ Node.js is not installed. Please install Node.js 16+
    pause
    exit /b 1
)

where npm >nul 2>nul
if %errorlevel% neq 0 (
    echo ❌ npm is not installed. Please install npm
    pause
    exit /b 1
)

echo ✅ All prerequisites are installed

REM Start Laravel Backend
echo 🔧 Starting Laravel Backend...
cd inventory-azhar

REM Check if .env exists
if not exist .env (
    echo 📝 Creating .env file...
    copy .env.example .env
    php artisan key:generate
    echo ⚠️  Please configure your database settings in .env file
)

REM Install dependencies if needed
if not exist vendor (
    echo 📦 Installing Laravel dependencies...
    composer install
)

REM Start Laravel server in background
echo 🌐 Starting Laravel server on http://localhost:8000
start "Laravel Backend" cmd /k "php artisan serve --host=0.0.0.0 --port=8000"

REM Wait a moment for Laravel to start
timeout /t 3 /nobreak >nul

REM Start React Frontend
echo ⚛️ Starting React Frontend...
cd ..\abu\frontend

REM Install dependencies if needed
if not exist node_modules (
    echo 📦 Installing React dependencies...
    npm install
)

REM Check if .env exists
if not exist .env (
    echo 📝 Creating .env file...
    copy env.example .env
)

REM Start React server
echo 🌐 Starting React server on http://localhost:3000
start "React Frontend" cmd /k "npm start"

echo.
echo 🎉 Development environment is running!
echo.
echo 📱 Frontend (React): http://localhost:3000
echo 🔧 Backend (Laravel): http://localhost:8000
echo 📊 Laravel Admin: http://localhost:8000/login
echo.
echo Close the command windows to stop the servers
echo.
pause 