#!/bin/bash

echo "🚀 Swimming Academy Deployment Helper"
echo "======================================"
echo ""

# Check if git is initialized
if [ ! -d ".git" ]; then
    echo "📦 Initializing Git repository..."
    git init
    git branch -M main
    echo "✅ Git initialized"
    echo ""
fi

echo "📋 Deployment Checklist:"
echo ""
echo "1. Database Setup (Supabase):"
echo "   - Go to https://supabase.com"
echo "   - Create new project"
echo "   - Save database credentials"
echo ""
echo "2. Backend Deployment (Railway):"
echo "   - Go to https://railway.app"
echo "   - Deploy from GitHub"
echo "   - Set environment variables (see DEPLOYMENT.md)"
echo "   - Run migrations: php artisan migrate --force"
echo ""
echo "3. Frontend Deployment (Vercel):"
echo "   - Go to https://vercel.com"
echo "   - Import GitHub repository"
echo "   - Set Root Directory: frontend"
echo "   - Add API_URL environment variable"
echo ""
echo "📚 For detailed instructions, see:"
echo "   - QUICK_START.md (15-minute guide)"
echo "   - DEPLOYMENT.md (detailed guide)"
echo ""
echo "✅ All deployment config files are ready!"
echo ""

