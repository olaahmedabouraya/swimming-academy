# 🚀 Deploy Backend to Fly.io (100% Free, No Credit Card)

## ✅ Why Fly.io?

- ✅ **No credit card required**
- ✅ **Always-on** (no spin-down like Render)
- ✅ **3 free VMs** (shared-cpu)
- ✅ **3GB storage**
- ✅ **160GB transfer/month**

---

## 📋 Prerequisites

1. GitHub account
2. Fly.io account (sign up at fly.io - no credit card needed)

---

## 🛠️ Step 1: Install Fly CLI

```bash
curl -L https://fly.io/install.sh | sh
```

Or on Linux:
```bash
# Add to PATH (add to ~/.bashrc or ~/.zshrc)
export FLYCTL_INSTALL="/home/$USER/.fly"
export PATH="$FLYCTL_INSTALL/bin:$PATH"
```

---

## 🔐 Step 2: Sign Up / Login

```bash
fly auth signup
```

This will open your browser to create an account (no credit card needed).

---

## 📦 Step 3: Prepare Your Backend

Make sure your backend code is pushed to GitHub.

---

## 🚀 Step 4: Launch Your App

```bash
cd /home/ola/swimming-academy/backend
fly launch
```

**Follow the prompts:**
1. **App name**: `swimming-academy-api` (or choose your own)
2. **Region**: Choose closest to you
3. **PostgreSQL**: Say **No** (we're using Supabase)
4. **Redis**: Say **No** (optional)
5. **Deploy now**: Say **Yes**

---

## ⚙️ Step 5: Configure Environment Variables

After launch, set environment variables:

```bash
fly secrets set APP_NAME="Olympia Academy"
fly secrets set APP_ENV=production
fly secrets set APP_DEBUG=false
fly secrets set APP_TIMEZONE=UTC
fly secrets set APP_URL=https://swimming-academy-api.fly.dev
```

**Database (Supabase):**
```bash
fly secrets set DB_CONNECTION=pgsql
fly secrets set DB_HOST=db.thbbhqsqjygavliigkgn.supabase.co
fly secrets set DB_PORT=5432
fly secrets set DB_DATABASE=postgres
fly secrets set DB_USERNAME=postgres
fly secrets set DB_PASSWORD=your-supabase-password
```

**Frontend/CORS:**
```bash
fly secrets set FRONTEND_URL=https://your-frontend.vercel.app
fly secrets set SESSION_DRIVER=database
fly secrets set SESSION_DOMAIN=.vercel.app
fly secrets set SANCTUM_STATEFUL_DOMAINS=your-frontend.vercel.app
```

---

## 🔑 Step 6: Generate APP_KEY

```bash
fly ssh console
php artisan key:generate
```

Copy the key, then:
```bash
fly secrets set APP_KEY=base64:xxxxx...
```

---

## 🗄️ Step 7: Run Migrations

```bash
fly ssh console
php artisan migrate --force
```

---

## 🌐 Step 8: Get Your URL

Your app will be available at:
```
https://swimming-academy-api.fly.dev
```

(Replace `swimming-academy-api` with your app name)

---

## 📝 Alternative: Use fly.toml Configuration

You can also create a `fly.toml` file in your backend directory:

```toml
app = "swimming-academy-api"
primary_region = "iad"

[build]

[env]
  APP_ENV = "production"
  APP_DEBUG = "false"

[[services]]
  internal_port = 8000
  protocol = "tcp"

  [[services.ports]]
    port = 80
    handlers = ["http"]
    force_https = true

  [[services.ports]]
    port = 443
    handlers = ["tls", "http"]

  [services.concurrency]
    type = "connections"
    hard_limit = 25
    soft_limit = 20

  [[services.http_checks]]
    interval = "10s"
    timeout = "2s"
    grace_period = "5s"
    method = "GET"
    path = "/"
```

Then run: `fly launch` (it will use this config)

---

## 🔄 Updating Your App

```bash
# Make changes, then:
git add .
git commit -m "Update backend"
git push

# Deploy to Fly.io
fly deploy
```

---

## 🆘 Troubleshooting

**Can't connect to database?**
- Check Supabase credentials in `fly secrets list`
- Verify Supabase project is active

**500 errors?**
- Check `APP_KEY` is set: `fly secrets list`
- Check logs: `fly logs`

**View logs:**
```bash
fly logs
```

**SSH into container:**
```bash
fly ssh console
```

---

## ✅ Advantages of Fly.io

- ✅ No credit card required
- ✅ Always-on (no wake-up delay)
- ✅ More powerful than Render free tier
- ✅ Good CLI tools
- ✅ Easy scaling later

---

**That's it! Your backend is now live on Fly.io - completely free! 🎉**


