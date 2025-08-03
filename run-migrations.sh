#!/bin/bash

# Azhar Material - Run Migrations Script
echo "🔄 Running Database Migrations..."

cd inventory-azhar

# Check if .env exists
if [ ! -f .env ]; then
    echo "❌ .env file not found. Please run setup first."
    exit 1
fi

# Run migrations
echo "📊 Running migrations..."
php artisan migrate

echo "✅ Migrations completed successfully!"
echo ""
echo "📋 What was added:"
echo "   - Hero sections table"
echo "   - Additional fields to brands table"
echo "   - Description and image fields to goods table"
echo ""
echo "🔗 You can now access:"
echo "   - Company Profile: http://localhost:3000"
echo "   - Admin Panel: http://localhost:8000/admin"
echo "   - API: http://localhost:8000/api" 