# 🚀 Deploy Backend to Koyeb (100% Free, No Verification)

## ✅ Why Koyeb?

- ✅ **No credit card required**
- ✅ **No verification** (just GitHub signup)
- ✅ **2 free services**
- ✅ **512MB RAM per service**
- ✅ **Auto-deploy from GitHub**
- ✅ **Easy setup**

---

## 📋 Prerequisites

1. GitHub account
2. Backend code pushed to GitHub

---

## 🚀 Step 1: Sign Up

1. Go to [koyeb.com](https://koyeb.com)
2. Click **"Get Started"** or **"Sign Up"**
3. Sign up with **GitHub** (easiest, no verification needed)
4. Authorize Koyeb to access your GitHub

---

## 📦 Step 2: Create App

1. In Koyeb dashboard, click **"Create App"**
2. Select **"GitHub"** as source
3. Choose your backend repository
   - If monorepo: You'll set root directory next
   - If separate repo: Select your backend repo

---

## ⚙️ Step 3: Configure App

### Basic Settings:

- **Name**: `swimming-academy-api`
- **Region**: Choose closest to you
- **Instance Type**: **Starter** (Free tier)

### Build Settings:

- **Build Command**: 
  ```
  composer install --no-dev --optimize-autoloader
  ```

- **Run Command**: 
  ```
  php artisan serve --host=0.0.0.0 --port=$PORT
  ```

### Root Directory (if monorepo):

- If your backend is in a subdirectory, set: `backend`

---

## 🔐 Step 4: Add Environment Variables

Click **"Environment Variables"** and add:

```env
APP_NAME=Olympia Academy
APP_ENV=production
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=https://swimming-academy-api-[random].koyeb.app
```

(Update APP_URL after deployment)

```env
DB_CONNECTION=pgsql
DB_HOST=db.thbbhqsqjygavliigkgn.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-supabase-password
```

```env
FRONTEND_URL=https://your-frontend.vercel.app
SESSION_DRIVER=database
SESSION_DOMAIN=.vercel.app
SANCTUM_STATEFUL_DOMAINS=your-frontend.vercel.app
```

---

## 🚀 Step 5: Deploy

1. Click **"Deploy"** or **"Create App"**
2. Wait 3-5 minutes for build and deployment
3. Watch the build logs

---

## 🔑 Step 6: Generate APP_KEY

1. Once deployed, go to your app dashboard
2. Click **"Shell"** or **"Console"** tab
3. Run:
   ```bash
   php artisan key:generate
   ```
4. Copy the generated key
5. Go to **"Environment Variables"**
6. Add:
   - Key: `APP_KEY`
   - Value: `base64:xxxxx...` (paste the full key)

---

## 🗄️ Step 7: Run Migrations

1. Go to **"Shell"** tab
2. Run:
   ```bash
   php artisan migrate --force
   ```

---

## 🌐 Step 8: Get Your URL

1. In your app dashboard, you'll see your URL
2. Format: `https://swimming-academy-api-[random].koyeb.app`
3. **Save this URL!**

---

## 🔄 Updating Your App

1. Push changes to GitHub
2. Koyeb will **auto-deploy** (or manually trigger deploy)

---

## 🆘 Troubleshooting

**Build fails?**
- Check build logs in Koyeb dashboard
- Verify `composer.json` is correct
- Check PHP version compatibility

**Can't connect to database?**
- Verify Supabase credentials
- Check environment variables are set

**500 errors?**
- Check `APP_KEY` is set
- Check logs in Koyeb dashboard
- Verify migrations ran

**View logs:**
- Go to app dashboard → **"Logs"** tab

---

## ✅ Advantages of Koyeb

- ✅ No credit card required
- ✅ No verification needed
- ✅ Auto-deploy from GitHub
- ✅ Easy to use
- ✅ Good for Laravel

---

## ⚠️ Limitations

- ⚠️ 512MB RAM (usually enough for small apps)
- ⚠️ 2 free services limit
- ⚠️ May spin down after inactivity (like Render)

---

**That's it! Your backend is now live on Koyeb - completely free, no verification! 🎉**


