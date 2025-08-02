#!/bin/bash

# Azhar Material - Development Startup Script (Updated Structure)
echo "🚀 Starting Azhar Material Development Environment..."

# Function to check if a command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Check prerequisites
echo "📋 Checking prerequisites..."

if ! command_exists php; then
    echo "❌ PHP is not installed. Please install PHP 8.3.9+"
    exit 1
fi

if ! command_exists composer; then
    echo "❌ Composer is not installed. Please install Composer"
    exit 1
fi

if ! command_exists node; then
    echo "❌ Node.js is not installed. Please install Node.js 16+"
    exit 1
fi

if ! command_exists yarn; then
    echo "⚠️ yarn not found, installing yarn globally..."
    npm install -g yarn
fi

echo "✅ All prerequisites are installed"

# Start Laravel Backend
echo "🔧 Starting Laravel Backend..."
cd backend

# Check if .env exists
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    php artisan key:generate
    echo "⚠️  Please configure your database settings in .env file"
fi

# Install dependencies if needed
if [ ! -d "vendor" ]; then
    echo "📦 Installing Laravel dependencies..."
    composer install
fi

# Generate application key if needed
php artisan key:generate --force

# Run migrations and seeders
echo "🗃️ Setting up database..."
php artisan migrate --force
php artisan db:seed --force

# Start Laravel server in background
echo "🌐 Starting Laravel server on http://localhost:8000"
php artisan serve --host=0.0.0.0 --port=8000 &
LARAVEL_PID=$!

# Wait a moment for Laravel to start
sleep 3

# Start React Frontend
echo "⚛️  Starting React Frontend..."
cd ../frontend

# Install dependencies if needed
if [ ! -d "node_modules" ]; then
    echo "📦 Installing React dependencies..."
    yarn install
fi

# Start React server
echo "🌐 Starting React server on http://localhost:3000"
yarn start &
REACT_PID=$!

# Function to cleanup on exit
cleanup() {
    echo "🛑 Shutting down servers..."
    kill $LARAVEL_PID 2>/dev/null
    kill $REACT_PID 2>/dev/null
    echo "✅ Servers stopped"
    exit 0
}

# Set up signal handlers
trap cleanup SIGINT SIGTERM

echo ""
echo "🎉 Development environment is running!"
echo ""
echo "📱 Frontend (React): http://localhost:3000"
echo "🔧 Backend (Laravel): http://localhost:8000"
echo "📊 Laravel Admin: http://localhost:8000/admin"
echo ""
echo "Press Ctrl+C to stop all servers"
echo ""

# Wait for both processes
wait 