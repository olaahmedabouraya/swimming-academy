#!/bin/bash
cd "$(dirname "$0")/backend"
php artisan serve --host=0.0.0.0 --port=8000



