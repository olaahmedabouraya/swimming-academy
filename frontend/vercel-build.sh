#!/bin/bash
# Build script for Vercel that injects API URL

# Get API URL from environment variable
API_URL=${API_URL:-"https://your-backend-url.up.railway.app/api"}

# Build Angular app
npm run build

# Replace placeholder in index.html with actual API URL
if [ -f "dist/swimming-academy/index.html" ]; then
  sed -i "s|%API_URL%|${API_URL}|g" dist/swimming-academy/index.html
fi

echo "Build complete with API URL: ${API_URL}"

