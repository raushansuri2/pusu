# Docker Database Setup for CakePHP Quoting Tool

This guide will help you set up the MySQL database for your CakePHP project using Docker.

## Prerequisites
- Docker Desktop installed on your Windows machine
- Git Bash or PowerShell terminal

## Quick Setup

### 1. Start the Database
Run the following command in your project root directory:

```bash
docker-compose up -d
```

This will start:
- MySQL 8.0 database on port 3306
- phpMyAdmin on port 8080 (for database management)

### 2. Database Connection Details
- **Host**: localhost or mysql (if connecting from within Docker)
- **Port**: 3306
- **Database**: datingappgggggg
- **Username**: cakephp
- **Password**: cakephp123
- **Root Password**: root123

### 3. Access phpMyAdmin
Open your browser and go to: http://localhost:8080
- Username: root
- Password: root123

### 4. Configure CakePHP
The database configuration has been set up in:
- `.env` file (environment variables)
- `app_local.php` (local configuration)

### 5. Test Connection
You can test the database connection by running:

```bash
# Check if containers are running
docker-compose ps

# Check database logs
docker-compose logs mysql
```

## Database Migration
If you have database migrations, run them after the database is up:

```bash
# Make sure your CakePHP application can connect to the database
# Then run migrations (if you have them)
bin/cake migrations migrate
```

## Common Commands

### Start containers
```bash
docker-compose up -d
```

### Stop containers
```bash
docker-compose down
```

### View logs
```bash
docker-compose logs mysql
docker-compose logs phpmyadmin
```

### Reset database
```bash
docker-compose down -v  # Removes the database volume
docker-compose up -d    # Starts with fresh database
```

## Troubleshooting

### Port Already in Use
If port 3306 is already in use, you can change it in `docker-compose.yml`:
```yaml
ports:
  - "3307:3306"  # Use 3307 instead
```

### Connection Issues
1. Ensure Docker Desktop is running
2. Check if containers are running: `docker-compose ps`
3. Verify database credentials in `app_local.php`
4. Check MySQL logs: `docker-compose logs mysql`

### Database Not Created
The database should be created automatically. If not:
1. Access phpMyAdmin at http://localhost:8080
2. Create the database manually: `datingappgggggg`
3. Import any existing SQL files if you have them

## Security Notes
- The default passwords are for development only
- Change passwords in production
- The `.env` file contains sensitive information - don't commit it to version control
- The database is accessible from localhost only in this configuration

## Next Steps
1. Start the database: `docker-compose up -d`
2. Configure your CakePHP application to use the database
3. Run any existing migrations
4. Test your application
