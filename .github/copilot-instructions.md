# Laravel Vercel Installer - Comprehensive Onboarding Guide

## Overview

The Laravel Vercel Installer is a development package that simplifies the process of deploying Laravel applications to Vercel. This package automatically generates the necessary configuration files and sets up the proper structure to make your Laravel application compatible with Vercel's serverless platform.

**What it does:**
- Generates `vercel.json` configuration file for Vercel deployment
- Creates `.vercelignore` file to exclude unnecessary files from deployment
- Sets up `api/index.php` as the serverless function entry point
- Provides optimal configuration for Laravel on Vercel's serverless environment

## Requirements

- **PHP**: >= 8.3
- **Laravel**: >= 12.0
- **Composer**: For package management

## Installation

### Install the Package

```bash
composer require revolution/laravel-vercel-installer --dev
```

### Run the Installer

```bash
php artisan vercel:install
```

This command will generate the following files in your Laravel project:
- `vercel.json` - Vercel configuration file
- `.vercelignore` - Files to ignore during deployment
- `api/index.php` - Serverless function entry point

### Uninstall

Once the files are generated, you can safely remove the package:

```bash
composer remove revolution/laravel-vercel-installer --dev
```

**Note**: The generated files will remain in your project after uninstalling the package.

## How the Installer Works

The installer creates three essential files:

### 1. `api/index.php`
This is the serverless function entry point that Vercel uses to serve your Laravel application. It simply requires your Laravel application's `public/index.php` file.

### 2. `vercel.json`
Main configuration file that includes:
- **Regions**: Default deployment region (hnd1)
- **Builds**: Configuration for PHP runtime (vercel-php@0.7.3 for PHP 8.3)
- **Routes**: URL routing rules for static assets and application routes
- **Environment Variables**: Optimized Laravel configuration for serverless environment

### 3. `.vercelignore`
Specifies files and directories to exclude from deployment (currently excludes `/vendor`).

## Post-Install Configuration

### Environment Variables

Set the following in Vercel's project settings under "Environment Variables":

#### Required Variables
- `APP_KEY`: Generate with `php artisan key:generate --show`
- `APP_ENV`: Set to `production`
- `APP_DEBUG`: Set to `false`

#### Optional Build Cache Control
- `VERCEL_FORCE_NO_BUILD_CACHE`: Set to `1` if you experience deployment failures when updating composer packages

### Database Configuration

Laravel on Vercel works with various database options:

- **Vercel Postgres**: https://vercel.com/docs/storage/vercel-postgres
- **AWS RDS**: Traditional cloud database
- **Other cloud databases**: Any database accessible via connection string

Configure your database connection in Vercel's environment variables.

### Cache and Session Configuration

**Important**: The `file` driver cannot be used on Vercel's serverless environment.

Recommended configurations:
- **Cache**: Use `array` driver (default in generated config) or external cache like Redis
- **Session**: Use `cookie` driver (default in generated config) or `database` driver if using a database

### TrustProxies Configuration

Vercel uses proxies, so you need to trust all proxies:

#### Laravel 11 (Recommended)
In `/bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->trustProxies(at: '*');
})
```

#### Laravel 10 (Legacy)
In `/app/Http/Middleware/TrustProxies.php`:
```php
protected $proxies = '*';
```

### API Prefix Configuration

Laravel's default `/api/` prefix conflicts with Vercel's function routing. Change it in Laravel 11:

In `/bootstrap/app.php`:
```php
use Illuminate\Foundation\Application;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'prefix/api', // Change this to your preferred prefix
        health: '/up',
    )
```

### Vercel.json Customization

#### Regions
Change the deployment region in `vercel.json`:
```json
"regions": ["hnd1"]
```
See available regions: https://vercel.com/docs/edge-network/regions

#### PHP Version
Default uses `vercel-php@0.7.3` (PHP 8.3). For other versions, check: https://github.com/vercel-community/php

#### Additional Static Routes
Add routes for additional static files in your `public` directory:
```json
{
    "src": "/images/(.*)",
    "dest": "/public/images/$1"
}
```

## Troubleshooting

### Common Issues

#### Deployment Fails After Package Updates
**Solution**: Add `VERCEL_FORCE_NO_BUILD_CACHE=1` to Vercel environment variables.

#### 404 Errors for Static Assets
**Solution**: Add appropriate routes in `vercel.json` for your static files.

#### Session/Auth Issues
**Solution**: Ensure TrustProxies is configured correctly and use `cookie` or `database` session driver.

#### API Routes Not Working
**Solution**: Change Laravel's API prefix to avoid conflict with Vercel's `/api/` function directory.

### Debugging Tips

1. Check Vercel's function logs in the dashboard
2. Verify environment variables are set correctly
3. Ensure all required files are not in `.vercelignore`
4. Test locally with `vercel dev` if possible

## Contributing

### Development Setup

1. Clone the repository:
```bash
git clone https://github.com/invokable/laravel-vercel-installer.git
cd laravel-vercel-installer
```

2. Install dependencies:
```bash
composer install
```

3. Run tests:
```bash
vendor/bin/phpunit
```

4. Check code style:
```bash
vendor/bin/pint --test
```

### Running Tests

The package includes feature tests that verify the installer creates the correct files:

```bash
# Run all tests
vendor/bin/phpunit

# Run with coverage
vendor/bin/phpunit --coverage-html coverage
```

### Code Style

This project uses Laravel Pint for code formatting:

```bash
# Check code style
vendor/bin/pint --test

# Fix code style
vendor/bin/pint
```

### Making Contributions

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Ensure all tests pass
6. Run code style checks
7. Submit a pull request

## Testing Your Changes

### Test the Installation Process

Create a new Laravel project and test the installer:

```bash
# Create test Laravel project
composer create-project laravel/laravel test-app
cd test-app

# Add your local package for testing
composer require revolution/laravel-vercel-installer --dev

# Run the installer
php artisan vercel:install

# Verify files were created
ls -la vercel.json .vercelignore api/index.php
```

## Documentation and Resources

- **Main Documentation**: [README.md](../README.md)
- **License**: [MIT License](../LICENSE)
- **Vercel Documentation**: https://vercel.com/docs
- **Vercel PHP Runtime**: https://github.com/vercel-community/php
- **Laravel Documentation**: https://laravel.com/docs

## Package Information

- **Package Name**: `revolution/laravel-vercel-installer`
- **Repository**: https://github.com/invokable/laravel-vercel-installer
- **License**: MIT
- **Author**: kawax (kawaxbiz@gmail.com)

## Support

For issues and questions:
1. Check this guide and the main README.md
2. Search existing issues on GitHub
3. Create a new issue with detailed reproduction steps
4. Include your Laravel version, PHP version, and Vercel configuration
