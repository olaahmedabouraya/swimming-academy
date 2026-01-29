# 🏊 Olympia Sports Academy

A full-stack swimming academy management system built with Angular and Laravel.

## 🚀 Quick Deployment

See [QUICK_START.md](./QUICK_START.md) for step-by-step deployment instructions.

## 📁 Project Structure

```
swimming-academy/
├── frontend/          # Angular 17 application
├── backend/           # Laravel 10 API
└── DEPLOYMENT.md      # Detailed deployment guide
```

## 🛠️ Local Development

### Frontend
```bash
cd frontend
npm install
npm start
# Runs on http://localhost:4200
```

### Backend
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
# Runs on http://localhost:8000
```

## 🌐 Free Hosting Setup

- **Frontend**: Vercel (Free)
- **Backend**: Railway (Free tier)
- **Database**: Supabase (Free tier)

See [DEPLOYMENT.md](./DEPLOYMENT.md) for complete instructions.

## 📝 Environment Variables

### Frontend (Vercel)
- `API_URL`: Your Railway backend URL + `/api`

### Backend (Railway)
- `APP_KEY`: Generated with `php artisan key:generate`
- `DB_*`: Supabase PostgreSQL credentials
- `FRONTEND_URL`: Your Vercel frontend URL

## 📚 Documentation

- [QUICK_START.md](./QUICK_START.md) - Fast deployment guide
- [DEPLOYMENT.md](./DEPLOYMENT.md) - Detailed deployment instructions

## 🆘 Support

For deployment issues, check:
1. Railway logs
2. Vercel build logs
3. Browser console for errors
4. Environment variables are set correctly
