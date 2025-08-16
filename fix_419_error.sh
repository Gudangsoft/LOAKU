#!/bin/bash
# Fix script untuk loa.siptenan.org
# Run di server melalui SSH atau terminal

echo "=========================================="
echo "FIXING LOA.SIPTENAN.ORG - 419 PAGE EXPIRED"
echo "=========================================="

# Navigate ke folder Laravel
cd /home/wwwroot/LOAKU || cd /var/www/html/LOAKU || cd /public_html/LOAKU

echo "1. Clearing all Laravel caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan session:flush

echo "2. Regenerating application key..."
php artisan key:generate --force

echo "3. Setting proper permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 777 storage/framework/sessions/
chmod -R 777 storage/framework/views/
chmod -R 777 storage/logs/

echo "4. Creating storage directories if missing..."
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs

echo "5. Setting ownership (if running as root)..."
# chown -R www-data:www-data storage/ 2>/dev/null || chown -R apache:apache storage/ 2>/dev/null || echo "Skipping chown"

echo "6. Checking .env file..."
if [ ! -f ".env" ]; then
    echo "WARNING: .env file not found! Copying from .env.example..."
    cp .env.example .env
    php artisan key:generate --force
fi

echo "7. Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=========================================="
echo "FIXES APPLIED!"
echo "=========================================="
echo "Test URLs:"
echo "- https://loa.siptenan.org/admin/login"
echo "- https://loa.siptenan.org/"
echo "- https://loa.siptenan.org/publisher/login"
echo ""
echo "If still getting 419 errors:"
echo "1. Clear browser cache (Ctrl+F5)"
echo "2. Try incognito/private window"
echo "3. Check server error logs"
echo "=========================================="
