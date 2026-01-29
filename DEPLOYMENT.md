# 🚀 Free Deployment Guide - Swimming Academy

This guide will help you deploy your full-stack application (Angular + Laravel + Database) for **FREE** using:
- **Frontend**: Vercel (Free)
- **Backend**: Render (Free - 750 hours/month) OR Railway (Free tier with $5 credit/month)
- **Database**: Supabase (Free tier)

**💡 For 100% free hosting, use Render instead of Railway!** See `DEPLOYMENT_RENDER.md` for Render-specific instructions.

---

## 📋 Prerequisites

1. GitHub account
2. Vercel account (sign up at vercel.com)
3. Railway account (sign up at railway.app)
4. Supabase account (sign up at supabase.com)

---

## 🗄️ Step 1: Set Up Database (Supabase)

### 1.1 Create Supabase Project

1. Go to [supabase.com](https://supabase.com)
2. Click "New Project"
3. Fill in:
   - **Name**: `swimming-academy`
   - **Database Password**: (save this securely!)
   - **Region**: Choose closest to you
4. Click "Create new project"
5. Wait 2-3 minutes for setup

### 1.2 Get Database Connection Details

1. Go to **Settings** → **Database**
2. Scroll to **Connection string** → **URI**
3. Copy the connection string (looks like: `postgresql://postgres:[PASSWORD]@[HOST]:5432/postgres`)
4. Save these details:
   - **Host**: `[HOST].supabase.co`
   - **Port**: `5432`
   - **Database**: `postgres`
   - **Username**: `postgres`
   - **Password**: `[PASSWORD]`

### 1.3 Run Migrations

You'll need to run your Laravel migrations. We'll do this after deploying the backend.

---

## 🎨 Step 2: Deploy Frontend (Vercel)

### 2.1 Prepare Frontend Code

1. **Update environment file** (already done):
   - `frontend/src/environments/environment.prod.ts` is ready

2. **Push to GitHub**:
   ```bash
   cd /home/ola/swimming-academy
   git init
   git add .
   git commit -m "Initial commit"
   git branch -M main
   git remote add origin https://github.com/YOUR_USERNAME/swimming-academy.git
   git push -u origin main
   ```

### 2.2 Deploy to Vercel

**Option A: Via GitHub (Recommended)**

1. Go to [vercel.com](https://vercel.com)
2. Click "Add New..." → "Project"
3. Import your GitHub repository
4. Configure:
   - **Framework Preset**: Angular
   - **Root Directory**: `frontend`
   - **Build Command**: `npm run build`
   - **Output Directory**: `dist/swimming-academy`
5. Click "Deploy"
6. **Wait for deployment** (takes 2-3 minutes)
7. Copy your deployment URL (e.g., `https://swimming-academy.vercel.app`)

**Option B: Via CLI**

```bash
cd frontend
npm i -g vercel
vercel login
vercel --prod
```

### 2.3 Set Environment Variables in Vercel

1. Go to your Vercel project → **Settings** → **Environment Variables**
2. Add:
   - **Key**: `API_URL`
   - **Value**: `https://your-backend.up.railway.app/api` (you'll get this after backend deployment)
3. **Important**: After adding, you need to rebuild the deployment

---

## ⚙️ Step 3: Deploy Backend (Railway)

### 3.1 Prepare Backend Code

1. **Create separate GitHub repo for backend** (or use monorepo):
   ```bash
   cd backend
   git init
   git add .
   git commit -m "Backend initial commit"
   git remote add origin https://github.com/YOUR_USERNAME/swimming-academy-backend.git
   git push -u origin main
   ```

### 3.2 Deploy to Railway

1. Go to [railway.app](https://railway.app)
2. Click "New Project"
3. Select "Deploy from GitHub repo"
4. Choose your backend repository
5. Railway will auto-detect Laravel

### 3.3 Configure Environment Variables

1. In Railway project, go to **Variables** tab
2. Add these variables:

```env
APP_NAME="Olympia Academy"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-project.up.railway.app

# Database (from Supabase)
DB_CONNECTION=pgsql
DB_HOST=your-host.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-supabase-password

# Frontend URL (from Vercel)
FRONTEND_URL=https://your-frontend.vercel.app

# Session
SESSION_DRIVER=database
SESSION_DOMAIN=.vercel.app

# Sanctum
SANCTUM_STATEFUL_DOMAINS=your-frontend.vercel.app
```

3. **Generate APP_KEY**:
   - In Railway, open **Deployments** → Click on your deployment
   - Open **Shell/Terminal**
   - Run: `php artisan key:generate`
   - Copy the key and add it to `APP_KEY` variable

### 3.4 Run Database Migrations

1. In Railway terminal, run:
   ```bash
   php artisan migrate --force
   ```

### 3.5 Get Backend URL

1. In Railway, go to **Settings** → **Networking**
2. Click "Generate Domain"
3. Copy the URL (e.g., `https://swimming-academy-production.up.railway.app`)

---

## 🔗 Step 4: Connect Everything

### 4.1 Update Frontend API URL

1. Go to Vercel → Your Project → **Settings** → **Environment Variables**
2. Update `API_URL` to your Railway backend URL:
   ```
   https://your-backend.up.railway.app/api
   ```
3. Go to **Deployments** → Click "..." on latest → **Redeploy**

### 4.2 Update Backend CORS

1. In Railway, update environment variable:
   ```
   FRONTEND_URL=https://your-frontend.vercel.app
   ```
2. Railway will auto-redeploy

### 4.3 Verify CORS Configuration

The `backend/config/cors.php` file is already configured to use `FRONTEND_URL` environment variable.

---

## ✅ Step 5: Final Checks

### 5.1 Test Your Deployment

1. **Frontend**: Visit `https://your-app.vercel.app`
2. **Backend API**: Visit `https://your-backend.up.railway.app/api`
3. **Test Login**: Try logging in from the frontend

### 5.2 Common Issues & Fixes

**Issue: CORS errors**
- ✅ Check `FRONTEND_URL` in Railway matches your Vercel URL exactly
- ✅ Check `SANCTUM_STATEFUL_DOMAINS` includes your Vercel domain

**Issue: Database connection failed**
- ✅ Verify Supabase credentials in Railway environment variables
- ✅ Check Supabase project is active (not paused)

**Issue: Frontend can't reach backend**
- ✅ Verify `API_URL` in Vercel environment variables
- ✅ Rebuild Vercel deployment after adding environment variables

**Issue: 500 errors on backend**
- ✅ Check Railway logs: **Deployments** → Click deployment → **View Logs**
- ✅ Verify `APP_KEY` is set
- ✅ Check database migrations ran successfully

---

## 📝 Quick Reference

### URLs to Save:
- **Frontend**: `https://your-app.vercel.app`
- **Backend**: `https://your-backend.up.railway.app`
- **Database**: Supabase Dashboard

### Important Files:
- `frontend/vercel.json` - Vercel configuration
- `backend/railway.json` - Railway configuration
- `backend/Procfile` - Railway start command
- `backend/config/cors.php` - CORS settings

---

## 🎉 You're Done!

Your application is now live and accessible to users worldwide!

**Next Steps:**
- Set up custom domains (optional, paid)
- Configure email service (for password resets)
- Set up monitoring and error tracking
- Configure backups for database

---

## 💰 Cost Breakdown

- **Vercel**: FREE (unlimited projects)
- **Railway**: FREE ($5 credit/month, usually enough)
- **Supabase**: FREE (500MB database, 2GB bandwidth)
- **Total**: **$0/month** 🎉

---

## 🆘 Need Help?

If you encounter issues:
1. Check Railway logs
2. Check Vercel build logs
3. Verify all environment variables are set
4. Ensure database migrations completed

Good luck with your deployment! 🚀

