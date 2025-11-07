# Troubleshooting: No Tables in Database

If your MySQL database has no tables, the migrations haven't run successfully. Here's how to fix it:

## Quick Fix: Run Migrations Manually

### Option 1: Using Railway CLI (Recommended)

1. Install Railway CLI:
   ```bash
   npm i -g @railway/cli
   ```

2. Login and link your project:
   ```bash
   railway login
   railway link
   ```

3. Run migrations:
   ```bash
   railway run php artisan migrate --force
   ```

### Option 2: Using Railway Deploy Hook

1. Go to your **Laravel App Service** → **Settings** → **Deploy**
2. Add a **Deploy Hook** with:
   ```bash
   php artisan migrate --force
   ```
3. Redeploy your service

### Option 3: Check and Fix Environment Variables

The most common issue is incorrect database connection variables.

1. **Verify MySQL Service Variables:**
   - Go to **MySQL Service** → **Variables**
   - Note the variable names (they might be `MYSQLHOST`, `MYSQLDATABASE`, etc.)

2. **Verify Laravel App Variables:**
   - Go to **Laravel App Service** → **Variables**
   - Ensure these are set correctly:
     ```bash
     DB_CONNECTION=mysql
     DB_HOST=${{MYSQLHOST}}
     DB_PORT=3306
     DB_DATABASE=${{MYSQLDATABASE}}
     DB_USERNAME=${{MYSQLUSER}}
     DB_PASSWORD=${{MYSQLPASSWORD}}
     ```

3. **Test Connection:**
   - Check your Laravel app logs for database connection errors
   - Look for messages like "SQLSTATE[HY000] [2002]" or connection refused errors

## Common Issues

### Issue 1: Config Cache Problem

If `config:cache` runs before migrations, it might cache empty database config.

**Solution:** The Dockerfile has been updated to:
1. Clear config cache first
2. Run migrations
3. Then cache config

### Issue 2: Database Variables Not Set

**Symptoms:**
- Migrations fail silently
- Database connection errors in logs

**Solution:**
- Verify all `DB_*` variables are set
- Use Railway's variable reference syntax: `${{VARIABLE_NAME}}`
- Ensure MySQL service is linked to your app service

### Issue 3: Migrations Failing Silently

The startup script uses `|| true` which suppresses errors.

**Solution:**
- Check Railway logs for migration errors
- Run migrations manually using Railway CLI
- Check Laravel logs in `storage/logs/laravel.log`

## Verify Migrations Ran

After running migrations, verify tables exist:

1. **Using Railway CLI:**
   ```bash
   railway run php artisan tinker
   ```
   Then in tinker:
   ```php
   DB::select('SHOW TABLES');
   ```

2. **Check Laravel Logs:**
   - Look for "Migration table created successfully"
   - Look for individual migration messages

3. **Check MySQL Directly:**
   - If you have MySQL client access, connect and run:
   ```sql
   SHOW TABLES;
   ```

## Expected Tables

After migrations run, you should see:
- `migrations`
- `users`
- `categories`
- `news_posts`
- `news_likes`
- `social_share_clicks`
- `cache`
- `cache_locks`
- `jobs`
- `job_batches`
- `failed_jobs`
- `sessions`

## Still Having Issues?

1. **Check Railway Logs:**
   - Go to **Laravel App Service** → **Logs**
   - Look for error messages

2. **Verify Database Connection:**
   - Test connection manually using Railway CLI
   - Check that MySQL service is running

3. **Check Environment Variables:**
   - Ensure all variables are set correctly
   - Verify variable reference syntax is correct

4. **Redeploy:**
   - Sometimes a fresh deployment fixes issues
   - Go to **Deployments** → **Redeploy**

