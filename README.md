# Installation
* `composer install`
* Set you env file variables:

``` 
-   APP_TIMEZONE
-   APP_LOCALE
-   LOG_CHANNEL
-   APP_CURRENCY
-   DB_DATABASE
-   DB_USERNAME
-   DB_PASSWORD 
```

* `php artisan bagisto:install`
* `php artsian serve`

# How to log in to your domain as an admin
Go to https://example.com/admin/, in case `php artisan bagisto:install` is opted, use the following credentials.

```
email: admin@example.com
password: admin123
```

# Allegro
Working SKU code: `bfe96e64-01f1-4e39-a01a-5b522f3572b7`


# bagisto:install vs concord.php
After installation check `config/concord.php` and add these lines at the bottom of the `modules` array if they're missing:
```
\Emsit\BagistoAllegroAPI\Providers\ModuleServiceProvider::class,
\RKREZA\Contact\Providers\ModuleServiceProvider::class
```

# PaczkomatyLocationsSeeder
Run `php artisan db:seed --class=Emsit\BagistoInPostShipping\Database\Seeders\DatabaseSeeder`. It may take up to 11 minutes.
