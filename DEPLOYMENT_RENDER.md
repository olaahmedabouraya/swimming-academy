# 🚀 Free Deployment with Render (100% Free)

This guide uses **Render** instead of Railway - it's completely free with no credit limits!

---

## 📋 Prerequisites

1. GitHub account
2. Render account (sign up at render.com) - **FREE**
3. Vercel account (for frontend) - **FREE**
4. Supabase account (for database) - **FREE**

---

## 🗄️ Step 1: Set Up Database (Supabase)

Same as before - see `DEPLOYMENT.md` Step 1.

---

## ⚙️ Step 2: Deploy Backend to Render (FREE)

### 2.1 Prepare Backend

1. **Push backend to GitHub** (if not already):
   ```bash
   cd backend
   git init
   git add .
   git commit -m "Backend ready for Render"
   git remote add origin https://github.com/YOUR_USERNAME/swimming-academy-backend.git
   git push -u origin main
   ```

### 2.2 Deploy to Render

1. Go to [render.com](https://render.com)
2. Sign up with GitHub (free)
3. Click **"New +"** → **"Web Service"**
4. Connect your GitHub repository (backend repo)
5. Configure:
   - **Name**: `swimming-academy-api`
   - **Environment**: **PHP**
   - **Region**: Choose closest to you
   - **Branch**: `main`
   - **Root Directory**: (leave empty if backend is root)
   - **Build Command**: `composer install --no-dev --optimize-autoloader && php artisan migrate --force`
   - **Start Command**: `php artisan serve --host=0.0.0.0 --port=$PORT`
6. Click **"Advanced"** → Add Environment Variables (include **APP_KEY** — see 2.3 below):

```env
APP_NAME=Olympia Academy
APP_ENV=production
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=https://swimming-academy-api.onrender.com
APP_KEY=base64:YOUR_KEY_HERE

# Database (from Supabase)
DB_CONNECTION=pgsql
DB_HOST=your-host.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-supabase-password

# Frontend URL (you'll get this after Vercel deployment)
FRONTEND_URL=https://your-frontend.vercel.app

# Session
SESSION_DRIVER=database
SESSION_DOMAIN=.vercel.app

# Sanctum
SANCTUM_STATEFUL_DOMAINS=your-frontend.vercel.app
```

7. Click **"Create Web Service"**
8. Wait 5-10 minutes for first deployment

### 2.3 Generate APP_KEY (Free tier: no Shell)

**Free tier does not include Shell.** Generate the key **locally** and add it to Environment:

1. On your machine, in the backend folder:
   ```bash
   cd backend
   php artisan key:generate --show
   ```
2. Copy the output (e.g. `base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx=`)
3. In Render dashboard → your service → **Environment** tab
4. Add (or edit):
   - Key: `APP_KEY`
   - Value: (paste the key you copied)
5. Save. The service will auto-redeploy.

**If you don’t have PHP locally**, you can generate a key with:
   ```bash
   openssl rand -base64 32
   ```
   Then set `APP_KEY=base64:PASTE_THE_OUTPUT_HERE` (include the `base64:` prefix).

### 2.4 Migrations (Free tier)

Migrations run automatically during **Build** (see Build Command in 2.2). You don’t need Shell.

If you need to run migrations later without redeploying, you’d need the paid Shell; on free tier, trigger a redeploy to run migrations again.

### 2.5 Get Backend URL

1. In Render dashboard, your service URL is shown at the top
2. Format: `https://swimming-academy-api.onrender.com`
3. **Save this URL!**

**Note:** Render services spin down after 15 minutes of inactivity. First request after spin-down takes ~30 seconds to wake up.

---

## 🎨 Step 3: Deploy Frontend (Vercel)

Same as before - see `DEPLOYMENT.md` Step 2.

**Important:** When setting `API_URL` in Vercel, use your Render URL:
```
https://swimming-academy-api.onrender.com/api
```

---

## 🔗 Step 4: Connect Everything

1. **Update Backend CORS**:
   - Render → Your Service → Environment
   - Update `FRONTEND_URL` with your Vercel URL
   - Service auto-redeploys

2. **Update Frontend API URL**:
   - Vercel → Environment Variables
   - Update `API_URL` with your Render URL
   - Rebuild deployment

---

## ✅ Advantages of Render

- ✅ **100% Free** - No credit limits
- ✅ **750 hours/month** - Enough for 24/7
- ✅ **Auto-deploy** from GitHub
- ✅ **Free SSL** certificate
- ✅ **Easy setup** - Similar to Railway

## ⚠️ Limitations

- ⚠️ **Spins down** after 15min inactivity (first request wakes it up)
- ⚠️ **Wake-up time** ~30 seconds after spin-down
- ⚠️ **Free tier** has resource limits (usually fine for small apps)

---

## 🎉 You're Done!

Your app is now live on **100% free hosting**!

**Total Cost: $0/month** 🎉

---

## 🆘 Troubleshooting

**Service won't start?**
- Check Render logs: Service → Logs tab
- Verify `APP_KEY` is set
- Check database credentials

**Slow first request?**
- Normal! Service is waking up from sleep
- Subsequent requests are fast

**CORS errors?**
- Verify `FRONTEND_URL` in Render matches Vercel URL exactly
- Check `SANCTUM_STATEFUL_DOMAINS` includes Vercel domain

---

**Need help?** Check Render logs and ensure all environment variables are set correctly.


