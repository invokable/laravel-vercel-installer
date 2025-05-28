# Laravel Vercel Installer

Install some files to run Laravel on Vercel.

## Requirements
- PHP >= 8.2
- Laravel >= 11.0

## Installation

```shell
composer require revolution/laravel-vercel-installer --dev

php artisan vercel:install
```

### Uninstall
```shell
composer remove revolution/laravel-vercel-installer --dev
```

## vercel.json
It will probably not work with the new spec that uses `functions` and `rewrites` in vercel.json.

### regions
https://vercel.com/docs/edge-network/regions

### builds
`vercel-php@0.7.3` is PHP8.3

To use another version, check this repository.
https://github.com/vercel-community/php

### routes
If there are other files in public, add them to routes.

```json
    {
        "src": "/images/(.*)",
        "dest": "/public/images/$1"
    },
```

### env
Secret env is set in the vercel settings page.

`php artisan key:generate --show` command generates a new key without updating the .env file. Set this key on the Settings page `APP_KEY`.

## Database
You can use Vercel Postgres or AWS RDS.

https://vercel.com/docs/storage/vercel-postgres

## Cache and session
You can't use the `file` driver.

If you're using a database, you can use the `database` driver.

## TrustProxies

### Laravel 10
If you have any problems with TrustProxies, change `/app/Http/Middleware/TrustProxies.php`.

```php
class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*';

```

### Laravel 11
Change `/bootstrap/app.php`
```php
->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
    })
```

## API prefix
If you use Laravel's API routes, you will need to change the `/api/` route as it conflicts with Vercel.

### Laravel 11
`/bootstrap/app.php`

```php
use Illuminate\Foundation\Application;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'prefix/api',
        health: '/up',
    )
```

## Disable build cache

Deployment often fails when you increase the package version in composer.json. To solve this, add `VERCEL_FORCE_NO_BUILD_CACHE` to Vercel's project settings - `Environment Variables`.　Setting it in `vercel.json` probably won't solve the problem.

- `VERCEL_FORCE_NO_BUILD_CACHE` : `1`

https://vercel.com/docs/deployments/troubleshoot-a-build#managing-build-cache

## LICENSE
MIT  
