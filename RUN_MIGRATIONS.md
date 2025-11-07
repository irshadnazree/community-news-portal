# How to Run Migrations on Railway

## Option 1: Install Railway CLI (Recommended)

### In WSL (Linux):

```bash
# Install Railway CLI using npm
npm install -g @railway/cli

# Or using curl (alternative method)
curl -fsSL https://railway.app/install.sh | sh
```

### After Installation:

```bash
# Login to Railway
railway login

# Link to your project (run this in your project directory)
cd /mnt/c/Users/aliff.azlan/Desktop/news-portal/community-news-portal
railway link

# Run migrations
railway run php artisan migrate --force
```

## Option 2: Use Railway Deploy Hook (Easiest - No CLI Needed)

1. Go to your **Laravel App Service** in Railway dashboard
2. Click **Settings** → **Deploy**
3. Scroll to **Deploy Hooks**
4. Click **"+ New Deploy Hook"**
5. Add this command:
   ```bash
   php artisan migrate --force
   ```
6. Click **Save**
7. Go to **Deployments** tab
8. Click **"Redeploy"** on the latest deployment

This will run migrations automatically on every deployment.

## Option 3: Use Railway's One-Click Deploy Hook

1. Go to your **Laravel App Service**
2. Click **Settings** → **Deploy**
3. Under **Deploy Hooks**, add:
   ```bash
   php artisan migrate --force
   ```
4. Save and redeploy

## Option 4: Check if Migrations Run Automatically

With the updated Dockerfile, migrations should run automatically on startup. Check your logs:

1. Go to **Laravel App Service** → **Logs**
2. Look for:
   - "Clearing config cache..."
   - "Running migrations..."
   - Migration success messages

If you see errors, share them and we can troubleshoot.

## Option 5: Manual Database Access (If Available)

If Railway provides database access:

1. Go to **MySQL Service** → **Connect**
2. Use the connection string or credentials
3. Connect using a MySQL client
4. Run migrations manually (not recommended, but possible)

## Troubleshooting Railway CLI Installation

### If npm is not found in WSL:

```bash
# Install Node.js in WSL
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt-get install -y nodejs

# Then install Railway CLI
npm install -g @railway/cli
```

### Alternative: Use Windows PowerShell

If WSL doesn't work, you can install Railway CLI in Windows:

```powershell
# In Windows PowerShell (not WSL)
npm install -g @railway/cli
railway login
railway link
railway run php artisan migrate --force
```

## Quick Check: Are Migrations Running?

Check your Laravel app logs in Railway:

1. **Laravel App Service** → **Logs**
2. Look for these messages (from updated Dockerfile):
   ```
   Clearing config cache...
   Running migrations...
   Migration table created successfully.
   Migrating: 2025_11_06_163327_add_role_to_users_table
   ...
   ```

If you see "Running migrations..." but no success messages, there's a connection issue.

If you see database connection errors, check:
- MySQL service is running
- Database variables are set correctly
- Services are linked

## Recommended: Use Deploy Hook

The easiest solution is **Option 2** (Deploy Hook) - no CLI installation needed, and migrations will run automatically on every deployment.

