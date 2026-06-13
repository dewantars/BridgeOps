#!/bin/sh
set -e

# Run standard Laravel caching operations in production if running web/queue/scheduler
if [ "$1" = "web" ] || [ "$1" = "queue" ] || [ "$1" = "scheduler" ]; then
    echo "Caching Laravel configuration and routes..."
    # Ensure Laravel storage directories exist and are writable
    mkdir -p storage/framework/cache/data
    mkdir -p storage/framework/sessions
    mkdir -p storage/framework/views
    mkdir -p storage/logs
    
    # Run caching
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

if [ "$1" = "web" ]; then
    echo "Running database migrations..."
    php artisan migrate --force

    echo "Starting Supervisor (Nginx + PHP-FPM)..."
    exec supervisord -c /etc/supervisord.conf
elif [ "$1" = "queue" ]; then
    echo "Starting Laravel Queue Worker..."
    exec php artisan queue:work --verbose --tries=3 --timeout=90
elif [ "$1" = "scheduler" ]; then
    echo "Starting Laravel Scheduler loop..."
    while [ true ]; do
        php artisan schedule:run --no-interaction &
        sleep 60
    done
else
    # Execute any custom command passed to the container
    echo "Running custom command: $@"
    exec "$@"
fi
