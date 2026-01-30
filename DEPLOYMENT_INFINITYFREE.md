# 🚀 Deploy to InfinityFree (Completely Free, No Card)

## ✅ Why InfinityFree?

- ✅ **Completely free**
- ✅ **No credit card required**
- ✅ **No verification**
- ✅ **PHP 8.x support**
- ✅ **MySQL database included**
- ✅ **Unlimited hosting**
- ⚠️ Shared hosting (basic, slower)
- ⚠️ Some limitations

---

## 📋 Prerequisites

1. InfinityFree account (sign up at infinityfree.net)
2. FTP client (FileZilla, WinSCP) or use File Manager
3. Your Laravel backend code

---

## 🚀 Step 1: Sign Up

1. Go to [infinityfree.net](https://infinityfree.net)
2. Click **"Sign Up"** or **"Create Account"**
3. Fill in:
   - Email
   - Password
   - Username
4. **No credit card needed!**
5. Verify your email

---

## 📦 Step 2: Add Website

1. After signup, log in to **InfinityFree Control Panel**
2. Click **"Add Website"** or **"Create Website"**
3. Choose:
   - **Subdomain**: `swimming-academy.infinityfreeapp.com` (or your choice)
   - **PHP Version**: 8.2 or latest
4. Click **"Create"**
5. Wait for setup (1-2 minutes)

---

## 🔧 Step 3: Access File Manager

1. In Control Panel, find your website
2. Click **"Manage"** or **"File Manager"**
3. You'll see `htdocs` folder (this is your web root)

---

## 📤 Step 4: Upload Laravel Files

### Option A: Using File Manager

1. Go to **File Manager** in Control Panel
2. Navigate to `htdocs` folder
3. Upload your Laravel files:
   - Upload all files from your `backend` folder
   - **Important:** Make sure `.htaccess` is uploaded
   - Upload `vendor` folder (if Composer not available)

### Option B: Using FTP

1. Get FTP credentials from Control Panel:
   - Host: `ftpupload.net` or similar (check your panel)
   - Username: (from Control Panel)
   - Password: (from Control Panel)
   - Port: 21
2. Connect with FileZilla or WinSCP
3. Navigate to `htdocs` folder
4. Upload all Laravel files

---

## ⚙️ Step 5: Configure for Shared Hosting

Laravel needs adjustments for shared hosting:

### 5.1 File Structure

Your `htdocs` should have:
```
htdocs/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
│   ├── index.php
│   ├── .htaccess
│   └── ...
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
├── .htaccess (root level)
└── composer.json
```

### 5.2 Public .htaccess

**Important:** The `public/.htaccess` file is required for Laravel to work properly. It's a hidden file (starts with a dot), so make sure it's uploaded.

If it doesn't exist, create `public/.htaccess` with this content:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

**Note:** This file should already exist in your Laravel project at `backend/public/.htaccess`. Make sure to upload it when deploying.

### 5.3 Root .htaccess

Create `.htaccess` in `htdocs` root:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### 5.4 Update index.php (if needed)

Edit `public/index.php` - paths should be:
```php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
```

---

## 🗄️ Step 6: Set Up Database

1. In Control Panel, go to **MySQL Databases**
2. Click **"Create Database"**
3. Fill in:
   - Database name: `swimming_academy`
   - Database user: (create new user)
   - Password: (set password)
4. **Save credentials!**
5. Note the host (usually `sqlXXX.infinityfree.com`)

**OR use Supabase (PostgreSQL):**
- Keep using Supabase (external database)
- Better option for Laravel

---

## 🔐 Step 7: Configure Environment

1. In `htdocs`, create/update `.env` file
2. Add:

```env
APP_NAME=Olympia Academy
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:xxxxx...
APP_URL=https://swimming-academy.infinityfreeapp.com

# Option 1: Use InfinityFree MySQL
DB_CONNECTION=mysql
DB_HOST=sqlXXX.infinityfree.com
DB_DATABASE=swimming_academy
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# Option 2: Use Supabase PostgreSQL (Recommended)
DB_CONNECTION=pgsql
DB_HOST=db.thbbhqsqjygavliigkgn.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-supabase-password

FRONTEND_URL=https://your-frontend.vercel.app
SESSION_DRIVER=database
SESSION_DOMAIN=.vercel.app
SANCTUM_STATEFUL_DOMAINS=your-frontend.vercel.app
```

---

## 🔑 Step 8: Generate APP_KEY

### Option A: Via Terminal (if available)

1. Access terminal in Control Panel (if available)
2. Navigate to your Laravel root
3. Run: `php artisan key:generate`

### Option B: Manually Generate

Run locally:
```bash
php -r "echo 'base64:' . base64_encode(random_bytes(32));"
```

Copy the output to `.env` as `APP_KEY`

---

## 🗄️ Step 9: Run Migrations

### Option A: Via Terminal

1. Access terminal
2. Run: `php artisan migrate --force`

### Option B: Via Database

1. Export your local database
2. Import via phpMyAdmin in Control Panel

---

## ⚠️ Important Notes

### Shared Hosting Limitations:

- ⚠️ **No SSH access** (usually)
- ⚠️ **No Composer** (upload `vendor` folder)
- ⚠️ **File size limits** (usually 2-10MB)
- ⚠️ **Execution time limits** (30-60 seconds)
- ⚠️ **Memory limits** (128-256MB)

### Workarounds:

1. **Upload vendor folder:**
   ```bash
   # Locally
   composer install --no-dev --optimize-autoloader
   # Then upload vendor/ folder
   ```

2. **Pre-compile assets:**
   ```bash
   # Locally
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Use external database:**
   - Use Supabase (PostgreSQL) instead of local MySQL
   - Better performance and reliability

---

## 🆘 Troubleshooting

**500 errors?**
- Check file permissions (755 for folders, 644 for files)
- Check `.htaccess` is correct
- Check `APP_KEY` is set
- Check error logs in Control Panel

**Database connection failed?**
- Verify credentials
- Check if external connections allowed (for Supabase)
- Test connection via phpMyAdmin

**Composer issues?**
- Upload `vendor` folder from local
- Don't rely on Composer on shared hosting

**Slow performance?**
- Normal for shared hosting
- Use Supabase for database (faster)
- Enable caching

---

## ✅ Advantages

- ✅ Completely free
- ✅ No credit card
- ✅ No verification
- ✅ PHP 8.x support
- ✅ MySQL included
- ✅ Unlimited hosting

## ⚠️ Disadvantages

- ⚠️ Shared hosting (slower)
- ⚠️ Limited control
- ⚠️ No SSH
- ⚠️ Resource limits
- ⚠️ May have ads

---

## 🎯 Final Recommendation

**For truly free (no card):**
- ✅ **InfinityFree** - Best option
- Use Supabase for database (better than local MySQL)
- Upload `vendor` folder (don't use Composer on host)

**If you can add card (just verification):**
- Railway/Render - Much better experience
- They won't charge if you stay in free tier

---

**This is a working solution for truly free hosting without a credit card!**


