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

### As an Array

`seo()->toArray()` returns the content of the same tags and the `images` list, for an API or a JavaScript frontend. A tag with no content is left out.

```php
seo()->toArray();

[
    'title' => 'Pharaonic',
    'description' => 'Open-source packages for Laravel.',
    'keywords' => 'laravel, packages, php',
    'alternates' => ['ar' => 'https://example.com/ar'],
    'images' => ['https://example.com/images/cover.png'],
    'canonical' => 'https://example.com',
    'og:url' => 'https://example.com',
    'og:locale' => 'en',
    'og:locale:alternate' => ['ar'],
    'og:type' => 'website',
    'og:title' => 'Pharaonic',
    'og:description' => 'Open-source packages for Laravel.',
    'og:image' => ['https://example.com/images/cover.png'],
    'twitter:card' => 'summary',
    'twitter:title' => 'Pharaonic',
    'twitter:description' => 'Open-source packages for Laravel.',
    'twitter:image' => 'https://example.com/images/cover.png',
]
```

`alternates` is keyed by language, and `images`, `og:locale:alternate` and `og:image` are always lists. `images` has no tag of its own: `render()` prints the images through the cards. A custom element is returned under its own name.

:::warning Escaping
These values are not escaped. Print them through something that escapes, such as Blade's `{{ }}` or a frontend framework's head manager.
:::
