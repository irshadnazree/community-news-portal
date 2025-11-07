# Verify Database Connection Setup

## Your Current Configuration

### MySQL Service Variables ✅
Your MySQL variables look correct. Railway auto-generates these.

### Laravel Service Variables ✅
Your Laravel variables are correctly using Railway's reference syntax.

## Potential Issues to Check

### Issue 1: Service Reference Not Linked

**Check:** Is your MySQL service linked to your Laravel app service?

1. Go to your **Laravel App Service** → **Variables**
2. Look for variables that start with `MYSQL*` - these should appear automatically if linked
3. If you don't see `MYSQLHOST`, `MYSQLDATABASE`, etc., you need to link the services

**Fix:**
1. In your **Laravel App Service**, click **"+ New"** → **"Add Reference"**
2. Select your **MySQL service**
3. Railway will automatically inject all MySQL variables

### Issue 2: Variable Resolution

Railway variables use nested references. Your setup:
- MySQL: `MYSQLHOST="${{RAILWAY_PRIVATE_DOMAIN}}"` (references Railway's internal variable)
- Laravel: `DB_HOST="${{MYSQLHOST}}"` (references MySQL service variable)

This should work, but let's verify.

### Issue 3: Check Actual Resolved Values

To see what values Railway is actually using:

1. Go to **Laravel App Service** → **Logs**
2. Look for startup messages
3. Or add a temporary debug route to see env values (remove after testing)

## Quick Test: Verify Connection

### Option 1: Check Laravel Logs

1. Go to **Laravel App Service** → **Logs**
2. Look for:
   - "Running migrations..." message
   - Database connection errors
   - Migration success/failure messages

### Option 2: Add Debug Output (Temporary)

Add this to your startup script temporarily to see resolved values:

```bash
echo "DB_HOST: $DB_HOST"
echo "DB_DATABASE: $DB_DATABASE"
echo "DB_USERNAME: $DB_USERNAME"
echo "DB_PASSWORD: [hidden]"
```

### Option 3: Test Connection Manually

If you have Railway CLI:

```bash
railway run php artisan tinker
```

Then in tinker:
```php
DB::connection()->getPdo();
// Should return PDO object if connected
```

## Recommended Fix: Use Direct References

Instead of nested references, you can use Railway's direct MySQL variables. Update your Laravel variables to:

```bash
DB_CONNECTION=mysql
DB_HOST=${{RAILWAY_PRIVATE_DOMAIN}}
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=${{MYSQL_ROOT_PASSWORD}}
```

**Note:** This requires the MySQL service to be linked so `MYSQL_ROOT_PASSWORD` is available.

## Alternative: Use Railway's Standard MySQL Variables

Railway provides these standard variables when MySQL service is linked:

```bash
DB_CONNECTION=mysql
DB_HOST=${{MYSQLHOST}}
DB_PORT=${{MYSQLPORT}}
DB_DATABASE=${{MYSQLDATABASE}}
DB_USERNAME=${{MYSQLUSER}}
DB_PASSWORD=${{MYSQLPASSWORD}}
```

But ensure these variables exist in your Laravel service (they should appear automatically when MySQL is linked).

## Step-by-Step Verification

1. **Verify MySQL Service is Running:**
   - Check MySQL service logs - should show "ready for connections"

2. **Verify Service Link:**
   - Laravel App Service → Variables
   - Should see `MYSQLHOST`, `MYSQLDATABASE`, etc. listed
   - If not, add reference to MySQL service

3. **Check Variable Resolution:**
   - Look at resolved values in Railway dashboard
   - Or check Laravel logs for connection attempts

4. **Test Migration:**
   - Run migrations manually or wait for startup
   - Check for success/error messages

## If Still Not Working

1. **Check Laravel Logs for Errors:**
   - Look for "SQLSTATE" errors
   - Look for "Connection refused" errors
   - Look for "Access denied" errors

2. **Verify All Variables Are Set:**
   - Double-check all `DB_*` variables are present
   - Ensure no typos in variable names

3. **Try Direct Values (Temporary Test):**
   - Replace `${{MYSQLHOST}}` with actual host value
   - Replace `${{MYSQLDATABASE}}` with `railway`
   - Replace `${{MYSQLUSER}}` with `root`
   - Replace `${{MYSQLPASSWORD}}` with the actual password
   - **Note:** This is just for testing - use references in production

4. **Redeploy:**
   - Sometimes a fresh deployment fixes variable resolution issues

