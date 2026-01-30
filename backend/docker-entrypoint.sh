#!/bin/sh
set -e
# Run migrations when container starts (don't fail container if migrate fails)
php artisan migrate --force || true
# Bind to PORT so Render detects the service (default 10000 if unset)
exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
