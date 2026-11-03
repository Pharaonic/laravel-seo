## Basic Usage

The `seo()` helper returns the manager of the current request. Each element is a property, and each setter returns the element, so calls chain.

```php title="app/Http/Controllers/HomeController.php"
public function __invoke()
{
    seo()->title->set('Pharaonic');
    seo()->description->set('Open-source packages for Laravel.');
    seo()->keywords->set('laravel, packages')->add('php');
    seo()->canonical->set(url('/'));
    seo()->images->add(asset('images/cover.png'));

    return view('home');
}
```

`@seo` in the layout prints every tag that has a value:

```blade title="resources/views/layouts/app.blade.php"
<head>
    @seo
</head>
```

```html title="Output" no-copy
<meta charset="UTF-8">
<title>Pharaonic</title>
<meta name="description" content="Open-source packages for Laravel.">
<meta name="keywords" content="laravel, packages, php">
<link rel="canonical" href="https://example.com">
<meta property="og:url" content="https://example.com">
<meta property="og:locale" content="en">
<meta property="og:type" content="website">
<meta property="og:title" content="Pharaonic">
<meta property="og:description" content="Open-source packages for Laravel.">
<meta property="og:image" content="https://example.com/images/cover.png">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Pharaonic">
<meta name="twitter:description" content="Open-source packages for Laravel.">
<meta name="twitter:image" content="https://example.com/images/cover.png">
```

Read a value back with `get()`, and empty it with `clear()`:

```php
seo()->title->get();            // "Pharaonic"
seo()->title->clear()->get();   // null
```

:::tip Render to a String
`seo()->render()` returns the same HTML as `@seo`, for places outside Blade.
:::
