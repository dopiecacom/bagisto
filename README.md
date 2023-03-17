# Installation
* `composer install`
* `php artisan bagisto:install`
* `php artisan optimize`
* `php artisan serve`

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
\Emsit\BagistoInPostShipping\Providers\ModuleServiceProvider::class,
\RKREZA\Contact\Providers\ModuleServiceProvider::class
```

# Publishing packages assets
`php artisan vendor:publish --force` and select package assets for publishing.

Add code below to the bottom of the `resources\themes\velocity\views\layouts\scripts.blade.php` file:\
```
<script type="text/javascript" src="{{ asset('themes/default/assets/js/jquery.flexdatalist.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('themes/default/assets/js/inpost_shipping.js') }}"></script>
```
... and to the bottom of the `resources\themes\velocity\views\layouts\styles.blade.php` file:\
`<link rel="stylesheet" href="{{ asset('themes/default/assets/css/jquery.flexdatalist.min.css') }}" />`

# PaczkomatyLocationsSeeder
Run `php artisan db:seed --class=Emsit\BagistoInPostShipping\Database\Seeders\DatabaseSeeder`. It may take up to 11 minutes.
