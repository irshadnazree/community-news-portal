# Quick Start: Deploy to Railway

## 🚀 Fast Deployment Steps

### 1. Push to GitHub
```bash
git add .
git commit -m "Add Railway deployment configuration"
git push origin main
```

### 2. Create Railway Project
1. Go to [railway.app](https://railway.app) and sign in
2. Click **"New Project"** → **"Deploy from GitHub repo"**
3. Select your repository
4. Railway will auto-detect the Dockerfile

### 3. Add MySQL Database
1. In your Railway project, click **"+ New"**
2. Select **"Database"** → **"MySQL"**
3. Railway creates the database automatically

### 4. Configure Environment Variables

**In your Laravel App Service** → **Variables**, add:

#### Step 1: Link MySQL Service (Recommended)
1. In your **Laravel App Service**, click **"+ New"** → **"Add Reference"**
2. Select your **MySQL service**
3. Railway will auto-inject MySQL variables

#### Step 2: Add Laravel Variables
Add these variables to your **Laravel App Service**:

```bash
APP_NAME="Community News Portal"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:v0ZMjx+sruecz7smNjk4mcdqzIOLEf1MUEuiEgFur2U=
APP_URL=${{RAILWAY_PUBLIC_DOMAIN}}

# Database Connection (using Railway variable references)
DB_CONNECTION=mysql
DB_HOST=${{MYSQLHOST}}
DB_PORT=3306
DB_DATABASE=${{MYSQLDATABASE}}
DB_USERNAME=${{MYSQLUSER}}
DB_PASSWORD=${{MYSQLPASSWORD}}

SESSION_DRIVER=database
CACHE_DRIVER=database
QUEUE_CONNECTION=database
```

**Important:** Use `${{VARIABLE_NAME}}` syntax to reference MySQL service variables.

**To generate APP_KEY (if you need a new one):**
```bash
# Using Docker (recommended)
docker compose exec app php artisan key:generate --show

# Or if you have PHP installed locally
cd src
php artisan key:generate --show
```

### 5. Verify Database Connection
After adding variables, check your app logs:
1. Go to **Laravel App Service** → **Logs**
2. Look for successful database connection
3. Verify migrations ran successfully

### 6. Deploy!
Railway automatically deploys on every push to `main` branch.

## ✅ Verify Deployment

1. Check **Deployments** tab for build status
2. View **Logs** to see application output
3. Visit your Railway-generated domain

## 🔧 Common Issues

**Build fails:**
- Check that all environment variables are set
- Verify `APP_KEY` is generated

**Database connection error:**
- Ensure MySQL service is running
- Verify database variables are linked to app service

**Assets not loading:**
- Check build logs for npm errors
- Verify `npm run build` completed successfully

## 📚 Full Documentation

See [RAILWAY_DEPLOYMENT.md](./RAILWAY_DEPLOYMENT.md) for detailed instructions.

