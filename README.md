<p align="center"><a href="https://pharaonic.dev/packages/laravel/seo" target="_blank"><img src="https://raw.githubusercontent.com/Pharaonic/laravel-seo/develop/docs/cover.webp"></a></p>

<p align="center">
  <a href="https://php.net" target="_blank"><img src="https://img.shields.io/static/v1?label=PHP&message=8.3%20|%208.4%20|%208.5&color=blue&style=flat-square" alt="PHP Version : 8.3 | 8.4 | 8.5"></a>
  <a href="https://laravel.com" target="_blank"><img src="https://img.shields.io/static/v1?label=Laravel&message=13.x&color=F05340&style=flat-square" alt="Laravel Version : 13.x"></a>
  <img src="https://img.shields.io/static/v1?label=License&message=MIT&color=brightgreen&style=flat-square" alt="License">
  <img src="https://github.com/Pharaonic/laravel-seo/actions/workflows/build.yml/badge.svg?branch=13.x" alt="Build">
  <br>
  <a href="https://packagist.org/packages/Pharaonic/laravel-seo" target="_blank"><img src="https://img.shields.io/static/v1?label=Packagist&message=pharaonic/laravel-seo&color=blue&logo=packagist&logoColor=white" alt="Source"></a>
  <a href="https://packagist.org/packages/pharaonic/laravel-seo" target="_blank"><img src="https://poser.pugx.org/pharaonic/laravel-seo/v" alt="Packagist Version"></a>
  <a href="https://packagist.org/packages/pharaonic/laravel-seo" target="_blank"><img src="https://poser.pugx.org/pharaonic/laravel-seo/downloads" alt="Packagist Downloads"></a>
</p>

<h3 align="center">SEO meta tags, Open Graph and Twitter cards for Laravel.</h3>
<br>

## Installation

```bash
composer require pharaonic/laravel-seo
```

The service provider is registered automatically. Publish the configuration when you need to change the defaults:

```bash
php artisan vendor:publish --tag=seo-config
```

## Usage

```php
seo()->title->set('Pharaonic');
seo()->description->set('Laravel packages by Pharaonic.');
seo()->images->add('https://pharaonic.dev/cover.png');
```

```blade
<head>
    @seo
</head>
```

## Documentation

Full documentation: [Laravel SEO Documentation](https://pharaonic.dev/packages/laravel/seo).

## Support & Security

See [SUPPORT.md](SUPPORT.md) and [SECURITY.md](SECURITY.md). Contributions are welcome; see [CONTRIBUTING.md](CONTRIBUTING.md).

## License

MIT. See [LICENSE](LICENSE).
