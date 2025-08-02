#!/bin/bash

# Azhar Material - Update Seeder Script
echo "🔄 Updating Database with Additional Fields..."

cd inventory-azhar

# Check if .env exists
if [ ! -f .env ]; then
    echo "❌ .env file not found. Please run setup first."
    exit 1
fi

# Run additional migrations
echo "🔧 Running additional migrations..."
php artisan migrate

echo "✅ Database updated successfully!"
echo ""
echo "📋 New fields added:"
echo "   - description: Text field for product description"
echo "   - image: String field for product image URL"
echo ""
echo "🔗 You can now access:"
echo "   - Frontend: http://localhost:3000"
echo "   - Backend: http://localhost:8000"
echo "   - Admin: http://localhost:8000/login" 