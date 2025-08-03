#!/bin/bash

# Azhar Material - Unified Development Startup Script
echo "🚀 Starting Azhar Material Unified Application..."

# Function to check if a command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Check prerequisites
echo "📋 Checking prerequisites..."

if ! command_exists php; then
    echo "❌ PHP is not installed. Please install PHP 8.2+"
    exit 1
fi

if ! command_exists composer; then
    echo "❌ Composer is not installed. Please install Composer"
    exit 1
fi

if ! command_exists node; then
    echo "❌ Node.js is not installed. Please install Node.js 18+"
    exit 1
fi

if ! command_exists npm; then
    echo "❌ npm is not installed. Please install npm"
    exit 1
fi

echo "✅ All prerequisites are installed"

# Check if .env exists
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    php artisan key:generate
    echo "⚠️  Please configure your database settings in .env file"
fi

# Install dependencies if needed
if [ ! -d "vendor" ]; then
    echo "📦 Installing PHP dependencies..."
    composer install
fi

if [ ! -d "node_modules" ]; then
    echo "📦 Installing Node.js dependencies..."
    npm install
fi

# Run database migrations and seeders
echo "🗄️  Running database migrations..."
php artisan migrate --force

echo "🌱 Running database seeders..."
php artisan db:seed --force

# Build React assets
echo "🏗️  Building React assets..."
npm run build

# Function to cleanup on exit
cleanup() {
    echo "🛑 Shutting down server..."
    kill $LARAVEL_PID 2>/dev/null
    echo "✅ Server stopped"
    exit 0
}

# Set up signal handlers
trap cleanup SIGINT SIGTERM

# Start unified Laravel server with built React assets
echo "🌐 Starting Unified Laravel Server on http://localhost:8000"
php artisan serve --host=0.0.0.0 --port=8000 &
LARAVEL_PID=$!

echo ""
echo "🎉 Unified application is running!"
echo ""
echo "🌍 Frontend (React SPA): http://localhost:8000"
echo "🔧 Admin Panel (Livewire): http://localhost:8000/admin-login"
echo "📊 Content Admin: http://localhost:8000/content (for content-admin role)"
echo "📈 Superadmin Dashboard: http://localhost:8000/admin (for superadmin role)"
echo ""
echo "📱 All features now run on single port 8000"
echo "🔐 Session/cookies work seamlessly across all features"
echo ""
echo "Press Ctrl+C to stop the server"
echo ""

# Wait for process
wait