# 🔍 Finding Supabase Database Connection Details

## Method 1: Connection Pooling Section (Most Common)

1. In Supabase Dashboard, go to **Settings** (gear icon) → **Database**
2. Look for **"Connection pooling"** section
3. You'll see tabs: **"Session mode"** and **"Transaction mode"**
4. Click on either tab
5. You'll see connection strings there:
   - **Connection string** (URI format)
   - **Connection parameters** (individual values)

## Method 2: Settings → API

1. Go to **Settings** → **API**
2. Scroll down to **"Database"** section
3. You'll find connection details there

## Method 3: Project Settings → Database

1. Go to **Settings** → **Database**
2. Look for **"Connection string"** or **"Connection info"** section
3. It might be at the top of the page, not in Settings submenu

## Method 4: Direct URL

Try going directly to:
```
https://supabase.com/dashboard/project/YOUR_PROJECT_ID/settings/database
```

Then look for:
- **Connection string**
- **Connection pooling**
- **Database URL**

## What You Need for Laravel

You need these values:
- **Host**: Usually `db.xxxxx.supabase.co` or `xxxxx.supabase.co`
- **Port**: `5432`
- **Database**: `postgres`
- **Username**: `postgres`
- **Password**: The one you set when creating the project (or reset it in Database Settings)

## Alternative: Use Connection Pooling String

If you can't find the direct connection string, use the **Connection Pooling** string:
- It's usually in **Settings** → **Database** → **Connection pooling**
- Format: `postgresql://postgres:[PASSWORD]@aws-0-[region].pooler.supabase.com:6543/postgres`

But for Laravel, you can also use the direct connection (port 5432).

## Quick Check

Look for any of these on the Database Settings page:
- "Connection string"
- "Connection info"
- "Database URL"
- "Connection pooling"
- "Direct connection"

The connection details are definitely there, just might be in a different section!

