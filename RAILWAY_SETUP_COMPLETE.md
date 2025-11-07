# Complete Railway + Git CI/CD Setup Guide

This guide provides a complete, production-ready setup for deploying your Laravel application to Railway with Git CI/CD, based on Railway's official documentation and Laravel best practices.

## 📁 Project Structure

```
community-news-portal/
├── Dockerfile                 # Production Dockerfile
├── railway.json              # Railway configuration
├── nixpacks.toml            # Alternative Nixpacks config
├── railway/
│   └── init-app.sh          # Laravel initialization script
├── .github/
│   └── workflows/
│       └── ci.yml           # GitHub Actions CI/CD
├── .railwayignore           # Files to exclude from builds
└── src/                     # Laravel application
```

## 🚀 Quick Start

### 1. Push to GitHub

```bash
git add .
git commit -m "Configure Railway deployment"
git push origin main
```

### 2. Create Railway Project

1. Go to [railway.app](https://railway.app) and sign in
2. Click **"New Project"** → **"Deploy from GitHub repo"**
3. Select your repository
4. Railway will auto-detect the Dockerfile

### 3. Add MySQL Database

1. In Railway project, click **"+ New"** → **"Database"** → **"MySQL"**
2. Railway creates the database automatically
3. Note the database service name

### 4. Link Database to App

1. In your **Laravel App Service**, click **"+ New"** → **"Add Reference"**
2. Select your **MySQL service**
3. Railway automatically injects MySQL variables

### 5. Configure Environment Variables

In **Laravel App Service** → **Variables**, add:

```bash
# Application
APP_NAME="Community News Portal"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:v0ZMjx+sruecz7smNjk4mcdqzIOLEf1MUEuiEgFur2U=
APP_URL=${{RAILWAY_PUBLIC_DOMAIN}}

# Database (auto-injected when MySQL is linked)
DB_CONNECTION=mysql
DB_HOST=${{MYSQLHOST}}
DB_PORT=${{MYSQLPORT}}
DB_DATABASE=${{MYSQLDATABASE}}
DB_USERNAME=${{MYSQLUSER}}
DB_PASSWORD=${{MYSQLPASSWORD}}

# Session & Cache
SESSION_DRIVER=database
CACHE_DRIVER=database
QUEUE_CONNECTION=database
```

## 🔧 Configuration Files Explained

### Dockerfile

- Uses PHP 8.3-FPM with Alpine Linux
- Includes Nginx and Supervisor
- Builds assets during image build
- Runs Laravel initialization on startup
- Handles Railway's dynamic PORT variable

### railway/init-app.sh

Based on Railway's official Laravel guide:
- Runs database migrations
- Clears all caches
- Caches configuration, routes, views, and events
- Handles errors gracefully

### railway.json

Railway configuration specifying:
- Dockerfile as build method
- Start command configuration

### .github/workflows/ci.yml

GitHub Actions pipeline:
- Runs tests on push/PR
- Checks code quality
- Builds Docker image (on main branch)

## 🔄 Deployment Flow

1. **Developer pushes code** → GitHub
2. **GitHub Actions runs:**
   - Tests (PHPUnit)
   - Code quality (Laravel Pint)
   - Docker build (on main)
3. **Railway detects push** → Auto-deploys
4. **Railway builds:**
   - Builds Docker image
   - Runs `railway/init-app.sh`
   - Starts Nginx + PHP-FPM
5. **Application ready** → Accessible via Railway domain

## 📋 Environment Variables Reference

### Required Variables

| Variable | Description | Example |
|----------|-------------|---------|
| `APP_KEY` | Laravel encryption key | `base64:...` |
| `APP_URL` | Application URL | `${{RAILWAY_PUBLIC_DOMAIN}}` |
| `DB_HOST` | Database host | `${{MYSQLHOST}}` |
| `DB_DATABASE` | Database name | `${{MYSQLDATABASE}}` |
| `DB_USERNAME` | Database user | `${{MYSQLUSER}}` |
| `DB_PASSWORD` | Database password | `${{MYSQLPASSWORD}}` |

### Railway Auto-Provided Variables

- `${{RAILWAY_PUBLIC_DOMAIN}}` - Your app's public domain
- `${{RAILWAY_PRIVATE_DOMAIN}}` - Internal domain
- `${{PORT}}` - Port to listen on (auto-set by Railway)
- `${{MYSQLHOST}}` - MySQL host (when linked)
- `${{MYSQLDATABASE}}` - Database name (when linked)
- `${{MYSQLUSER}}` - Database user (when linked)
- `${{MYSQLPASSWORD}}` - Database password (when linked)

## 🐛 Troubleshooting

### 502 Bad Gateway

**Cause:** PHP-FPM not running

**Solution:**
1. Check Railway logs for startup errors
2. Verify `railway/init-app.sh` has executable permissions
3. Check that migrations aren't blocking startup

### Database Connection Errors

**Cause:** Database variables not set or incorrect

**Solution:**
1. Verify MySQL service is linked to app service
2. Check that `DB_*` variables use `${{}}` syntax
3. Ensure MySQL service is running

### Migrations Not Running

**Cause:** Database connection failing or script error

**Solution:**
1. Check logs for migration errors
2. Run migrations manually via Railway Deploy Hook
3. Verify database credentials

### Assets Not Loading

**Cause:** Build failed or assets not copied

**Solution:**
1. Check build logs for npm errors
2. Verify `npm run build` completed
3. Check that `public/build` exists

## 📚 Additional Resources

- [Railway Documentation](https://docs.railway.app)
- [Railway Laravel Guide](https://docs.railway.app/guides/laravel)
- [Laravel Deployment Guide](https://laravel.com/docs/deployment)
- [GitHub Actions Documentation](https://docs.github.com/en/actions)

## ✅ Deployment Checklist

- [ ] Code pushed to GitHub
- [ ] Railway project created
- [ ] MySQL database service added
- [ ] Database linked to app service
- [ ] Environment variables configured
- [ ] APP_KEY generated and set
- [ ] First deployment successful
- [ ] Application accessible via Railway domain
- [ ] Migrations ran successfully
- [ ] Tests passing in CI/CD

## 🎯 Next Steps

1. **Set up custom domain** (optional)
   - Railway Dashboard → Settings → Domains
   - Add your custom domain
   - Update `APP_URL` variable

2. **Configure monitoring** (optional)
   - Set up error tracking (Sentry, Bugsnag)
   - Configure logging
   - Set up uptime monitoring

3. **Optimize performance**
   - Enable OPcache
   - Configure Redis for cache/sessions
   - Set up CDN for static assets

4. **Set up staging environment**
   - Create separate Railway project
   - Configure different branch for staging
   - Set up staging database

