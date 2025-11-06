# Community News Submission Portal

A modern, full-featured news submission and management portal built with Laravel 12, Livewire 3, and Tailwind CSS. This application allows users to submit news articles, editors to review and approve content, and admins to manage the platform.

## 🚀 Features

- **User Roles**: Guest, User, Editor, and Admin with role-based access control
- **News Submission**: Users can submit news articles with images
- **Editorial Workflow**: Pending → Published workflow for content review
- **Public News Feed**: Browse published news with search and category filters
- **Engagement**: Like/recommend articles, view counts, and social sharing
- **Admin Panel**: Manage categories and users
- **Responsive Design**: Mobile-first design with Tailwind CSS and DaisyUI

## 📋 Prerequisites

Before you begin, ensure you have the following installed on your system:

- **Docker Desktop** (version 20.10 or higher)
  - [Download for Windows](https://www.docker.com/products/docker-desktop/)
  - [Download for macOS](https://www.docker.com/products/docker-desktop/)
  - [Download for Linux](https://docs.docker.com/engine/install/)
- **Docker Compose** (usually included with Docker Desktop)
- **Git** (for cloning the repository)

### Verify Docker Installation

After installing Docker Desktop, verify the installation:

```bash
docker --version
docker compose version
```

Both commands should return version numbers. If not, please refer to Docker's official documentation.

## 📥 Installation

### Step 1: Clone the Repository

```bash
git clone <repository-url>
cd community-news-portal
```

Replace `<repository-url>` with your actual repository URL.

### Step 2: Start Docker Desktop

Make sure Docker Desktop is running on your machine. You should see the Docker icon in your system tray (Windows/macOS) or verify the service is running (Linux).

### Step 3: Build and Start Containers

Using Makefile (recommended):

```bash
make up
```

Or using Docker Compose directly:

```bash
docker compose up -d --build
```

This command will:
- Build the PHP-FPM container with all required extensions
- Pull and start MySQL 8 database
- Pull and start Nginx web server
- Pull and start phpMyAdmin
- Set up all necessary volumes and networks

**Note**: The first build may take several minutes as it downloads base images and installs dependencies.

### Step 4: Install PHP Dependencies

```bash
make composer-install
```

Or:

```bash
docker compose exec app composer install
```

### Step 5: Install Node.js Dependencies

```bash
make npm-install
```

Or:

```bash
docker compose exec app npm install
```

### Step 6: Build Frontend Assets

```bash
make npm-build
```

Or:

```bash
docker compose exec app npm run build
```

### Step 7: Set Up Environment File

The `.env` file should already be configured for Docker. If you need to modify it:

```bash
# Edit src/.env file
# Database configuration is already set for Docker:
# DB_HOST=db
# DB_DATABASE=community_db
# DB_USERNAME=user
# DB_PASSWORD=user
```

### Step 8: Fix Storage Permissions

```bash
make fix-permission
```

Or:

```bash
docker compose exec app sh -c "mkdir -p storage/framework/{sessions,views,cache} storage/app/public bootstrap/cache && chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache"
```

### Step 9: Create Storage Link

```bash
docker compose exec app php artisan storage:link
```

### Step 10: Run Database Migrations

```bash
make migrate
```

Or:

```bash
docker compose exec app php artisan migrate
```

### Step 11: Seed the Database

```bash
make seed
```

Or:

```bash
docker compose exec app php artisan db:seed
```

This will create:
- **Admin user**: `admin@example.com` / `password`
- **Editor user**: `editor@example.com` / `password`
- **Regular user**: `user@example.com` / `password`
- Sample categories

## 🎯 Running the Application

### Start the Application

If containers are not running:

```bash
make up
```

Or:

```bash
docker compose up -d
```

### Access the Application

Once all containers are running, access the application at:

- **Main Application**: http://localhost:8000
- **phpMyAdmin**: http://localhost:8080
  - Server: `db`
  - Username: `user`
  - Password: `user`

### Stop the Application

```bash
make down
```

Or:

```bash
docker compose down
```

### Restart the Application

```bash
make restart
```

Or:

```bash
docker compose restart
```

## 🛠️ Available Commands

The project includes a `Makefile` with convenient commands:

| Command | Description |
|---------|-------------|
| `make up` | Build and start all containers |
| `make build` | Build Docker images |
| `make down` | Stop and remove all containers |
| `make restart` | Restart all containers |
| `make composer-install` | Install PHP dependencies |
| `make npm-install` | Install Node.js dependencies |
| `make npm-dev` | Run Vite dev server (for development) |
| `make npm-build` | Build production assets |
| `make migrate` | Run database migrations |
| `make seed` | Seed the database |
| `make fix-permission` | Fix storage and cache permissions |
| `make test` | Run PHPUnit tests |
| `make artisan cmd=<command>` | Run Laravel artisan command |

### Running Artisan Commands

```bash
# Example: Clear cache
make artisan cmd="cache:clear"

# Example: Create a controller
make artisan cmd="make:controller ExampleController"
```

## 🧪 Running Tests

```bash
make test
```

Or:

```bash
docker compose exec app php artisan test
```

## 🔧 Development Workflow

### For Frontend Development

If you're working on CSS/JavaScript and want hot-reloading:

```bash
make npm-dev
```

This will start the Vite dev server with hot module replacement. Keep this running in a separate terminal while developing.

### Viewing Logs

```bash
# View all logs
docker compose logs -f

# View specific service logs
docker compose logs -f app
docker compose logs -f nginx
docker compose logs -f db
```

### Accessing the Container

```bash
# Access PHP container
docker compose exec app bash

# Access database
docker compose exec db mysql -u user -puser community_db
```

## 📁 Project Structure

```
community-news-portal/
├── docker/
│   ├── nginx/
│   │   └── default.conf          # Nginx configuration
│   └── php/
│       ├── Dockerfile            # PHP-FPM container definition
│       └── docker-entrypoint.sh  # Container startup script
├── src/                          # Laravel application
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/      # Application controllers
│   │   │   └── Middleware/       # Custom middleware
│   │   ├── Livewire/             # Livewire components
│   │   ├── Models/               # Eloquent models
│   │   └── Policies/             # Authorization policies
│   ├── database/
│   │   ├── migrations/           # Database migrations
│   │   ├── seeders/              # Database seeders
│   │   └── factories/            # Model factories
│   ├── resources/
│   │   ├── views/                # Blade templates
│   │   ├── css/                  # CSS files
│   │   └── js/                   # JavaScript files
│   ├── routes/
│   │   └── web.php               # Web routes
│   └── tests/                    # PHPUnit tests
├── docker-compose.yml             # Docker services configuration
├── Makefile                       # Convenient commands
└── README.md                      # This file
```

## 🔐 Default User Accounts

After seeding the database, you can log in with:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | password |
| Editor | editor@example.com | password |
| User | user@example.com | password |

**⚠️ Important**: Change these passwords in production!

## 🐛 Troubleshooting

### Port Already in Use

If you get an error that port 8000 or 3306 is already in use:

1. Stop the conflicting service, or
2. Modify the ports in `docker-compose.yml`:
   ```yaml
   ports:
     - "8001:80"  # Change 8000 to 8001
   ```

### Permission Denied Errors

If you encounter permission errors with storage:

```bash
make fix-permission
```

### Vite Manifest Not Found

If you see "Vite manifest not found" error:

```bash
make npm-build
```

### Database Connection Errors

1. Ensure the database container is running:
   ```bash
   docker compose ps
   ```

2. Check database logs:
   ```bash
   docker compose logs db
   ```

3. Wait a few seconds after starting containers for MySQL to initialize.

### Container Won't Start

1. Check logs:
   ```bash
   docker compose logs app
   ```

2. Rebuild containers:
   ```bash
   make down
   make up
   ```

### Clear All Caches

```bash
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan view:clear
docker compose exec app php artisan route:clear
```

## 🛠️ Technology Stack

- **Backend**: Laravel 12
- **Frontend**: Livewire 3, Tailwind CSS v4.1, DaisyUI v5
- **Database**: MySQL 8
- **Web Server**: Nginx
- **Containerization**: Docker & Docker Compose
- **Asset Bundling**: Vite 7
- **PHP Version**: 8.3

## 📝 Additional Notes

- The application uses Docker volumes to persist database data
- All source code is mounted as a volume for live development
- Storage permissions are automatically fixed on container startup
- phpMyAdmin is available for database management at http://localhost:8080

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests: `make test`
5. Submit a pull request
