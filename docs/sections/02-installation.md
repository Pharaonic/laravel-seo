## Installation

Install the package with Composer. The service provider is registered automatically.

### Requirements

- PHP 8.0, 8.1 or 8.2
- Laravel 9.0 or later 9.x releases

### Composer Installation

```bash title="Terminal" no-line-numbers
composer require pharaonic/laravel-seo
```

### Publish Configuration

Publish the configuration file to `config/pharaonic/seo.php` when you need to change the defaults or add elements:

```bash title="Terminal" no-line-numbers
php artisan vendor:publish --tag=seo-config
```

### Render the Tags

Print every tag inside the `<head>` of your layout:

```blade title="resources/views/layouts/app.blade.php"
<head>
    @seo
</head>
```

:::success Installation Complete
You're all set! Set a title anywhere in the request, and `@seo` prints it with the rest of the tags.
:::
