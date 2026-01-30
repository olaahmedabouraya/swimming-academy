# 📤 Upload Checklist for InfinityFree

## ✅ What to Upload

Upload **ALL files and folders** from your `backend` directory, including:

### Required Folders:
- ✅ `app/` - Application code
- ✅ `bootstrap/` - Bootstrap files
- ✅ `config/` - Configuration files
- ✅ `database/` - Migrations, seeders
- ✅ `public/` - Public files (important!)
- ✅ `resources/` - Views, assets
- ✅ `routes/` - Route definitions
- ✅ `storage/` - Storage directory (needs write permissions)
- ✅ `vendor/` - Composer dependencies (CRITICAL - upload this!)

### Required Files:
- ✅ `.env` - Environment file (create/update on server)
- ✅ `.htaccess` - Apache configuration (in root AND public/)
- ✅ `artisan` - Laravel command line tool
- ✅ `composer.json` - Dependencies list
- ✅ `composer.lock` - Lock file
- ✅ All other files in backend root

### ⚠️ What NOT to Upload:
- ❌ `.git/` - Git folder (not needed)
- ❌ `node_modules/` - If you have any (not needed for Laravel)
- ❌ `.env.example` - Just example, not needed
- ❌ `tests/` - Optional (can skip to save space)

---

## 📁 Folder Structure on Server

Your `htdocs` (web root) should look like this:

```
htdocs/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
│   ├── index.php
│   ├── .htaccess
│   └── (other public files)
├── resources/
├── routes/
├── storage/
│   ├── app/
│   ├── framework/
│   ├── logs/
│   └── (needs 755 permissions)
├── vendor/          ← IMPORTANT: Upload this!
├── .env
├── .htaccess        ← Root level .htaccess
├── artisan
├── composer.json
└── composer.lock
```

---

## 🔑 Important: Upload `vendor/` Folder

**CRITICAL:** You MUST upload the `vendor/` folder because:
- Shared hosting usually doesn't have Composer
- Laravel needs `vendor/` to run
- Without it, you'll get errors

**How to prepare vendor folder:**
```bash
# On your local machine, in backend directory:
cd /home/ola/swimming-academy/backend
composer install --no-dev --optimize-autoloader
# This creates/updates vendor/ folder
# Then upload the entire vendor/ folder
```

---

## 📤 Upload Methods

### Method 1: File Manager (Easiest)

1. Go to InfinityFree Control Panel
2. Click "File Manager"
3. Navigate to `htdocs` folder
4. Upload files:
   - Upload folders one by one, OR
   - Create a ZIP file locally, upload, then extract on server

### Method 2: FTP (Recommended for large uploads)

1. Get FTP credentials from Control Panel
2. Use FileZilla or WinSCP
3. Connect to server
4. Navigate to `htdocs` folder
5. Upload all files and folders

### Method 3: ZIP Upload (Fastest)

1. On your local machine:
   ```bash
   cd /home/ola/swimming-academy/backend
   zip -r backend.zip . -x "*.git*" -x "node_modules/*" -x "tests/*"
   ```
2. Upload `backend.zip` to `htdocs`
3. Extract on server using File Manager

---

## ⚙️ After Upload: Set Permissions

Set these permissions:
- **Folders**: `755`
- **Files**: `644`
- **storage/**: `755` (needs write access)
- **bootstrap/cache/**: `755` (needs write access)

---

## ✅ Quick Checklist

Before uploading, make sure:
- [ ] `vendor/` folder exists (run `composer install` locally)
- [ ] `.env` file is ready (or create on server)
- [ ] `.htaccess` files are present (root and public/)
- [ ] All folders are included
- [ ] `storage/` folder has subfolders (app/, framework/, logs/)

---

**Upload everything including all folders! The `vendor/` folder is especially important.**


