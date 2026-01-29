# 🚀 Quick Start Deployment Guide

## Step-by-Step in 15 Minutes

### 1️⃣ Set Up Database (5 min)

1. Go to [supabase.com](https://supabase.com) → Sign up
2. **New Project** → Name: `swimming-academy`
3. Save your **Database Password** (you'll need it!)
4. Wait for project to be ready
5. Go to **Settings** → **Database** → Copy connection details:
   - Host: `xxxxx.supabase.co`
   - Port: `5432`
   - Database: `postgres`
   - Username: `postgres`
   - Password: (the one you saved)

---

### 2️⃣ Deploy Backend to Render (5 min) - 100% FREE!

1. Go to [render.com](https://render.com) → Sign up with GitHub (FREE)
2. **New +** → **Web Service**
3. Connect your **backend** GitHub repository
4. Configure:
   - **Environment**: PHP
   - **Build Command**: `composer install --no-dev --optimize-autoloader`
   - **Start Command**: `php artisan serve --host=0.0.0.0 --port=$PORT`

5. **Add Environment Variables** (in Render → Environment):
   ```
   APP_NAME=Olympia Academy
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-project.up.railway.app
   
   DB_CONNECTION=pgsql
   DB_HOST=xxxxx.supabase.co
   DB_PORT=5432
   DB_DATABASE=postgres
   DB_USERNAME=postgres
   DB_PASSWORD=your-supabase-password
   
   FRONTEND_URL=https://your-frontend.vercel.app
   SANCTUM_STATEFUL_DOMAINS=your-frontend.vercel.app
   SESSION_DOMAIN=.vercel.app
   ```

6. **Generate APP_KEY**:
   - Render → Your service → **Shell** tab
   - Run: `php artisan key:generate`
   - Copy the key → Add to `APP_KEY` environment variable

7. **Run Migrations**:
   - In Render Shell: `php artisan migrate --force`

8. **Get Backend URL**:
   - Render dashboard shows your URL at the top
   - Format: `https://swimming-academy-api.onrender.com`
   - **Note:** Service spins down after 15min inactivity (first request wakes it up)

---

### 3️⃣ Deploy Frontend to Vercel (5 min)

1. Go to [vercel.com](https://vercel.com) → Sign up with GitHub
2. **Add New Project** → Import GitHub repo
3. **Configure**:
   - Root Directory: `frontend`
   - Framework: Angular
   - Build Command: `npm run build`
   - Output Directory: `dist/swimming-academy`

4. **Add Environment Variable**:
   - Key: `API_URL`
   - Value: `https://your-backend.up.railway.app/api` (from step 2)

5. **Deploy** → Wait 2-3 minutes
6. **Copy Frontend URL**: `https://xxxxx.vercel.app`

---

### 4️⃣ Connect Everything

1. **Update Backend CORS**:
   - Render → Environment → Update `FRONTEND_URL` with your Vercel URL
   - Render auto-redeploys

2. **Update Frontend API URL**:
   - Vercel → Environment Variables → Update `API_URL` with your Render URL
   - Format: `https://your-service.onrender.com/api`
   - Vercel → Deployments → Redeploy

---

### 5️⃣ Test It! 🎉

1. Visit your Vercel URL
2. Try logging in
3. If errors, check:
   - Railway logs (Deployments → View Logs)
   - Vercel build logs
   - Browser console for CORS errors

---

## ✅ Checklist

- [ ] Supabase database created
- [ ] Backend deployed to Railway
- [ ] Database migrations run
- [ ] Frontend deployed to Vercel
- [ ] Environment variables set correctly
- [ ] CORS configured
- [ ] App is accessible and working

---

## 🆘 Troubleshooting

**CORS Error?**
- Check `FRONTEND_URL` in Railway matches Vercel URL exactly
- Check `SANCTUM_STATEFUL_DOMAINS` includes Vercel domain

**Can't Connect to API?**
- Verify `API_URL` in Vercel matches Railway URL
- Rebuild Vercel after adding environment variable

**Database Error?**
- Check Supabase credentials in Railway
- Verify migrations ran successfully

**500 Error?**
- Check `APP_KEY` is set in Railway
- Check Railway logs for specific errors

---

## 🎯 Your URLs

Save these:
- **Frontend**: `https://xxxxx.vercel.app`
- **Backend**: `https://xxxxx.onrender.com` (Render)
- **Database**: Supabase Dashboard

## 💰 Cost: $0/month (100% Free!)

- ✅ Vercel: Free
- ✅ Render: Free (750 hours/month)
- ✅ Supabase: Free (500MB database)

---

**That's it! Your app is live! 🚀**

