# Fix 502 Bad Gateway Error

## Problem

The 502 error with "connection reset by peer" means:
- Nginx is running
- PHP-FPM is NOT running or crashing
- The startup script is likely failing before PHP-FPM starts

## Root Cause

The startup script has `set -e` which exits on any error. If migrations fail, the script exits and PHP-FPM never starts.

## Solution Applied

I've updated the Dockerfile to:
1. **Remove `set -e`** - Don't exit on errors
2. **Add error handling to migrations** - Continue even if migrations fail
3. **Ensure services always start** - PHP-FPM and Nginx will start regardless of migration status

## What to Do Now

### Step 1: Push the Updated Dockerfile

```bash
git add Dockerfile
git commit -m "Fix 502 error - ensure services start even if migrations fail"
git push origin main
```

### Step 2: Wait for Railway to Redeploy

Railway will automatically detect the push and redeploy.

### Step 3: Check Logs After Deployment

Go to **Laravel App Service** → **Logs** and look for:
- "Clearing config cache..."
- "Running migrations..."
- "Starting services..."
- Supervisor starting nginx and php-fpm

### Step 4: Verify Services Are Running

In the logs, you should see:
```
INFO success: nginx entered RUNNING state
INFO success: php-fpm entered RUNNING state
```

## If Migrations Are Failing

If migrations fail, the app will still start, but you'll need to fix the database connection:

1. **Check migration errors in logs**
2. **Verify database variables are set correctly**
3. **Run migrations manually** using Railway Deploy Hook:
   - Go to **Settings** → **Deploy** → **Deploy Hooks**
   - Add: `php artisan migrate --force`
   - Redeploy

## Alternative: Run Migrations Separately

If you want to ensure migrations run but not block startup:

1. **Use Railway Deploy Hook** for migrations
2. **Remove migrations from startup script** (optional)
3. **Let app start first**, then run migrations

## Verify Fix

After redeploying, check:
1. ✅ No more 502 errors
2. ✅ Application loads (even if migrations failed)
3. ✅ Check logs for migration status
4. ✅ Run migrations separately if needed

## Expected Log Output After Fix

```
Clearing config cache...
Running migrations...
Migration table created successfully.
Migrating: 2025_11_06_163327_add_role_to_users_table
...
Caching configuration...
Starting services...
INFO supervisord started with pid 1
INFO spawned: 'nginx' with pid 2
INFO spawned: 'php-fpm' with pid 3
INFO success: nginx entered RUNNING state
INFO success: php-fpm entered RUNNING state
```

If you see this, the 502 error should be fixed!

