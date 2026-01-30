# 🚀 Deploy to 000webhost (Completely Free, No Card)

## ⛔ 000webhost has closed (2024)

**000webhost was shut down by Hostinger in 2024.** New signups stopped in July 2024; the platform fully closed in October 2024. Hostinger does not offer free web hosting, so this guide is kept for reference only. For free backend hosting without a card, see **[BACKEND_NO_CARD_OPTIONS.md](BACKEND_NO_CARD_OPTIONS.md)** (e.g. self-host + ngrok, or use a virtual card for Render/Koyeb/Fly.io).

---

## ✅ Why 000webhost? (historical)

- ✅ **Completely free**
- ✅ **No credit card required**
- ✅ **No verification**
- ✅ **PHP 8.x support**
- ✅ **MySQL database included**
- ⚠️ Basic hosting (shared hosting limitations)
- ⚠️ Slower than paid options

---

## 📋 Prerequisites

1. 000webhost account (sign up at 000webhost.com)
2. FTP client (FileZilla, WinSCP, or use cPanel File Manager)
3. Your Laravel backend code

---

## 🚀 Step 1: Sign Up

1. Go to [000webhost.com](https://000webhost.com)
2. Click **"Get Started"** or **"Sign Up"**
3. Fill in:
   - Email
   - Password
   - Website name
4. **No credit card needed!**
5. Verify your email

---

## 📦 Step 2: Create Website

1. After signup, create a new website
2. Choose a subdomain (e.g., `swimming-academy.000webhostapp.com`)
3. Wait for setup (2-3 minutes)

---

## 🔧 Step 3: Access cPanel

1. Go to your 000webhost dashboard
2. Click on your website
3. Access **cPanel** or **File Manager**

---

## 📤 Step 4: Upload Laravel Files

### Option A: Using cPanel File Manager

1. Go to **File Manager** in cPanel
2. Navigate to `public_html` folder
3. Upload your Laravel files:
   - Upload all files from your `backend` folder
   - **Important:** Make sure `.htaccess` is uploaded

### Option B: Using FTP

1. Get FTP credentials from cPanel:
   - Host: `files.000webhost.com`
   - Username: (from cPanel)
   - Password: (from cPanel)
2. Connect with FileZilla or WinSCP
3. Upload all Laravel files to `public_html`

---

## ⚙️ Step 5: Configure for Shared Hosting

Laravel needs some adjustments for shared hosting:

### 5.1 Move Public Files

1. In `public_html`, you should have:
   - All Laravel files
   - `public` folder contents should be in root

### 5.2 Update .htaccess

Create/update `.htaccess` in `public_html`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### 5.3 Update index.php Paths

Edit `public/index.php` to point to correct paths (if needed).

---

## 🗄️ Step 6: Set Up Database

1. In cPanel, go to **MySQL Databases**
2. Create new database: `swimming_academy`
3. Create database user
4. Add user to database
5. Note the credentials:
   - Host: Usually `localhost`
   - Database name
   - Username
   - Password

**Note:** 000webhost uses MySQL, not PostgreSQL. You'll need to:
- Either use Supabase (external PostgreSQL)
- Or convert your app to use MySQL

---

## 🔐 Step 7: Configure Environment

1. In `public_html`, create `.env` file
2. Add:

```env
APP_NAME=Olympia Academy
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:xxxxx...
APP_URL=https://your-site.000webhostapp.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=swimming_academy
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# Or use Supabase PostgreSQL:
DB_CONNECTION=pgsql
DB_HOST=db.thbbhqsqjygavliigkgn.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-supabase-password
```

---

## 🔑 Step 8: Generate APP_KEY

1. Access your site via SSH (if available) or use cPanel Terminal
2. Navigate to your Laravel root
3. Run: `php artisan key:generate`
4. Copy the key to `.env`

**Or manually generate:**
```bash
php -r "echo 'base64:' . base64_encode(random_bytes(32));"
```

---

## 🗄️ Step 9: Run Migrations

1. Access terminal/SSH
2. Run: `php artisan migrate --force`

---

## ⚠️ Limitations of Shared Hosting

- ⚠️ No SSH access (usually)
- ⚠️ Limited PHP extensions
- ⚠️ No Composer (may need to upload `vendor` folder)
- ⚠️ File size limits
- ⚠️ Execution time limits

**Workaround:**
- Upload `vendor` folder from local
- Pre-compile assets
- Use external database (Supabase)

---

## 🆘 Troubleshooting

**500 errors?**
- Check file permissions (755 for folders, 644 for files)
- Check `.htaccess` is correct
- Check `APP_KEY` is set

**Database connection failed?**
- Verify credentials
- Check if external connections allowed (for Supabase)

**Composer issues?**
- Upload `vendor` folder from local instead

---

## ✅ Advantages

- ✅ Completely free
- ✅ No credit card
- ✅ No verification
- ✅ PHP support
- ✅ Database included

## ⚠️ Disadvantages

- ⚠️ Shared hosting limitations
- ⚠️ Slower than VPS
- ⚠️ Less control
- ⚠️ May have ads

---

**This is a basic but working solution for truly free hosting!**


