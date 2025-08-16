#!/bin/bash
# Quick Update Script for loa.siptenan.org
# Run this on your server after uploading files

echo "=========================================="
echo "QUICK UPDATE FOR LOA SIPTENAN.ORG"
echo "=========================================="

# Check if we're in Laravel root
if [ ! -f "artisan" ]; then
    echo "ERROR: Please run this script from Laravel root directory"
    exit 1
fi

echo "Backing up current files..."
cp resources/views/publisher/loa-requests/show.blade.php resources/views/publisher/loa-requests/show.blade.php.backup.$(date +%Y%m%d_%H%M%S) 2>/dev/null || true
cp app/Http/Controllers/PublisherController.php app/Http/Controllers/PublisherController.php.backup.$(date +%Y%m%d_%H%M%S) 2>/dev/null || true

echo "Setting file permissions..."
chmod 755 resources/views/publisher/loa-requests/
chmod 644 resources/views/publisher/loa-requests/show.blade.php
chmod 644 app/Http/Controllers/PublisherController.php
chmod 644 app/Http/Middleware/PublisherMiddleware.php
chmod 644 routes/web.php

echo "Clearing Laravel caches..."
php artisan view:clear
php artisan cache:clear
php artisan config:clear

# Set storage permissions
chmod -R 777 storage/framework/views/ 2>/dev/null || true
chmod -R 777 storage/logs/ 2>/dev/null || true

echo "=========================================="
echo "UPDATE COMPLETED!"
echo "=========================================="
echo "Test URL: http://loa.siptenan.org/publisher/loa-requests/2"
echo "If still blank, try:"
echo "1. Clear browser cache (Ctrl+F5)"
echo "2. Check error logs in storage/logs/"
echo "3. Verify file permissions"
echo "=========================================="
