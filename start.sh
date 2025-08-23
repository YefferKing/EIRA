#!/bin/bash
php artisan config:cache
php artisan route:cache
php -S 0.0.0.0:${PORT:-8080} -t public/ public/index.php