#!/bin/bash

# Azhar Material - Quick Setup Script
echo "🏗️ Azhar Material - Quick Setup"

# Make sure we're in the right directory
cd /app

# Setup Backend
echo "🔧 Setting up Backend (Laravel)..."
cd backend

# Install dependencies if not exists
if [ ! -d "vendor" ]; then
    echo "📦 Installing Laravel dependencies..."
    COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader
fi

# Generate app key if needed
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

echo "✅ Backend setup completed!"

# Setup Frontend
echo "⚛️ Setting up Frontend (React)..."
cd ../frontend

# Install dependencies if not exists
if [ ! -d "node_modules" ]; then
    echo "📦 Installing React dependencies..."
    yarn install
fi

echo "✅ Frontend setup completed!"

echo ""
echo "🎉 Setup completed successfully!"
echo ""
echo "📋 Next steps:"
echo "1. Make sure MySQL server is running on port 8889"
echo "2. Create database 'inventory-azhar'"
echo "3. Run: ./start-dev.sh"
echo ""
echo "🔗 Access URLs:"
echo "- Frontend (React): http://localhost:3000"
echo "- Backend (Laravel): http://localhost:8000"
echo "- Admin Panel: http://localhost:8000/admin"