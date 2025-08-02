#!/bin/bash

# Azhar Material - Database Seeder Script
echo "🌱 Running Database Seeders..."

cd backend

# Check if .env exists
if [ ! -f .env ]; then
    echo "❌ .env file not found. Please run setup first."
    exit 1
fi

# Run migrations
echo "📊 Running migrations..."
php artisan migrate:fresh

# Run additional migrations if they exist
echo "🔧 Running additional migrations..."
php artisan migrate

# Run seeders
echo "🌱 Running seeders..."
php artisan db:seed

echo "✅ Database seeded successfully!"
echo ""
echo "📋 Sample data includes:"
echo "   - Categories: 100+ categories"
echo "   - Brands: 50+ brands"
echo "   - Products: 1000+ products"
echo ""
echo "🔗 You can now access:"
echo "   - Frontend: http://localhost:3000"
echo "   - Backend: http://localhost:8000"
echo "   - Admin: http://localhost:8000/login" 