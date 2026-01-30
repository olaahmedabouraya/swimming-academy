# 📤 How to Upload Files Using FTP (FileZilla/WinSCP)

## 🔧 Step 1: Get FTP Credentials from InfinityFree

1. Log in to **InfinityFree Control Panel**
2. Click on your website/account
3. Look for **"FTP Accounts"** or **"FTP Settings"**
4. You'll see:
   - **FTP Host**: Usually `ftpupload.net` or similar
   - **FTP Username**: Your username
   - **FTP Password**: Your password
   - **FTP Port**: Usually `21`
   - **FTP Directory**: Usually `htdocs` or `/htdocs`

**Save these credentials!**

---

## 🦅 Option A: Using FileZilla (Recommended - Works on Linux/Windows/Mac)

### Install FileZilla

**On Linux:**
```bash
sudo apt-get install filezilla
# or
sudo dnf install filezilla
```

**On Windows/Mac:**
- Download from [filezilla-project.org](https://filezilla-project.org)
- Install normally

### Connect to Server

1. **Open FileZilla**
2. At the top, enter your FTP details:
   - **Host**: `ftpupload.net` (or your FTP host)
   - **Username**: Your FTP username
   - **Password**: Your FTP password
   - **Port**: `21`
3. Click **"Quickconnect"** or press Enter

### Upload Files

1. **Left side** (Local site): Navigate to your backend folder
   - `/home/ola/swimming-academy/backend`

2. **Right side** (Remote site): Navigate to `htdocs` folder
   - Usually: `/htdocs` or `/public_html`

3. **Select files/folders** on the left:
   - Select all folders: `app`, `bootstrap`, `config`, `database`, `public`, `resources`, `routes`, `storage`, `vendor`
   - Select all files: `.env`, `artisan`, `composer.json`, etc.

4. **Drag and drop** from left to right, OR
   - Right-click → **"Upload"**

5. **Wait for upload** (78MB vendor folder will take a few minutes)

### Set Permissions (After Upload)

1. Right-click on `storage/` folder → **"File Attributes"**
2. Set to `755` (check all boxes in "Owner" and "Group", read+execute in "Public")
3. Do the same for `bootstrap/cache/`

---

## 🪟 Option B: Using WinSCP (Windows Only)

### Install WinSCP

1. Download from [winscp.net](https://winscp.net)
2. Install normally

### Connect to Server

1. **Open WinSCP**
2. Click **"New Site"**
3. Enter:
   - **File protocol**: `FTP`
   - **Host name**: `ftpupload.net` (or your FTP host)
   - **Port number**: `21`
   - **User name**: Your FTP username
   - **Password**: Your FTP password
4. Click **"Login"**

### Upload Files

1. **Left panel** (Local): Navigate to `/home/ola/swimming-academy/backend`
2. **Right panel** (Remote): Navigate to `htdocs` folder
3. **Select all files and folders** on the left
4. **Drag and drop** to right panel, OR
   - Right-click → **"Upload"**

### Set Permissions

1. Right-click on `storage/` → **"Properties"**
2. Set permissions to `755`
3. Apply to subfolders

---

## 🐧 Option C: Using Command Line FTP (Linux)

### Install FTP Client

```bash
sudo apt-get install ftp
# or
sudo apt-get install lftp  # (better option)
```

### Connect and Upload

**Using `lftp` (recommended):**
```bash
lftp ftp://username@ftpupload.net
# Enter password when prompted

# Navigate to htdocs
cd htdocs

# Upload entire directory
mirror -R /home/ola/swimming-academy/backend .

# Exit
quit
```

**Using `ftp`:**
```bash
ftp ftpupload.net
# Enter username and password

# Navigate
cd htdocs

# Enable binary mode (for all files)
binary

# Upload files
put local-file remote-file

# Upload directory (use mput)
mput *

# Exit
quit
```

---

## 📋 Step-by-Step: FileZilla (Detailed)

### 1. Get FTP Info from InfinityFree

- Host: `ftpupload.net` (example)
- Username: `your_username`
- Password: `your_password`
- Port: `21`

### 2. Open FileZilla

### 3. Enter Connection Details

```
Host: ftpupload.net
Username: your_username
Password: your_password
Port: 21
```

### 4. Click "Quickconnect"

### 5. Navigate Local Site (Left)

- Go to: `/home/ola/swimming-academy/backend`
- You'll see all your folders and files

### 6. Navigate Remote Site (Right)

- Go to: `/htdocs` or `/public_html`
- This is where you'll upload

### 7. Select Everything

- On the left, select ALL folders and files
- Or select specific ones:
  - `app/`
  - `bootstrap/`
  - `config/`
  - `database/`
  - `public/`
  - `resources/`
  - `routes/`
  - `storage/`
  - `vendor/` ← Important!
  - All root files

### 8. Upload

- **Drag and drop** from left to right
- OR right-click → **"Upload"**
- Wait for upload to complete

### 9. Set Permissions

- Right-click `storage/` → **"File Attributes"**
- Set to `755`
- Check **"Recurse into subdirectories"**
- Click **"OK"**

---

## ⚠️ Common Issues

### Connection Failed?

- Check FTP host is correct
- Check port is `21`
- Check username/password
- Try passive mode (FileZilla → Edit → Settings → Connection → Passive)

### Upload Slow?

- Normal for large files (78MB vendor folder)
- Be patient
- Use ZIP upload if too slow

### Permission Denied?

- Make sure you're in `htdocs` folder
- Check folder permissions on server

### Files Not Showing?

- Refresh remote directory (F5)
- Check you're in correct folder

---

## ✅ After Upload Checklist

- [ ] All folders uploaded
- [ ] `vendor/` folder uploaded (78MB)
- [ ] `.env` file uploaded (update with server settings)
- [ ] `.htaccess` files uploaded
- [ ] Permissions set on `storage/` (755)
- [ ] Permissions set on `bootstrap/cache/` (755)

---

## 🎯 Quick Tips

1. **Use FileZilla** - Most user-friendly
2. **Upload vendor folder last** - It's the biggest (78MB)
3. **Be patient** - Large uploads take time
4. **Check permissions** - Storage needs write access
5. **Test connection first** - Make sure FTP works before uploading

---

**FileZilla is the easiest option. Install it and follow the steps above!**


