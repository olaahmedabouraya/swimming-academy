#!/bin/sh
set -e
# Run migrations when container starts (DB credentials available at runtime)
php artisan migrate --force
exec "$@"
