# 🚀 Complete Step-by-Step Deployment Guide

**100% FREE Hosting** - Your app will be live and accessible to users worldwide!

---

## 📋 What You'll Deploy

- **Frontend (Angular)**: Vercel - FREE
- **Backend (Laravel)**: Koyeb - FREE (no verification) OR Fly.io/Render (may require verification)
- **Database (PostgreSQL)**: Supabase - FREE (500MB)

**Total Cost: $0/month** 🎉

---

## ⏱️ Time Required: ~20 minutes

---

## 📝 Step 1: Prepare Your Code (5 minutes)

### 1.1 Push to GitHub

```bash
cd /home/ola/swimming-academy

# Initialize git if not already done
git init
git add .
git commit -m "Ready for deployment"

# Create repositories on GitHub first, then:
git remote add origin-frontend https://github.com/YOUR_USERNAME/swimming-academy-frontend.git
git remote add origin-backend https://github.com/YOUR_USERNAME/swimming-academy-backend.git

# Or use a monorepo (single repository):
git remote add origin https://github.com/YOUR_USERNAME/swimming-academy.git
git push -u origin main
```

**Note:** You can use one repository or separate repos for frontend/backend.

---

## 🗄️ Step 2: Set Up Database - Supabase (5 minutes)

### 2.1 Create Supabase Account

1. Go to **[supabase.com](https://supabase.com)**
2. Click **"Start your project"** or **"Sign up"**
3. Sign up with GitHub (easiest)

### 2.2 Create New Project

1. Click **"New Project"**
2. Fill in:
   - **Name**: `swimming-academy`
   - **Database Password**: Create a strong password (SAVE THIS!)
   - **Region**: Choose closest to you
3. Click **"Create new project"**
4. Wait 2-3 minutes for setup

### 2.3 Get Database Connection Details

1. Go to **Settings** (gear icon) → **Database**
2. Scroll to **"Connection string"** section
3. Click **"URI"** tab
4. You'll see: `postgresql://postgres:[YOUR-PASSWORD]@[HOST].supabase.co:5432/postgres`
5. **Save these details:**
   - **Host**: `xxxxx.supabase.co` (from the connection string)
   - **Port**: `5432`
   - **Database**: `postgres`
   - **Username**: `postgres`
   - **Password**: (the one you created)

**Keep this tab open** - you'll need these credentials soon!

---

## ⚙️ Step 3: Deploy Backend - Choose Your Option

**All major platforms now require credit card verification. Here are your options:**

### Option A: Use InfinityFree (Truly Free, No Card)
- ✅ Completely free, no card needed
- ✅ PHP 8.x support
- ⚠️ Basic shared hosting (slower, limitations)
- See `DEPLOYMENT_INFINITYFREE.md` for instructions

### Option B: Add Card for Verification (Recommended)
- Railway/Render/Koyeb only verify, don't charge
- Much better experience
- $5 free credit/month (Railway) or 750 hours/month (Render)
- See original instructions below

### Option C: Self-Host
- Use your own server/VPS
- Complete control
- Free if you have hardware

**Recommendation:** If possible, add a card just for verification (they won't charge). Otherwise, see BACKEND_NO_CARD_OPTIONS.md (000webhost closed in 2024).

### 3.1 Create Render Account

1. Go to **[render.com](https://render.com)**
2. Click **"Get Started for Free"**
3. Sign up with **GitHub** (recommended)

### 3.2 Create Web Service

1. Click **"New +"** button (top right)
2. Select **"Web Service"**
3. Connect your GitHub account if prompted
4. Select your repository:
   - If using monorepo: Select the repo, set **Root Directory** to `backend`
   - If using separate repo: Select your backend repository

### 3.3 Configure Service

Fill in the form:

- **Name**: `swimming-academy-api`
- **Environment**: Select **"PHP"**
- **Region**: Choose closest to you
- **Branch**: `main` (or your default branch)
- **Root Directory**: (leave empty if backend is root, or `backend` if monorepo)
- **Build Command**: 
  ```
  composer install --no-dev --optimize-autoloader
  ```
- **Start Command**: 
  ```
  php artisan serve --host=0.0.0.0 --port=$PORT
  ```

### 3.4 Add Environment Variables

Scroll down to **"Environment Variables"** section and click **"Add Environment Variable"** for each:

```env
APP_NAME=Olympia Academy
APP_ENV=production
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=https://swimming-academy-api.onrender.com
```

(You'll update APP_URL after deployment)

```env
DB_CONNECTION=pgsql
DB_HOST=xxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-supabase-password-here
```

(Use the credentials from Step 2.3)

```env
FRONTEND_URL=https://your-frontend.vercel.app
```

(You'll update this after frontend deployment)

```env
SESSION_DRIVER=database
SESSION_DOMAIN=.vercel.app
SANCTUM_STATEFUL_DOMAINS=your-frontend.vercel.app
```

(Update after frontend deployment)

### 3.5 Deploy

1. Scroll down and click **"Create Web Service"**
2. Wait 5-10 minutes for first deployment
3. Watch the logs - you'll see build progress

### 3.6 Get Your Backend URL

1. Once deployed, you'll see your service URL at the top
2. Format: `https://swimming-academy-api.onrender.com`
3. **Copy this URL!** You'll need it for the frontend

### 3.7 Generate APP_KEY

1. In Render dashboard, click on your service
2. Go to **"Shell"** tab (in the left sidebar)
3. Run this command:
   ```bash
   php artisan key:generate
   ```
4. You'll see output like: `Application key set successfully [base64:xxxxx...]`
5. Copy the key (the part after `base64:`)
6. Go to **"Environment"** tab
7. Click **"Add Environment Variable"**:
   - Key: `APP_KEY`
   - Value: `base64:xxxxx...` (paste the full key)
8. Service will auto-redeploy

### 3.8 Run Database Migrations

1. Go back to **"Shell"** tab
2. Run:
   ```bash
   php artisan migrate --force
   ```
3. You should see migrations running successfully
4. If you have seeders, run: `php artisan db:seed --force`

### 3.9 Update APP_URL

1. Go to **"Environment"** tab
2. Find `APP_URL` variable
3. Update it with your actual Render URL: `https://swimming-academy-api.onrender.com`
4. Service will auto-redeploy

**✅ Backend is now live!** Save your backend URL.

---

## 🎨 Step 4: Deploy Frontend to Vercel (5 minutes)

### 4.1 Create Vercel Account

1. Go to **[vercel.com](https://vercel.com)**
2. Click **"Sign Up"**
3. Sign up with **GitHub** (recommended)

### 4.2 Import Project

1. Click **"Add New..."** → **"Project"**
2. Import your GitHub repository
3. If using monorepo, you'll configure the root directory next

### 4.3 Configure Project

In the project configuration:

- **Framework Preset**: Angular (auto-detected)
- **Root Directory**: 
  - If monorepo: Click **"Edit"** → Set to `frontend`
  - If separate repo: Leave as is
- **Build Command**: `npm run build`
- **Output Directory**: `dist/swimming-academy`
- **Install Command**: `npm install` (default)

### 4.4 Add Environment Variable

1. Scroll down to **"Environment Variables"**
2. Click **"Add"**
3. Add:
   - **Key**: `API_URL`
   - **Value**: `https://swimming-academy-api.onrender.com/api`
   (Use your actual Render backend URL from Step 3.6)

### 4.5 Deploy

1. Click **"Deploy"**
2. Wait 2-3 minutes for build and deployment
3. Watch the build logs

### 4.6 Get Your Frontend URL

1. Once deployed, you'll see: **"Congratulations! Your project has been deployed."**
2. Your URL will be: `https://swimming-academy-xxxxx.vercel.app`
3. **Copy this URL!**

### 4.7 Update API URL (if needed)

1. Go to **Settings** → **Environment Variables**
2. Update `API_URL` if you need to change it
3. Go to **Deployments** tab
4. Click **"..."** on latest deployment → **"Redeploy"**

**✅ Frontend is now live!**

---

## 🔗 Step 5: Connect Everything (3 minutes)

### 5.1 Update Backend CORS

1. Go back to **Render** dashboard
2. Your service → **"Environment"** tab
3. Find `FRONTEND_URL` variable
4. Update value to your Vercel URL: `https://swimming-academy-xxxxx.vercel.app`
5. Find `SANCTUM_STATEFUL_DOMAINS` variable
6. Update value to your Vercel domain: `swimming-academy-xxxxx.vercel.app`
7. Render will auto-redeploy

### 5.2 Update Frontend API URL

1. Go to **Vercel** dashboard
2. Your project → **Settings** → **Environment Variables**
3. Update `API_URL` to: `https://swimming-academy-api.onrender.com/api`
4. Go to **Deployments** → Click **"..."** → **"Redeploy"**

### 5.3 Test Your Application

1. Visit your Vercel URL: `https://swimming-academy-xxxxx.vercel.app`
2. Try logging in
3. Test the features

---

## ✅ Step 6: Final Verification (2 minutes)

### 6.1 Check Backend is Running

1. Visit: `https://swimming-academy-api.onrender.com/api`
2. You should see a response (might be an error, but that's OK - it means backend is up)

### 6.2 Check Frontend

1. Visit your Vercel URL
2. Open browser console (F12)
3. Check for any errors
4. Try logging in

### 6.3 Common Issues & Fixes

**❌ CORS Error:**
- ✅ Check `FRONTEND_URL` in Render matches Vercel URL exactly
- ✅ Check `SANCTUM_STATEFUL_DOMAINS` includes Vercel domain
- ✅ Rebuild both services

**❌ Can't connect to API:**
- ✅ Verify `API_URL` in Vercel is correct
- ✅ Check backend is running (visit Render URL)
- ✅ Rebuild frontend after updating API_URL

**❌ Database connection failed:**
- ✅ Verify Supabase credentials in Render
- ✅ Check Supabase project is active (not paused)
- ✅ Test connection in Render Shell: `php artisan tinker` → Try a query

**❌ 500 Error on backend:**
- ✅ Check Render logs: Service → Logs tab
- ✅ Verify `APP_KEY` is set
- ✅ Check migrations ran successfully

**❌ Slow first request:**
- ✅ Normal! Render spins down after 15min inactivity
- ✅ First request takes ~30 seconds to wake up
- ✅ Subsequent requests are fast

---

## 🎉 Success Checklist

- [ ] Database created on Supabase
- [ ] Backend deployed to Render
- [ ] APP_KEY generated and set
- [ ] Database migrations run successfully
- [ ] Frontend deployed to Vercel
- [ ] API_URL set in Vercel
- [ ] CORS configured in Render
- [ ] App is accessible and working
- [ ] Can log in successfully

---

## 📍 Your Live URLs

Save these for future reference:

- **Frontend**: `https://swimming-academy-xxxxx.vercel.app`
- **Backend API**: `https://swimming-academy-api.onrender.com/api`
- **Database**: Supabase Dashboard

---

## 🔄 Updating Your App

### To update frontend:
1. Push changes to GitHub
2. Vercel auto-deploys (or manually redeploy)

### To update backend:
1. Push changes to GitHub
2. Render auto-deploys (or manually redeploy)

### To run new migrations:
1. Render → Shell tab
2. Run: `php artisan migrate --force`

---

## 💰 Cost Summary

- **Vercel**: FREE (unlimited projects)
- **Render**: FREE (750 hours/month - enough for 24/7)
- **Supabase**: FREE (500MB database, 2GB bandwidth)
- **Total**: **$0/month** 🎉

---

## 🆘 Need Help?

1. **Render Logs**: Service → Logs tab
2. **Vercel Logs**: Deployment → View Function Logs
3. **Supabase Logs**: Project → Logs
4. **Browser Console**: F12 → Console tab

---

## 🎯 Quick Reference Commands

**Render Shell:**
```bash
php artisan key:generate          # Generate app key
php artisan migrate --force       # Run migrations
php artisan db:seed --force       # Run seeders (if any)
php artisan config:clear          # Clear config cache
php artisan cache:clear           # Clear cache
```

**Local Testing:**
```bash
# Frontend
cd frontend
npm install
npm start

# Backend
cd backend
composer install
php artisan serve
```

---

**That's it! Your app is now live and accessible to users worldwide! 🚀**

Follow these steps in order, and you'll have your app deployed in about 20 minutes.

